<?php

namespace App\Http\Controllers\auth;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


class Login extends Controller{

    private $success=false;
    private $message;


    public function __invoke(Request $request){
        $validatedData = $request->validate([
            'user' => 'required|string',
            'password' => 'required|string',
        ]);

        if(self::test_connection($request->input('user'), $request->input('password'))){
            self::store_data($request->input('user'), $request->input('password'));
        }else{
            return Redirect::back()->withError( $this->message )->withInput();
        }
        return Redirect::to(route('action'));
    }
    private function test_connection($login, $password){
        $client = new Client(['http_errors' => false, 'headers' => ["X-AUTH-USER"=>$login, "X-AUTH-TOKEN"=>$password]]);

        $request = $client->get(env('KIMAI_API_URL') . 'ping');

        $response = json_decode($request->getBody()->getContents());
        $this->message=$response->message;

        if($response->message=="pong") return true;
        else return false;
    }
    private function store_data($login, $password){
        //generated dynamic token
        $token= hash("sha512", env('APP_SALT_PART1') . $login . env('APP_SALT_PART2').date("Y-m-d_H:i:s").env('APP_SALT_PART3'));
        //cut token in 2 parts
        $middle = 64;
        $token1 = substr($token, 0, $middle);
        $token2 = substr($token, $middle);

        $crypted_password = Crypt::encrypt($token1. openssl_encrypt($password,env('APP_CRYPT_METHOD'), env('APP_CRYPT_KEY')). $token2);

        //insert user if not exist or update password if needed
        if(! DB::table('users')->select('id')->where('username', $login)->exists()){
            $id = DB::table('users')->insertGetId(["username"=>$login, "password"=>$crypted_password, "created_at"=>now(), "updated_at"=>now()]);
        }else{
            $id = DB::table('users')->select('id')->where('username', $login)->first()->id;
            DB::table('users')->where('id', $id)->update(["password"=>$crypted_password, "updated_at"=>now()]);
        }

        Cookie::queue("token", $token1);
        session(["token"=>$token2]);
        session(["username"=>$login]);
        session(["id"=>$id]);


    }

    public function logout(Request $request){
        $request->session()->flush();
        $request->session()->regenerate();
        Cookie::forget('token');
        return Redirect::to(route('login'))->withError( "You've been disconnected" )->withInput();

    }
}
