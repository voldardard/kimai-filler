<?php


namespace App\Http\Controllers\events;

use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use http\Env\Response;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Object_;


class Manage extends Controller
{
    private $latestDate;
    private $message;
    private $previewed=array();

    public function preview(Request $request){
        $validatedData = $request->validate([
            'morningBegin' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'morningEnd' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'afternoonBegin' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'afternoonEnd' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'days'=>'required|array|min:1',
            'days.*'=>'boolean',
        ]);




        if(! self::checkTimeOrder($request->input('morningBegin'), $request->input('morningEnd'))){
            return Redirect::back()->withError( $this->message )->withInput();
        }
        if(! self::checkTimeOrder($request->input('afternoonBegin'), $request->input('afternoonEnd'))){
            return Redirect::back()->withError( $this->message )->withInput();
        }

        foreach ($request->input('days') as $day => $bool){
            $available_day[]=$day;
        }
        $d_latest= new DateTime(session('KIMAI_LAST_INSERT_END_DATE').' 01:01:01');
        $d_latest_y = (int)$d_latest->format('Y');
        $d_latest_m = (int)$d_latest->format('m');
        $d_latest_d = (int)$d_latest->format('d');

        $d_current= new DateTime('now');
        $d_current_y = (int)$d_current->format('Y');
        $d_current_m = (int)$d_current->format('m');
        $d_current_d = (int)$d_current->format('d');

        /*
         * Retrieve number of day from the last time
         */
        $list=array();
        while($d_latest_y<$d_current_y){
            while($d_latest_m<=12){
                while($d_latest_d<=self::getDayPerMonth($d_latest_m, $d_latest_y)){
                    $list[strtotime("$d_latest_y-$d_latest_m-$d_latest_d")]=date('l', strtotime("$d_latest_y-$d_latest_m-$d_latest_d"));
                    $d_latest_d++;
                }
                $d_latest_d=1;
                $d_latest_m++;
            }
            $d_latest_m=1;
            $d_latest_y++;
        }
        if($d_latest_y==$d_current_y){
            while($d_latest_m<$d_current_m){
                while($d_latest_d<=self::getDayPerMonth($d_latest_m, $d_latest_y)){
                    $list[strtotime("$d_latest_y-$d_latest_m-$d_latest_d")]=date('l', strtotime("$d_latest_y-$d_latest_m-$d_latest_d"));
                    $d_latest_d++;
                }
                $d_latest_d=1;
                $d_latest_m++;
            }
            if($d_latest_m==$d_current_m){
                while($d_latest_d<=$d_current_d){
                    $list[strtotime("$d_latest_y-$d_latest_m-$d_latest_d")]=date('l', strtotime("$d_latest_y-$d_latest_m-$d_latest_d"));
                    $d_latest_d++;
                }
            }
        }

        $random_url = sha1(time());
        $preview_id= DB::table('previews')->insertGetId(['unique_url'=>$random_url, 'users_id'=>session('id'), 'created_at'=>now(), 'updated_at'=>now()]);
        /*
         * Create object array
         */
        foreach ($list as $timestamp => $day){
            if(in_array($day, $available_day)){

            $time=array(
                ['from'=>$request->input('morningBegin'), 'to'=>$request->input('morningEnd')],
                ['from'=>$request->input('afternoonBegin'), 'to'=>$request->input('afternoonEnd')]
            );

                //object base
                $obj_date= array();
                $obj_date['day']=$day;
                $obj_date['timestamp']=$timestamp;
                $obj_date['date']=date( 'Y-m-d', $timestamp );
                $obj_date['created_at']=now();
                $obj_date['updated_at']=now();
                $obj_date['previews_id']=$preview_id;

                //morning
                $obj_date['from']=$request->input('morningBegin');
                $obj_date['to']=$request->input('morningEnd');
                $this->previewed[]=$obj_date;

                //afternoon
                $obj_date['from']=$request->input('afternoonBegin');
                $obj_date['to']=$request->input('afternoonEnd');
                $this->previewed[]=$obj_date;
            }
        }


        DB::table('previews_articles')->insert($this->previewed);

        return Redirect::to('preview/'.$random_url);


    }
    public function delete_article(Request $request, $unique_id, $article_id){
        if(DB::table('previews')->where(["unique_url"=>$unique_id, "users_id"=>session('id')])->exists()){
            $id = DB::table('previews')->select('id')->where(['unique_url'=>$unique_id, 'users_id'=>session('id'), ])->first()->id;
            DB::table('previews_articles')->where(['id'=>$article_id, 'previews_id'=>$id])->delete();
            DB::table('previews')->where([
                'unique_url'=>$unique_id,
                'users_id'=>session('id'),
            ])->update(['updated_at' => now()]);

            return response("article $article_id succsessfully removed");
        }else{
            abort('401');
        }
    }
    public function update_article(Request $request, $unique_id, $article_id){
        $validatedData = $request->validate([
            'from' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'to' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'date' => 'required|date',
        ]);
        if(! self::checkTimeOrder($validatedData['from'], $validatedData['to'])){
            return response()->json(['message'=>'time not in good order'], 405);
        }
        $timestamp= strtotime($request->input('date').' 01:01:01');
        $day = date('l', $timestamp);
        $object=[
            'from'=>$request->input('from'),
            'to'=>$request->input('to'),
            'date'=>$request->input('date'),
            'day'=>$day,
            'timestamp'=>$timestamp,
            'updated_at'=>now()
        ];

        if(DB::table('previews')->where(["unique_url"=>$unique_id, "users_id"=>session('id')])->exists()){
            $id = DB::table('previews')->select('id')->where(['unique_url'=>$unique_id, 'users_id'=>session('id'), ])->first()->id;
            DB::table('previews_articles')->where([
                'id'=>$article_id, 'previews_id'=>$id
            ])->update($object);
            DB::table('previews')->where([
                'unique_url'=>$unique_id,
                'users_id'=>session('id'),
            ])->update(['updated_at' => now()]);

            return response()->json($object);
        }else{
            abort('401');
        }
    }
    public function submit(Request $request, $unique_id){

        if(DB::table('previews')->where(["unique_url"=>$unique_id, "users_id"=>session('id'), "run"=>false])->exists()){
            $preview_id = DB::table('previews')->select('id')->where(['unique_url'=>$unique_id, 'users_id'=>session('id'), ])->first()->id;

            $all_preview=DB::table('previews_articles')->where(['previews_id'=>$preview_id])->get(['date', 'from', 'to', 'id']);
            $response=array();
            foreach ($all_preview as $value){

                $client = new Client(['http_errors' => false, 'headers' => ["X-AUTH-USER"=>config('KIMAI_API_USERNAME'), "X-AUTH-TOKEN"=>config('KIMAI_API_TOKEN')]]);

                $request = $client->post(env('KIMAI_API_URL') . 'timesheets', [RequestOptions::JSON => [
                        'begin' => $value->date."T".$value->from.":00",
                        'end'=> $value->date."T".$value->to.":00",
                        'project'=> intval(session('KIMAI_LAST_INSERT_PROJECT')),
                        'activity'=> intval(session('KIMAI_LAST_INSERT_ACTIVITY')),
                        'tags'=> '',
                        'description'=>''
                        ]
                ]);

                if($request->getStatusCode() == 200) {
                    $response[] = json_decode($request->getBody()->getContents());
                }else{
                    $errors[]=$request->getStatusCode(). " | Error communicating with kimai api for item: ".$value->id;
                }
            }


            DB::table('previews')->where([
                'unique_url'=>$unique_id,
                'users_id'=>session('id'),
            ])->update(['run'=>true, 'updated_at' => now()]);

            if((isset($errors)) && (! empty($errors))){
                return Redirect::back()->withError( $errors )->withInput();
            }
            return response()->json($response);
        }else{
            return Redirect::to('export/'.$unique_id);
        }


    }
    public function add_article(Request $request, $unique_id){
        $validatedData = $request->validate([
            'from' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'to' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'date' => 'required|date',
        ]);
        $timestamp= strtotime($request->input('date').' 01:01:01');
        $day = date('l', $timestamp);
        $object=[
            'from'=>$request->input('from'),
            'to'=>$request->input('to'),
            'date'=>$request->input('date'),
            'day'=>$day,
            'timestamp'=>$timestamp,
            'created_at'=>now(),
            'updated_at'=>now()
        ];
        if(DB::table('previews')->where(["unique_url"=>$unique_id, "users_id"=>session('id')])->exists()){
            $preview_id = DB::table('previews')->select('id')->where(['unique_url'=>$unique_id, 'users_id'=>session('id'), ])->first()->id;
            $object['previews_id']=$preview_id;
            $id=DB::table('previews_articles')->insertGetId($object);
            $object['article_id']=$id;

            DB::table('previews')->where([
                'unique_url'=>$unique_id,
                'users_id'=>session('id'),
            ])->update(['updated_at' => now()]);

            return response()->json($object);
        }else{
            abort('401');
        }
    }
    public function getLatestDate(){
        if(self::setLatestDate()) return new DateTime($this->latestDate);
        else return false; // Status code here
    }
    private function setLatestDate(){
        $client = new Client(['http_errors' => false, 'headers' => ["X-AUTH-USER"=>config('KIMAI_API_USERNAME'), "X-AUTH-TOKEN"=>config('KIMAI_API_TOKEN')]]);

        $request = $client->get(env('KIMAI_API_URL') . 'timesheets/recent');

        $response = json_decode($request->getBody()->getContents());

       // print_r($response);

        $this->latestDate = $response[0]->end;

        if($request->getStatusCode() == 200) return true;
        else {
            $this->message = $request->getStatusCode(). " | ".$response->message;
            return false;
        }


    }
    private function getDayPerMonth($month, $year=null){
        switch($month){
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
            case 1:
                return 31;
            case 2:
                if(empty($year))
                    $year=time();
                else
                    $year = strtotime($year.'-02-01 01:01:01');
                if(date('L', $year) == 1){
                    return 29;
                }else{
                    return 28;
                }
            case 6:
            case 9:
            case 11:
            case 4:
                return 30;
            default:
                return false;

        }
    }
    private function checkTimeOrder($begin, $end){
        $beginTime=explode(':', $begin);
        $endTime=explode(':', $end);
        if (($beginTime[0]<$endTime[0]) || ( ($beginTime[0]==$endTime[0]) && ($beginTime[1]<$endTime[1]) ))return true;
        else {
            $this->message="[$begin -> $end] Time should be in order <";
            return false;
        }
    }
    public function getPreview($unique_url){
        if(DB::table('previews')->where(['unique_url'=>$unique_url, 'users_id'=>session('id')])->exists()){
            $id = DB::table('previews')->select('id')->where(['unique_url'=>$unique_url, 'users_id'=>session('id')])->first()->id;

            return DB::table('previews_articles')->where(['previews_id'=>$id])->get();
        }
    }
}
