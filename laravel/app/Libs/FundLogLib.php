<?php
namespace app\Libs;

use Carbon\Carbon;
use App\User;
use App\AppConfig;
use App\BaseSchedule;
use App\CustomSchedule;
use App\FundLog;
use App\ChargeLog;

class FundLogLib
{
    public static function makeLog(User $user)
    {
        // set_time_limit(0);
        $firstFundLog = FundLog::where('user_id', $user->id)->first();
        if (empty($firstFundLog)) {
            $firstFundLog = new FundLog;
            $firstFundLog->user_id = $user->id;
            $firstFundLog->fund = $user->first_fund;
            $firstFundLog->base_fund = $user->total_fund;
            $firstFundLog->show_datetime = $user->start_date;
            $firstFundLog->type = 'start';
            $firstFundLog->save();
        }
        $today = Carbon::today()->format('Y-m-d');
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        
        while (true) {
            $logDoneDate = User::where('id', $user->id)->first()->log_done_date;
            if ($logDoneDate >= $today.' 00:00' && $logDoneDate <= $today.' 23:59') {
                break;
            }
            if ($logDoneDate >= $tomorrow.' 00:00') {
                break;
            }
            
            $newLogDoneDate = new Carbon($logDoneDate);
            $newLogDoneDate = $newLogDoneDate->addDay()->format('Y-m-d');
            
            $cs = CustomSchedule::where([
                ['user_id' , $user->id],
                ['show_datetime' , '>=', $newLogDoneDate.' 00:00'],
                ['show_datetime' , '<=', $newLogDoneDate.' 23:59']
            ])->first();

            $bs = BaseSchedule::where([
                ['rank_id',$user->rank_id],
                ['show_datetime' , '>=', $newLogDoneDate.' 00:00'],
                ['show_datetime' , '<=', $newLogDoneDate.' 23:59']
            ])->first();

            $addFund = 0;
            $type = '';

            $chargeLogs = ChargeLog::where([
                ['user_id',$user->id],
                ['charge_datetime' , '>=', $newLogDoneDate.' 00:00'],
                ['charge_datetime' , '<=', $newLogDoneDate.' 23:59']
            ])->get();

            if (count($chargeLogs) != 0) {
                foreach ($chargeLogs as $key => $chargeLog) {
                    $addFund += $chargeLog->fund;
                    $totalFund = $user->total_fund + $chargeLog->fund;
                    if($totalFund < 0){
                        $chargeLog->fund = $user->total_fund * -1;
                        if($user->now_fund >0){
                            $chargeLog->fund -= $user->now_fund;
                        }
                        $chargeLog->save();
                        $user->total_fund = 0;
                    }else{
                        $user->total_fund = $totalFund;
                    }
                    if ($chargeLog->fund > 0) {
                        $user->investment_count += 1;
                    }
                    $user->save();
                }
            }

            if (isset($cs)) {
                $addFund = $cs->add_fund;
                $type = 'cs';
            } else {
                if (isset($bs)) {
                    $addFund = $bs->add_fund;
                    $type = 'bs';
                } else {
                    $per = 0;
                    if ($user->custom_per_flag) {
                        $per = $user->custom_per;
                        $type = 'auto_custom';
                    } else {
                        $per = AppConfig::first()->default_per;
                        $type = 'auto';
                    }
                    $addFund += $user->total_fund *  $per/100;
                }
            }

            $user->now_fund += $addFund;
            if ($user->now_fund >= 10000000000000000) {
                $user->now_fund = 10000000000000000;
            }
            if ($user->now_fund <= 0) {
                $user->now_fund = 0;
            }
            $user->log_done_date = $newLogDoneDate;
            $user->save();
    
            $fundLog = new FundLog;
            $fundLog->user_id = $user->id;
            $fundLog->fund = $user->now_fund;
            $fundLog->base_fund = $user->total_fund;
            $fundLog->show_datetime = $newLogDoneDate;
            $fundLog->type = $type;
            $fundLog->save();
        }
    }

    public static function checkAllLog()
    {
        // set_time_limit(0);
        $users = User::get();
        if(count($users) > 0){
            $today = Carbon::today()->format('Y-m-d');
            $tomorrow = Carbon::tomorrow()->format('Y-m-d');
    
            $oldestLogDoneDate = Carbon::parse(User::orderBy('log_done_date', 'asc')->first()->log_done_date)
                ->format('Y-m-d');
            
            if ($oldestLogDoneDate < $today) {
                $untreatedUsers = User::where('log_done_date', '<', $today.' 00:00')->get();
                foreach ($untreatedUsers as $key => $user) {
                    self::makeLog($user);
                }
            }
        }
    }

    public static function logReset(User $user)
    {
        // set_time_limit(0);
        $user->now_fund = $user->first_fund;
        $user->total_fund = $user->first_fund;
        $user->log_done_date = $user->start_date;
        $user->investment_count = 1;
        $user->save();
        
        FundLog::where('user_id', $user->id)->delete();
        
        self::makeLog($user);
    }

    public static function todayAddFund(User $user, $addFund)
    {
        $today = Carbon::today()->format('Y-m-d');
        $fundLog = FundLog::where([
            ['user_id' , $user->id],
            ['show_datetime', $today]
        ])->first();
        $user->now_fund += $addFund;
        if ($user->now_fund >= 10000000000000000) {
            $user->now_fund = 10000000000000000;
        }
        if ($user->now_fund <= 0) {
            $user->now_fund = 0;
        }
        $user->total_fund += $addFund;
        if ($user->total_fund >= 10000000000000000) {
            $user->total_fund = 10000000000000000;
        }
        if ($user->total_fund <= 0) {
            $user->total_fund = 0;
        }
        if ($addFund > 0) {
            $user->investment_count += 1;
        }
        $user->save();
        
        $fundLog->fund += $addFund;
        $fundLog->base_fund += $addFund;
        $fundLog->save();
    }

    public static function getFutureLog(User $user, $cou)
    {
        $futureLogs = [];
        $nowFund = $user->now_fund;
        $totalFund = $user->total_fund;

        $day = Carbon::today()->format('Y-m-d');

        $todayLog = FundLog::where([
            ['user_id' , $user->id],
            ['show_datetime' , '>=', $day.' 00:00'],
            ['show_datetime' , '<=', $day.' 23:59']
        ])->first();

        if(empty($todayLog)){
            $day = Carbon::parse($user->start_date)->format('Y-m-d');
            $fundLogs[] =  [
                'user_id' => $user->id,
                'fund' => $user->now_fund,
                'base_fund' => $user->total_fund,
                'show_datetime' => $day,
                'type' => 'start'
            ];  
        }else{
            $fundLogs[] =  [
                'user_id' => $user->id,
                'fund' => $todayLog->fund,
                'base_fund' => $todayLog->base_fund,
                'show_datetime' => $todayLog->show_datetime,
                'type' =>$todayLog->type
            ];
        }
        
        for ($i=0; $i < $cou ; $i++) { 

            $day = Carbon::parse($day)->addDay()->format('Y-m-d');
            $addFund = 0;
            $type = '';
            
            $cs = CustomSchedule::where([
                ['user_id' , $user->id],
                ['show_datetime' , '>=', $day.' 00:00'],
                ['show_datetime' , '<=', $day.' 23:59']
            ])->first();

            $bs = BaseSchedule::where([
                ['rank_id',$user->rank_id],
                ['show_datetime' , '>=', $day.' 00:00'],
                ['show_datetime' , '<=', $day.' 23:59']
            ])->first();

            $chargeLogs = ChargeLog::where([
                ['user_id',$user->id],
                ['charge_datetime' , '>=', $day.' 00:00'],
                ['charge_datetime' , '<=', $day.' 23:59']
            ])->get();

            if (count($chargeLogs) != 0) {
                foreach ($chargeLogs as $key => $chargeLog) {
                    $addFund += $chargeLog->fund;
                    $totalFund += $chargeLog->fund;
                }
            }

            if (isset($cs)) {
                $addFund = $cs->add_fund;
                $type = 'cs';
            } else {
                if (isset($bs)) {
                    $addFund = $bs->add_fund;
                    $type = 'bs';
                } else {
                    $per = 0;
                    if ($user->custom_per_flag) {
                        $per = $user->custom_per;
                        $type = 'auto_custom';
                    } else {
                        $per = AppConfig::first()->default_per;
                        $type = 'auto';
                    }
                    $addFund += $user->total_fund *  $per/100;
                }
            }

            $nowFund += $addFund;
            if ($nowFund >= 10000000000000000) {
                $nowFund = 10000000000000000;
            }
            if ($nowFund <= 0) {
                $nowFund = 0;
            }

            $fundLogs[] =  [
                'user_id' => $user->id,
                'fund' => $nowFund,
                'base_fund' => $totalFund,
                'show_datetime' => $day,
                'type' =>$type
            ];
        }
        return $fundLogs;
    }
}
