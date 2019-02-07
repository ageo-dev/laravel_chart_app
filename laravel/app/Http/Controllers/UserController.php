<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\AppConfig;
use App\BaseSchedule;
use App\CustomSchedule;
use App\FundLog;
use App\ChargeLog;
use App\Libs\FundLogLib;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:user');
        FundLogLib::checkAllLog();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLog()
    {
        $user = Auth::user();

        $appConfig = AppConfig::first();
        $now = Carbon::now();
        $year = $now->year;
        $month = str_pad($now->month, 2, 0, STR_PAD_LEFT);
        $thisMonth = Carbon::parse($year.'-'.$month.'-01');
        $updateDate = Carbon::today()->format('Y-m-d ')
            .str_pad(floor($appConfig->update_hour), 2, 0, STR_PAD_LEFT)
            .':'
            .str_pad(floor($appConfig->update_minute), 2, 0, STR_PAD_LEFT);

        $logs = FundLog::where([
            ['user_id',$user->id],
            ['show_datetime', '>=', $thisMonth]
        ])
        ->orderBy('show_datetime', 'asc')
        ->get();

        if(Carbon::parse($user->start_date)->format('Y-m-d') > Carbon::today()){
            $logs = [];
        }
        


        $beforeDayLog = null;
        $startDayLog = null;
        
        if (count($logs) != 0) {
            $logFirstDay = $logs[0]->show_datetime;
            $beforeDay = Carbon::parse($logFirstDay)->subDay();
            $beforeDayLog = FundLog::where([
                ['user_id',$user->id],
                ['show_datetime', $beforeDay]
            ])
            ->first();
        }

        if (empty($beforeDayLog)) {
            $startDayLog = [
                'user_id' => $user->id,
                'fund' => $user->first_fund,
                'show_datetime' => $user->start_date,
                'type' => 'start'
            ];

            $startDayLog = json_decode(json_encode($startDayLog));
        }

        $chargeLogs = ChargeLog::where([
            ['user_id',$user->id],
            ['charge_datetime', '>=', $thisMonth],
            ['charge_datetime', '<=', $now]
        ])->get();


        return view('user.main')->with([
            'headTitle' => 'Operational status | '.config('app.name', ''),
            'logs' => $logs,
            'user' => $user,
            'beforeDayLog' => $beforeDayLog,
            'startDayLog' => $startDayLog,
            'headTitle' => $month.' / '.$year.' Operation status of ID: '.$user->id.' | '.config('app.name', ''),
            'pageTitle' => $month.' / '.$year.' Operation status',
            'logYear' => $year,
            'logMonth' => $month,
            'chart' => 'circle',
            'chargeLogs' => $chargeLogs,
            'updateDate' => $updateDate,
            'appConfig' => $appConfig
        ]);
    }

    public function logSearch(Request $request)
    {
        $this->validate($request, [
            'log_month' => 'required|numeric|between:1,12',
            'log_year' => 'required|numeric|min:2018',
            'user_id' => 'required|numeric',
            'chart' =>'required|string'
        ]);

        $year = $request->log_year;
        $month = str_pad($request->log_month, 2, 0, STR_PAD_LEFT);
        $dateStart = Carbon::parse($year.'-'.$month.'-01');
        $dateEnd = Carbon::parse($year.'-'.$month)->format('Y-m-t 23:59');

        $appConfig = AppConfig::first();
        $updateDate = Carbon::today()->format('Y-m-d ')
        .str_pad(floor($appConfig->update_hour), 2, 0, STR_PAD_LEFT)
        .':'
        .str_pad(floor($appConfig->update_minute), 2, 0, STR_PAD_LEFT);

        $logs = null;
        if ($dateEnd <= Carbon::today()) {
            $logs = FundLog::where([
                ['user_id',$request->user_id],
                ['show_datetime','>=', $dateStart],
                ['show_datetime','<=', $dateEnd]
            ])
            ->orderBy('show_datetime', 'asc')
            ->get();
        } else {
            $logs = FundLog::where([
                ['user_id',$request->user_id],
                ['show_datetime', '>=', $dateStart],
                ['show_datetime', '<=', Carbon::today()]
            ])
            ->orderBy('show_datetime', 'asc')
            ->get();
        }


        $beforeDayLog = null;
        $startDayLog = null;
        
        if (count($logs) != 0) {
            $logFirstDay = $logs[0]->show_datetime;
            $beforeDay = Carbon::parse($logFirstDay)->subDay();
            $beforeDayLog = FundLog::where([
                ['user_id',$request->user_id],
                ['show_datetime', $beforeDay]
            ])
            ->first();
        }

        $user = User::where('id', $request->user_id)->first();
        if (empty($beforeDayLog)) {
            $startDayLog = [
                'user_id' => $user->id,
                'fund' => $user->first_fund,
                'show_datetime' => $user->start_date,
                'type' => 'start'
            ];

            $startDayLog = json_decode(json_encode($startDayLog));
        }

        $chargeLogs = ChargeLog::where([
            ['user_id',$user->id],
            ['charge_datetime', '>=', $dateStart],
            ['charge_datetime', '<=', $dateEnd]
        ])->get();

        return view('user.main')->with([
            'logs' => $logs,
            'user' => $user,
            'beforeDayLog' => $beforeDayLog,
            'startDayLog' => $startDayLog,
            'headTitle' => $month.' / '.$year.' Operation status of ID: '.$user->id.' | '.config('app.name', ''),
            'pageTitle' => $month.' / '.$year.' Operation status',
            'logYear' => $year,
            'logMonth' => $month,
            'chart' => $request->chart,
            'chargeLogs' => $chargeLogs,
            'updateDate' => $updateDate,
            'appConfig' => $appConfig
        ]);
    }
}
