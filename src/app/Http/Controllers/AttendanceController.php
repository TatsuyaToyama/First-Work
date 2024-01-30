<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Record;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DateTime;
use DateInterval;

class AttendanceController extends Controller
{
    public function index(){

        $user = Auth::user();

        // ユーザーがログインしているか確認
        if (isset($user['name'])) {
            $name = $user->name;
            $record_item=[];
            $record_item['name']=$name;

            // 本日までの最新のデータを取得
            $search_item= Record::where('name', $record_item['name'])
                        ->latest('date')
                        ->first();
                        
            // 本日までの勤務が終了している場合、本日のデータとして新規追加する
            if($search_item ==null || $search_item['work_end'] !== null){

                // 本日の勤務情報が既にあるかを確認し取得する
                $currentDate = date('Y-m-d');
                $search_item= Record::where('name', $record_item['name'])
                            ->where('date', $currentDate)
                            ->first(); 

                // 本日の勤務情報がない場合、勤務情報をNullで送る
                if($search_item == null){
                    $search_item=[
                        'name' => $name,
                        'date' =>$currentDate,
                        'work_start'=> null,
                        'work_end'=>null,
                        'break_start'=>null,
                        'break_end'=>null
                    ];
                }
                }

        } else {
            //ログインしていない場合、ログイン画面に遷移
            return redirect()->route('login')->withInput()->withErrors(['error' => 'ログインしてください。']);
        };
        return view('index',['search_item'=>$search_item]);
    }

    public function record(Request $request){
        $record_item = $request -> only(['name','date','work_start','work_end','break_start','break_end']);

        // 名前と日付をもとにTableから検索する
        $search_item= Record::where('name', $record_item['name'])
                    ->where('date', $record_item['date'])
                    ->first(); 

        // 検索結果があったら該当行に格納、ない場合新しくIdを付与して名前、日付ごと格納

        if($search_item !== null){
            // 休憩終了が打ち込まれていた際に総休憩時間を計算し、入力する
            if($record_item['break_end'] !== null){
                //計算のためTime型からDateTime型に変更
                $breakEndTimestamp = DateTime::createFromFormat('H:i:s', $record_item['break_end'])->getTimestamp();
                $breakStartTimestamp = DateTime::createFromFormat('H:i:s', $search_item['break_start']) ->getTimestamp();

                $break_total = $breakEndTimestamp-$breakStartTimestamp;
                
                $record_item['break_total']=$break_total;
                $search_item['break_start']= null;
                $record_item['break_end']= null;
                
                if($search_item['break_total'] !== null){

                    $search_breakTotalTimestamp = DateTime::createFromFormat('H:i:s', $search_item['break_total'])->getTimestamp();

                    $record_item['break_total'] =$record_item['break_total']+ $search_breakTotalTimestamp ;
                }else{

                    $search_breakTotalTimestamp = DateTime::createFromFormat('H:i:s', '00:00:00')->getTimestamp();

                    $record_item['break_total'] =$record_item['break_total']+ $search_breakTotalTimestamp ;
                }
                $record_item['break_total'] = date('H:i:s', $record_item['break_total']);
            }

            
            // Nullの値を消す
            $record_item = array_filter($record_item, function ($value) {
                return $value !== null;});

            $search_item->fill($record_item);
            $search_item->save();
            
        }else{
                Record::create($record_item);
                $search_item= $record_item;
                 
        }
        // return view('index',['search_item'=>$search_item]);
        return redirect()->route('records.index')->with('search_item', $search_item);
    }

    public function dashboard(){

    return redirect()->route('records.index');
    }


    public function attendance(Request $request){
        $date=[];
        if(isset($request['selectedDate'])){
            $date['selectedDate'] = $request['selectedDate'];
            $request->session()->put([
            '_old_input' => [
            'selectedDate' => $date['selectedDate']
        ]
        ]);
        }else{
            $date['selectedDate'] = Carbon::now()->toDateString();
            $request->session()->put([
        '_old_input' => [
            'selectedDate' => Carbon::now()->toDateString()
        ]
        ]);
        }

        $search_date= Record::where('date', $date['selectedDate'])
                    ->select('name', 'work_start', 'work_end', 'break_total','created_at','updated_at')
                    ->paginate(5);

        $number = count($search_date);

        for($i=0; $i<$number ;$i++){

            if($search_date[$i]['work_end'] !== null){

                // work_start, work_end, break_totalを計算可能な型に変更
                $workStartTimestamp = DateTime::createFromFormat('H:i:s', $search_date[$i]['work_start'])->getTimestamp();
                $workEndTimestamp = DateTime::createFromFormat('H:i:s', $search_date[$i]['work_end'])->getTimestamp();
                // 日跨ぎ分の時間加算
                $createdDate=Carbon::parse($search_date[$i]['created_at'])->toDateString();
                $updatedDate=Carbon::parse($search_date[$i]['updated_at'])->toDateString();
                $dateDifference = strtotime($updatedDate) - strtotime($createdDate);
                $workEndTimestamp += $dateDifference;

                if($search_date[$i]['break_total'] !== null){
                    list($hours, $minutes, $seconds) = explode(':',$search_date[$i]['break_total']);
                    $breakTotalTime = $hours * 3600 + $minutes * 60 + $seconds;
                }else{
                    $search_date[$i]['break_total'] ="-";
                    $breakTotalTime=0;
                }

                $workTime = $workEndTimestamp - $workStartTimestamp - $breakTotalTime;

                // 秒数から00:00:00の形に変換
                    $hours = floor($workTime / 3600); //時間
                    $minutes = floor(($workTime / 60) % 60); //分
                    $seconds = floor($workTime % 60); //秒
                $workTime = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
                $search_date[$i]['work_time']=$workTime;

                //work_endに過剰日付分の時間を加算
                if($dateDifference>0){
                    list($hours, $minutes, $seconds) = explode(':',$search_date[$i]['work_end']);
                    $hours += $dateDifference/3600; 
                $workEndTimestamp = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
                $search_date[$i]['work_end']=$workEndTimestamp;
                }

            }else{
                if($search_date[$i]['break_total'] !== null){
                    list($hours, $minutes, $seconds) = explode(':',$search_date[$i]['break_total']);
                    $breakTotalTime = $hours * 3600 + $minutes * 60 + $seconds;
                }else{
                    $search_date[$i]['break_total'] ="-";
                    $breakTotalTime=0;
                }
                $search_date[$i]['work_end'] = "-";
                $search_date[$i]['work_time']="-";
            }
        }

        return view('attendance',['search_date' => $search_date]);
    }

    public function user(){
        $search_user= User::select('name')
                    ->distinct(['name'])
                    ->paginate(5);
        return view('user',['search_user' => $search_user]);
    }


    public function search(Request $request){
        $search_name=$request -> only(['name']);
        $request->session()->put([
            '_old_input' => [
            'name' => $search_name
        ]
        ]);

        $search_user= Record::where('name',$search_name)
                    ->select('name','date','work_start', 'work_end', 'break_total','created_at','updated_at')
                    ->paginate(5);
        
        if($search_user->total() !== 0){
            $number = count($search_user);
            for($i=0; $i<$number ;$i++){
                 if($search_user[$i]['break_total'] !== null){
                    list($hours, $minutes, $seconds) = explode(':',$search_user[$i]['break_total']);
                    $breakTotalTime = $hours * 3600 + $minutes * 60 + $seconds;
                }else{
                    $search_user[$i]['break_total'] ="-";
                    $breakTotalTime=0;
                }
                if($search_user[$i]['work_end'] !== null){
                    // work_start, work_end, break_totalを計算可能な型に変更
                    $workStartTimestamp = DateTime::createFromFormat('H:i:s', $search_user[$i]['work_start'])->getTimestamp();
                    $workEndTimestamp = DateTime::createFromFormat('H:i:s', $search_user[$i]['work_end'])->getTimestamp();
                     
                    // 日跨ぎ分の時間加算
                    $createdDate=Carbon::parse($search_user[$i]['created_at'])->toDateString();
                    $updatedDate=Carbon::parse($search_user[$i]['updated_at'])->toDateString();
                    $dateDifference = strtotime($updatedDate) - strtotime($createdDate);
                    $workEndTimestamp += $dateDifference;

                    $workTime = $workEndTimestamp - $workStartTimestamp - $breakTotalTime;

                    // 秒数から00:00:00の形に変換
                        $hours = floor($workTime / 3600); //時間
                        $minutes = floor(($workTime / 60) % 60); //分
                        $seconds = floor($workTime % 60); //秒
                    $workTime = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
                    $search_user[$i]['work_time']=$workTime;

                    //work_endに過剰日付分の時間を加算
                    if($dateDifference>0){
                        list($hours, $minutes, $seconds) = explode(':',$search_user[$i]['work_end']);
                        $hours += $dateDifference/3600; 
                    $workEndTimestamp = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
                    $search_user[$i]['work_end']=$workEndTimestamp;
                    }
                }else{
                    $search_user[$i]['work_end'] = "-";
                    $search_user[$i]['work_time'] = "-";
                }
            }
        }else{
            $search_user[0]=[
                'name'=> $search_name['name'],
                'date'=> "-",
                'work_start' => "-",
                'work_end' => "-",
                'break_total' => "-",
                'work_time' => "-"
                ];
        }
        return view('search',['search_user' => $search_user]);
    }  
}
