<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

use App\User;
use App\AppConfig;
use App\BaseSchedule;
use App\CustomSchedule;
use App\Rank;
use App\FundLog;
use App\Libs\FundLogLib;

class ScheduleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        FundLogLib::checkAllLog();
    }

    public function listPaginate()
    {
        return AppConfig::first()->list_paginate;
    }

    public function baseScheduleList($ascOrDesc)
    {
        $pageNum = 0;
        if (isset($_GET['page'])) {
            $pageNum = $_GET['page'];
        } else {
            $pageNum = 1;
        }
        $query = BaseSchedule::query();
        if($ascOrDesc == 'asc'){
            $query->orderBy('updated_at', 'asc');
        }
        if($ascOrDesc == 'desc'){
            $query->orderBy('updated_at', 'desc');
        }
        $lists = $query->paginate($this->listPaginate());
        return view('admin.schedule_list')->with([
            'headTitle' => 'Basic schedule list  |  '.config('app.name', '').'Management screen',
            'listsTitle' => 'Basic schedule list',
            'menu' => 'baseScheduleList',
            'lists'=>$lists,
            'scheduleName' => 'base',
            'pageNum' => $pageNum,
            'ascOrDesc'=>$ascOrDesc
            ]);
    }

    public function addBaseSchedule()
    {
        return view('admin.add_schedule')->with([
            'headTitle' => 'Basic schedule registration  |  '.config('app.name', '').'Management screen',
            'pageTitle'=>'Basic schedule registration',
            'menu' => 'addBaseSchedule',
            'scheduleName'=>'base',
            'rank'=>Rank::get()
            ]);
    }

    public function scheduleValidate($request)
    {
        $addNames = [
            'base',
            'custom'
        ];
    
        $this->validate($request, [
            'show_datetime' => 'required|date',
            'add_fund' => 'required|numeric|max:10000000000000000',
            'schedule_name' => ['required','string',Rule::in($addNames)],
        ]);
    }

    public function addScheduleConf(Request $request)
    {
        $this->scheduleValidate($request);

        if($request->schedule_name == 'base'){
            if(Carbon::parse($request->show_datetime)->format('Y-m-d') == Carbon::today()->format('Y-m-d')){
                return redirect()->route('admin.addBaseSchedule')->with([
                    'message' => 'It is not possible to register the basic schedule of the day'
                ]);
            }
        }

        $duplications = [];
        $rankNames = [];

        $message = null;
        if ($request->schedule_name == 'base') {
            $this->validate($request, [
                'rank' => 'required'
            ]);
            if(Carbon::parse($request->show_datetime)->format('Y-m-d') <= Carbon::yesterday()){
                $message = 'If you want to reflect the past basic schedule on the user\'s chart, please do "Overwrite the past chart" from the user information after registering the basic schedule';   
            }
            foreach ($request->rank as $key => $value) {
                $rankNames[] = Rank::where('id', $value)->first()->name;
                $rec = BaseSchedule::where('show_datetime', $request->show_datetime)
                    ->where('rank_id', $value)->first();
                if (isset($rec)) {
                    $duplications[] = $rec;
                }
            }
        }

        if ($request->schedule_name == 'custom') {
            $user = User::where('id',$request->user_id)->first();
            $this->validate($request, [
                'user_id' => 'required|numeric',
                'show_datetime' => 'required|date|after:'.$user->start_date
            ]);
            if(Carbon::parse($request->show_datetime)->format('Y-m-d') <= Carbon::yesterday()){
                $message = 'If you register a past custom schedule, will the past chart be overwritten?';   
            }
            $rankNames[] = Rank::where('id', $request->rank)->first()->name;
            $rec = CustomSchedule::where('show_datetime', $request->show_datetime)
                ->where('user_id', $request->user_id)->first();
            if (isset($rec)) {
                $duplications[] = $rec;
            }
        }

        return view('admin.conf_schedule')->with([
            'headTitle' => 'Check schedule registration  |  '.config('app.name', '').'Management screen',
            'data' => $request,
            'duplications' => $duplications,
            'rankNames' => $rankNames,
            'message' => $message
            ]);
    }

    public function addScheduleExecute(Request $request)
    {
        if ($request->schedule_name == 'base') {
            foreach ($request->rank as $key => $value) {
                $rec = BaseSchedule::where('show_datetime', $request->show_datetime)
                    ->where('rank_id', $value)->first();
                if (empty($rec)) {
                    $rec = new BaseSchedule;
                }
                $rec->rank_id = $value;
                $rec->add_fund = $request->add_fund;
                $rec->show_datetime = $request->show_datetime;
                $rec->save();
            }

            return redirect()->route('admin.baseScheduleList',['ascOrDesc'=>'desc'])->with([
                    'message'=>'Has registered'
                ]);
        }

        if ($request->schedule_name == 'custom') {
            $rec = CustomSchedule::where('show_datetime', $request->show_datetime)
                ->where('user_id', $request->user_id)->first();
            if (empty($rec)) {
                $rec = new CustomSchedule;
            }


            $user = User::where('id',$request->user_id)->first();
            if (Carbon::parse($request->show_datetime)->format('Y-m-d') < Carbon::today()->format('Y-m-d')){
                $rec->user_id = $request->user_id;
                $rec->add_fund = $request->add_fund;
                $rec->show_datetime = $request->show_datetime;
                $rec->save();
                FundLogLib::logReset($user);
                return redirect()->route('admin.showLog',$user)->with([
                    'message' => 'I overwrote the past chart'
                ]);
            }

            if (Carbon::parse($request->show_datetime)->format('Y-m-d') == Carbon::today()->format('Y-m-d')){
                $confUpdateDatetime = Carbon::parse($request->show_datetime)
                    ->format('Y-m-d '.str_pad(AppConfig::first()->update_hour, 2, 0, STR_PAD_LEFT).':'.str_pad(AppConfig::first()->update_minute, 2, 0, STR_PAD_LEFT).':00');
                $confUpdateDatetime = Carbon::parse($confUpdateDatetime);

                if($confUpdateDatetime <= Carbon::now()){
                    return redirect()->route('admin.addCustomSchedule',$user)->with([
                        'message' => 'As today\'s charts are already reflected, custom schedule registration for today can not be done.'
                    ]);
                }else{
                    $rec->user_id = $request->user_id;
                    $rec->add_fund = $request->add_fund;
                    $rec->show_datetime = $request->show_datetime;
                    $rec->save();
                    FundLogLib::logReset($user);
                    return redirect()->route('admin.customScheduleList',['ascOrDesc'=>'desc'])->with([
                        'message'=>'Has registered'
                    ]);
                }
            }else{
                $rec->user_id = $request->user_id;
                $rec->add_fund = $request->add_fund;
                $rec->show_datetime = $request->show_datetime;
                $rec->save();
                FundLogLib::logReset($user);
                return redirect()->route('admin.customScheduleList',['ascOrDesc'=>'desc'])->with([
                    'message'=>'Has registered'
                ]);
            }
        }
    }


    public function customScheduleList($ascOrDesc)
    {
        $pageNum = 0;
        if (isset($_GET['page'])) {
            $pageNum = $_GET['page'];
        } else {
            $pageNum = 1;
        }

        $query = CustomSchedule::query();
        if($ascOrDesc == 'asc'){
            $query->orderBy('updated_at', 'asc');
        }
        if($ascOrDesc == 'desc'){
            $query->orderBy('updated_at', 'desc');
        }
        $lists = $query->paginate($this->listPaginate());
        return view('admin.schedule_list')->with([
            'headTitle' => 'Custom schedule list  |  '.config('app.name', '').'Management screen',
            'listsTitle' => 'Custom schedule list',
            'menu' => 'customScheduleList',
            'lists'=>$lists,
            'scheduleName' => 'custom',
            'pageNum' => $pageNum,
            'ascOrDesc'=>$ascOrDesc
            ]);
    }

    public function addCustomSchedule($userId)
    {
        $user = User::where('id', $userId)->first();
        return view('admin.add_schedule')->with([
            'headTitle' => 'Custom schedule registration  |  '.config('app.name', '').'Management screen',
            'pageTitle'=>'Custom schedule registration',
            'scheduleName'=>'custom',
            'rank'=>Rank::get(),
            'user'=>$user
            ]);
    }
    
    public function scheduleDelete(Request $request)
    {
        if ($request->schedule_name == 'base') {
            BaseSchedule::where('id', $request->schedule_id)->first()->delete();
            return redirect('admin/base_schedule_list/'.$request->asc_or_desc.'?page='.$request->page_num)->with([
                'message'=>'It has been deleted'
            ]);
        }

        if ($request->schedule_name == 'custom') {
            CustomSchedule::where('id', $request->schedule_id)->first()->delete();
            return redirect('admin/custom_schedule_list/'.$request->asc_or_desc.'?page='.$request->page_num)->with([
                'message'=>'It has been deleted'
            ]);
        }
    }

    public function scheduleArrayDelete(Request $request)
    {
        $array = explode(',', $request->del_array);
        if ($request->schedule_name == 'base') {
            foreach ($array as $key => $value) {
                BaseSchedule::where('id', $value)->first()->delete();
            }
            return redirect('admin/base_schedule_list/'.$request->asc_or_desc.'?page='.$request->page_num)->with([
                'message'=>'It has been deleted'
            ]);
        }

        if ($request->schedule_name == 'custom') {
            foreach ($array as $key => $value) {
                CustomSchedule::where('id', $value)->first()->delete();
            }
            return redirect('admin/base_schedule_list/'.$request->asc_or_desc.'?page='.$request->page_num)->with([
                'message'=>'It has been deleted'
            ]);
        }
    }
}
