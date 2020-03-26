<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class auth
{
    private $message;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $username = $request->session()->get('username');
        $token1 = Cookie::get('token', 'hahaha');
        $token2 = $request->session()->get('token');

        if(!isset($token1) || empty($token1) || !isset($token2) || empty($token2) || !isset($username) || empty($username)){
            return Redirect::to(route('login'));

        }

        $sql_response= DB::table('users')->select('id', 'password')->where('username', $username)->first();
        if(! is_object($sql_response)){
            return Redirect::to(route('login'));
        }
        $cryptedPassword= Crypt::decrypt($sql_response->password);
        $token1_lengh=strlen($token1);
        $token2_lengh=strlen($token2);
        $pass_lengh=strlen($cryptedPassword);
        $cryptedPassword = substr($cryptedPassword, $token1_lengh, ($pass_lengh-$token2_lengh-$token1_lengh));
        $decryptedPassword = openssl_decrypt($cryptedPassword, env('APP_CRYPT_METHOD'), env('APP_CRYPT_KEY'));

        self::test_connection($username, $decryptedPassword);

        if(! self::test_connection($username, $decryptedPassword)){
            return Redirect::to(route('logout'));
        }
        Config::push('KIMAI_API_TOKEN', $decryptedPassword);
        Config::push('KIMAI_API_USERNAME', $username);

        if(! self::setLatestDate($username, $decryptedPassword)){
            return Redirect::back()->withError( $this->message )->withInput();
        }

        return $next($request);
    }

    private function test_connection($login, $password){
        $client = new Client(['http_errors' => false, 'headers' => ["X-AUTH-USER"=>$login, "X-AUTH-TOKEN"=>$password]]);

        $request = $client->get(env('KIMAI_API_URL') . 'ping');

        $response = json_decode($request->getBody()->getContents());
        $this->message=$response->message;

        if($response->message=="pong") return true;
        else return false;
    }
    private function setLatestDate(){
        $client = new Client(['http_errors' => false, 'headers' => ["X-AUTH-USER"=>config('KIMAI_API_USERNAME'), "X-AUTH-TOKEN"=>config('KIMAI_API_TOKEN')]]);

        $request = $client->get(env('KIMAI_API_URL') . 'timesheets/recent');

        $response = json_decode($request->getBody()->getContents());




        if($request->getStatusCode() == 200) {
            $last_inserted_date_end=new \DateTime($response[0]->end);
            $last_inserted_date_from=new \DateTime($response[0]->begin);

            session(['KIMAI_LAST_INSERT_FROM_DATE'=> $last_inserted_date_from->format('Y-m-d')]);
            session(['KIMAI_LAST_INSERT_END_DATE'=> $last_inserted_date_end->format('Y-m-d')]);
            session(['KIMAI_LAST_INSERT_FROM'=> $last_inserted_date_from->format('H:i')]);
            session(['KIMAI_LAST_INSERT_END'=> $last_inserted_date_end->format('H:i')]);
            session(['KIMAI_LAST_INSERT_PROJECT'=> $response[0]->project->id]);
            session(['KIMAI_LAST_INSERT_ACTIVITY'=> $response[0]->activity->id]);



            return true;
        }
        else {
            $this->message = $request->getStatusCode(). " | ".$response->message;
            return false;
        }


    }
}
