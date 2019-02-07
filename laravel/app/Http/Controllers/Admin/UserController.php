<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\User;
use App\AppConfig;
use App\Rank;
use App\FundLog;
use App\ChargeLog;
use App\Libs\FundLogLib;
use App\CustomSchedule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        FundLogLib::checkAllLog();
    }

    public function listPaginate()
    {
        return AppConfig::first()->list_paginate;
    }

    public function index($ascOrDesc)
    {
        $pageNum = 0;
        if (isset($_GET['page'])) {
            $pageNum = $_GET['page'];
        } else {
            $pageNum = 1;
        }
        $query = User::query();
        if ($ascOrDesc == 'asc') {
            $query->orderBy('created_at', 'asc');
        }
        if ($ascOrDesc == 'desc') {
            $query->orderBy('created_at', 'desc');
        }
        $lists = $query->paginate($this->listPaginate());
        
        return view('admin.user_list')->with([
            'headTitle' => 'User list  |  '.config('app.name', '').'Management screen',
            'listsTitle' => 'User list',
            'menu' => 'userList',
            'lists'=>$lists,
            'pageNum' => $pageNum,
            'ascOrDesc'=>$ascOrDesc,
            'type'=>'all'
            ]);
    }


    public function userDelete(User $user)
    {
        $id = $user->id;
        $user->delete();
        return redirect('admin/user_list/desc')->with([
            'message' => 'ID:'.$id.'has been deleted'
        ]);
    }

    public function userEdit(User $user)
    {
        return view('admin.user_edit')->with([
            'headTitle' => 'Edit user information  |  '.config('app.name', '').'Management screen',
            'user'=>$user,
            'rank'=>Rank::get()
            ]);
    }

    public function userEditExecute(Request $request, User $user)
    {
        $this->validate($request, [
            // 'name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255',
            'email' => Rule::unique('users')->ignore($user->id),
            'rank_id' => 'required|integer',
            'first_fund' => 'required|numeric|min:1|max:10000000000000000',
            'custom_per' => 'required|numeric',
            'custom_per_flag' => 'required|boolean',
            'start_date' => 'required|date',
        ]);

        $logResetFlag = false;

        // $user->name = $request->name;
        $user->email = $request->email;
        if (isset($request->password)) {
            $this->validate($request, [
                'password' => 'string|alpha_num|min:6',
            ]);
            $user->password = bcrypt($request->password);
        }
        $user->rank_id = $request->rank_id;
        if ($user->first_fund != $request->first_fund) {
            $chargeLog = ChargeLog::where([
                ['user_id',$user->id],
                ['charge_datetime',$user->start_date]
            ])->first();
            if(empty($chargeLog)){
                $chargeLog = new ChargeLog;
                $chargeLog->user_id = $user->id;
                $chargeLog->charge_datetime = $user->start_date;
            }
            $chargeLog->fund = $request->first_fund;
            $chargeLog->save();
            

            $user->first_fund = $request->first_fund;
            $user->total_fund = $request->first_fund;
            $logResetFlag = true;
        }
        $user->custom_per = $request->custom_per;
        $user->custom_per_flag = $request->custom_per_flag;
        if ($user->start_date != $request->start_date) {
            $chargeLog = ChargeLog::where([
                ['user_id',$user->id],
                ['charge_datetime',$user->start_date]
            ])->first();
            $chargeLog->charge_datetime = $request->start_date;
            $chargeLog->save();

            $user->start_date = $request->start_date;
            $logResetFlag = true;
        }
        if (isset($request->memo)) {
            $user->memo = $request->memo;
        }
        $user->save();

        if ($logResetFlag) {
            FundLogLib::logReset($user);
        }

        return redirect()->route('admin.userEdit', $user)->with(['message'=>'Has been updated']);
    }

    public function addFund(User $user)
    {
        return view('admin.add_fund')->with([
            'headTitle' => 'Add capital  |  '.config('app.name', '').'Management screen',
            'user' => $user
        ]);
    }

    public function addFundCheck(Request $request, User $user)
    {
        $this->validate($request, [
            'add_fund' => 'required|numeric|max:10000000000000000',
            'charge_datetime' => 'required|date|after:'.$user->start_date,
            'confirm' => 'required|boolean'
        ]);

        if (!$request->confirm) {
            if (Carbon::parse($request->charge_datetime) < Carbon::today()) {
                return view('admin.confirm')->with([
                    'headTitle' => 'Chart rewrite confirmation  |  '.config('app.name', '').'Management screen',
                    'pageTitle' => 'Chart rewrite confirmation',
                    'user' => $user,
                    'request' => $request,
                    'confirm' => 'addFund'
                ]);
            } else {
                $this->addFundExecute($request, $user);
                return redirect()->route('admin.userProf', $user)->with([
                    'headTitle' => 'User information details  |  '.config('app.name', '').'Management screen',
                    'user' => $user,
                    'message'=>'We have updated the capital'
                ]);
            }
        } else {
            $this->addFundExecute($request, $user);
            return redirect()->route('admin.userProf', $user)->with([
                'headTitle' => 'User information details  |  '.config('app.name', '').'Management screen',
                'user' => $user,
                'message'=>'We have rewritten the past chart'
            ]);
        }
    }

    public function addFundExecute(Request $request, User $user)
    {
        if ($request->add_fund == 0) {
            return redirect()->route('admin.userProf', $user)->with([
                'headTitle' => 'User information details  |  '.config('app.name', '').'Management screen',
                'user' => $user,
                'message'=>'I did not update the capital because the amount to be added was 0'
            ]);
        } else {
            $charhgeLog = new ChargeLog;
            $charhgeLog->user_id = $user->id;
            $charhgeLog->fund = $request->add_fund;
            $charhgeLog->charge_datetime = $request->charge_datetime;
            $charhgeLog->save();

            if (Carbon::parse($request->charge_datetime)->format('Y-m-d') < Carbon::today()->format('Y-m-d')) {
                FundLogLib::logReset($user);
            }
            if (Carbon::parse($request->charge_datetime)->format('Y-m-d') == Carbon::today()->format('Y-m-d')) {
                $confUpdateDatetime = Carbon::parse($request->charge_datetime)
                    ->format('Y-m-d '.str_pad(floor(AppConfig::first()->update_hour), 2, 0, STR_PAD_LEFT).':'.str_pad(floor(AppConfig::first()->update_minute), 2, 0, STR_PAD_LEFT).':00');

                if ($confUpdateDatetime <= Carbon::now()) {
                    FundLogLib::todayAddFund($user, $request->add_fund);
                } else {
                    FundLogLib::logReset($user);
                }
            }
        }
    }

    public function userFundReset(User $user)
    {
        ChargeLog::where('user_id', $user->id)->delete();
        FundLogLib::logReset($user, true);
        
        return redirect()->route('admin.userProf', $user)->with([
            'message'=>'Has been updated'
        ]);
    }

    public function userProf(User $user)
    {
        return view('admin.user_prof')->with([
            'headTitle' => 'User information details  |  '.config('app.name', '').'Management screen',
            'user' => $user
        ]);
    }

    public function showChargeLog(User $user)
    {
        $chargeLogs = ChargeLog::orderBy('charge_datetime', 'desc')->paginate($this->listPaginate());
        
        return view('admin.charge_log')->with([
            'headTitle' => 'Additional capital log  |  '.config('app.name', '').'Management screen',
            'user' => $user,
            'chargeLogs' => $chargeLogs
            ]);
    }

    public function userSearchView()
    {
        return view('admin.user_search')->with([
            'headTitle' => 'User search  |  '.config('app.name', '').'Management screen',
            'menu' => 'userSearchView',
            'rank'=>Rank::get()
        ]);
    }

    public function userSearch(Request $request)
    {
        $pageNum = 0;
        if (isset($_GET['page'])) {
            $pageNum = $_GET['page'];
        } else {
            $pageNum = 1;
        }

        $order = [
            'asc',
            'desc',
            'start_date_asc',
            'start_date_desc',
            'custom_per_asc',
            'custom_per_desc',
            'investment_count_asc',
            'investment_count_desc'
        ];
        $this->validate($request, [
            // 'name' => 'nullable|string|max:255',
            'id' => 'nullable|string',
            'email' => 'nullable|string|max:255',
            'first_fund_s' => 'nullable|numeric',
            'first_fund_m' => 'nullable|numeric',
            'now_fund_s' => 'nullable|numeric',
            'now_fund_m' => 'nullable|numeric',
            'create_date_s' => 'nullable|date',
            'create_date_m' => 'nullable|date',
            'investment_count_s' => 'nullable|numeric',
            'investment_count_m' => 'nullable|numeric',
            'custom_per_s' => 'nullable|numeric',
            'custom_per_m' => 'nullable|numeric',
            'start_date_s' => 'nullable|date',
            'start_date_m' => 'nullable|date',
            'asc_or_desc' => ['required','string',Rule::in($order)],
        ]);

        // $nameAttay = explode(',', $request->name);
        $idAttay = explode(',', $request->id);
        $emailAttay = explode(',', $request->email);
        $rankArray = $request->rank_id;

        $appendData = [
            // 'name' => $request->name,
            'id' => $request->id,
            'email'=> $request->email,
            'first_fund_s' => $request->first_fund_s,
            'first_fund_m' => $request->first_fund_m,
            'now_fund_s' => $request->now_fund_s,
            'now_fund_m' => $request->now_fund_m,
            'create_date_s' => $request->create_date_s,
            'create_date_m' => $request->create_date_m,
            'investment_count_s' => $request->investment_count_s,
            'investment_count_m' => $request->investment_count_m,
            'custom_per_s' => $request->custom_per_s,
            'custom_per_m' => $request->custom_per_m,
            'start_date_s' => $request->start_date_s,
            'start_date_m' => $request->start_date_m,
            'asc_or_desc' => $request->asc_or_desc,
        ];

        $query = User::query();

        // $query->where(function ($query) use ($nameAttay) {
        //     foreach ($nameAttay as $key => $value) {
        //         if ($key == 0) {
        //             $query->where('name', 'like', '%'.$value.'%');
        //         } else {
        //             $query->orWhere('name', 'like', '%'.$value.'%');
        //         }
        //     }
        // });
        

        if (count($idAttay) != 0) {
            if ($idAttay[0] != '') {
                $query->where(function ($query) use ($idAttay) {
                    foreach ($idAttay as $key => $value) {
                        if ($key == 0) {
                            $query->where('id', $value);
                        } else {
                            $query->orWhere('id', $value);
                        }
                    }
                });
            }
        }

        $query->where(function ($query) use ($emailAttay) {
            foreach ($emailAttay as $key => $value) {
                if ($key == 0) {
                    $query->where('email', 'like', '%'.$value.'%');
                } else {
                    $query->orWhere('email', 'like', '%'.$value.'%');
                }
            }
        });

        if (isset($request->first_fund_s)) {
            $value = $request->first_fund_s;
            $query->where(function ($query) use ($value) {
                $query->where('first_fund', '>=', $value);
            });
        }
        if (isset($request->first_fund_m)) {
            $value = $request->first_fund_m;
            $query->where(function ($query) use ($value) {
                $query->where('first_fund', '<=', $value);
            });
        }

        if (isset($request->now_fund_s)) {
            $value = $request->now_fund_s;
            $query->where(function ($query) use ($value) {
                $query->where('now_fund', '>=', $value);
            });
        }
        if (isset($request->now_fund_m)) {
            $value = $request->now_fund_m;
            $query->where(function ($query) use ($value) {
                $query->where('now_fund', '<=', $value);
            });
        }

        if (isset($request->investment_count_s)) {
            $value = $request->investment_count_s;
            $query->where(function ($query) use ($value) {
                $query->where('investment_count', '>=', $value);
            });
        }
        if (isset($request->investment_count_m)) {
            $value = $request->investment_count_m;
            $query->where(function ($query) use ($value) {
                $query->where('investment_count', '<=', $value);
            });
        }

        if (isset($rankArray)) {
            $query->where(function ($query) use ($rankArray) {
                foreach ($rankArray as $key => $value) {
                    if ($key == 0) {
                        $query->where('rank_id', $value);
                    } else {
                        $query->orWhere('rank_id', $value);
                    }
                }
            });
        }

        if (isset($request->create_date_s)) {
            $value = $request->create_date_s;
            $query->where(function ($query) use ($value) {
                $query->whereDate('created_at', '>=', $value);
            });
        }
        if (isset($request->create_date_m)) {
            $value = $request->create_date_m;
            $query->where(function ($query) use ($value) {
                $query->whereDate('created_at', '<=', $value);
            });
        }

        if (isset($request->start_date_s)) {
            $value = $request->start_date_s;
            $query->where(function ($query) use ($value) {
                $query->whereDate('start_date', '>=', $value);
            });
        }
        if (isset($request->start_date_m)) {
            $value = $request->start_date_m;
            $query->where(function ($query) use ($value) {
                $query->whereDate('start_date', '<=', $value);
            });
        }

        if (isset($request->custom_per_s)) {
            $value = $request->custom_per_s;
            $query->where(function ($query) use ($value) {
                $query->where('custom_per', '>=', $value);
            });
        }
        if (isset($request->custom_per_m)) {
            $value = $request->custom_per_m;
            $query->where(function ($query) use ($value) {
                $query->where('custom_per', '<=', $value);
            });
        }

        if (isset($request->custom_per_flag)) {
            if ($request->custom_per_flag != 'all') {
                $this->validate($request, [
                    'custom_per_flag' => 'boolean'
                ]);
                $value = $request->custom_per_flag;
                $query->where(function ($query) use ($value) {
                    $query->where('custom_per_flag', $value);
                });
            }
        }

        if (isset($request->memo)) {
            $value = $request->memo;
            $query->where(function ($query) use ($value) {
                $query->where('memo', 'like', '%'.$value.'%');
            });
        }

        if ($request->asc_or_desc == 'asc') {
            $query->orderBy('created_at', 'asc');
        }
        if ($request->asc_or_desc == 'desc') {
            $query->orderBy('created_at', 'desc');
        }
        if ($request->asc_or_desc == 'start_date_asc') {
            $query->orderBy('start_date', 'asc');
        }
        if ($request->asc_or_desc == 'start_date_desc') {
            $query->orderBy('start_date', 'desc');
        }
        if ($request->asc_or_desc == 'custom_per_asc') {
            $query->orderBy('custom_per', 'asc');
        }
        if ($request->asc_or_desc == 'custom_per_desc') {
            $query->orderBy('custom_per', 'desc');
        }
        if ($request->asc_or_desc == 'investment_count_asc') {
            $query->orderBy('investment_count', 'asc');
        }
        if ($request->asc_or_desc == 'investment_count_desc') {
            $query->orderBy('investment_count', 'desc');
        }

        $allLists = $query->get();
        $listsCouAll = count($allLists);
        $lists = $query->paginate($this->listPaginate());
        $listsCou = count($lists);

        $allUserIdArray = [];
        foreach ($allLists as $key => $list) {
            $allUserIdArray[] = $list->id;
        }

        return view('admin.user_list')->with([
            'headTitle' => 'User search results  |  '.config('app.name', '').'Management screen',
            'listsTitle' => 'Applicable users list',
            'lists'=>$lists,
            'appendData' => $appendData,
            'pageNum' => $pageNum,
            'type'=>'search',
            'listsCou' => $listsCou,
            'listsCouAll' => $listsCouAll,
            'allUserIdArray' => $allUserIdArray
            ]);
    }

    public function userBulkEdit(Request $request)
    {
        $rank = Rank::get();
        $idArrayPlane = $request->id_array;
        $idArray = explode(',',$idArrayPlane);
        $idArray = json_decode(json_encode($idArray));
        return view('admin.user_bulk_edit')->with([
            'headTitle' => 'User bulk edit  |  '.config('app.name', '').'Management screen',
            'listsTitle' => 'User bulk edit',
            'rank' => $rank,
            'idArray' => $idArray,
            'idArrayPlane' => $idArrayPlane
            ]);
    }

    public function userBulkEditCheck(Request $request)
    {
        $order = [
            'non',
            'yes',
            'no'
        ];
        $this->validate($request, [
            'id_array' => 'required|string',
            'rank_id' => 'required|numeric',
            'start_date' => 'nullable|date',
            'custom_per' => 'nullable|numeric',
            'custom_per_flag' => ['required','string',Rule::in($order)],
            'first_fund' => 'nullable|numeric',
            'show_datetime' => 'nullable|date',
            'add_fund' => 'nullable|numeric'
        ]);
        $idArrayPlane = $request->id_array;
        $idArray = explode(',',$idArrayPlane);

        $rankFlag = false;
        $startDateFlag = false;
        $customPerChangeFlag = false;
        $customPerUseFlag = false;
        $firstFundFlag = false;
        $csFlag = false;

        $rankName = '';

        $csUpdateLists = [];
        $add_fund = 0;
        if(isset($request->show_datetime)){
            $csFlag = true;
            foreach ($idArray as $key => $value) {
                $cs = CustomSchedule::where([
                    ['user_id',$value],
                    ['show_datetime',$request->show_datetime]
                ])->first();
                if (isset($cs)) {
                    $csUpdateLists[] = $cs;
                }
            }
            if(isset($request->add_fund)){
                $add_fund = $request->add_fund;
            }
        }

        if($request->rank_id != 0){
            $rankFlag = true;
            $rankName = Rank::where('id',$request->rank_id)->first()->name;
        }

        if(isset($request->start_date)){
            $startDateFlag = true;
        }

        if(isset($request->custom_per)){
            $customPerChangeFlag = true;
        }

        if($request->custom_per_flag != 'non'){
            $customPerUseFlag = true;
        }

        if(isset($request->first_fund)){
            $firstFundFlag = true;
        }

        $idArray = json_decode(json_encode($idArray));
        return view('admin.user_bulk_edit_conf')->with([
            'headTitle' => 'Confirm User Bulk Edit  |  '.config('app.name', '').'Management screen',
            'listsTitle' => 'Confirm User Bulk Edit',
            'rankFlag' => $rankFlag,
            'startDateFlag' => $startDateFlag,
            'customPerChangeFlag' => $customPerChangeFlag,
            'customPerUseFlag' => $customPerUseFlag,
            'firstFundFlag' => $firstFundFlag,
            'csFlag' => $csFlag,
            'idArray' => $idArray,
            'idArrayPlane' => $idArrayPlane,
            'request' => $request,
            'rankName' => $rankName,
            'csUpdateLists' => $csUpdateLists
            ]);
    }

    public function userBulkEditDone(Request $request)
    {
        $order = [
            'non',
            'yes',
            'no'
        ];
        $this->validate($request, [
            'id_array' => 'required|string',
            'rank_id' => 'required|numeric',
            'start_date' => 'nullable|date',
            'custom_per' => 'nullable|numeric',
            'custom_per_flag' => ['required','string',Rule::in($order)],
            'first_fund' => 'nullable|numeric',
            'show_datetime' => 'nullable|date',
            'add_fund' => 'nullable|numeric',
        ]);
        $idArrayPlane = $request->id_array;
        $idArray = explode(',',$idArrayPlane);

        // set_time_limit(0);
        foreach ($idArray as $key => $value) {
            $user = User::where('id',$value)->first();

            $logResetFlag = false;

            if($request->cs_flag){
                $cs = CustomSchedule::where([
                    ['user_id',$user->id],
                    ['show_datetime',$request->show_datetime]
                ])->first();
                if (empty($cs)) {
                    $cs = new CustomSchedule;
                }
                $cs->user_id = $user->id;
                $cs->show_datetime = $request->show_datetime;
                $cs->add_fund = $request->add_fund;
                $cs->save();

                if (Carbon::parse($cs->show_datetime) <= Carbon::today()) {
                    $logResetFlag = true;
                }
            }

            if($request->rank_flag){
                $user->rank_id = $request->rank_id;
            }
            if($request->start_date_flag){
                $user->start_date = $request->start_date;
                $logResetFlag = true;
            }
            if($request->custom_per_change_flag){
                $user->custom_per = $request->custom_per;
            }
            if($request->custom_per_use_flag){
                if($request->custom_per_flag == 'yes'){
                    $user->custom_per_flag = 1;
                }
                if($request->custom_per_flag == 'no'){
                    $user->custom_per_flag = 0;
                }
            }
            if($request->first_fund_flag){
                $user->start_date = $request->start_date;
                $logResetFlag = true;
            }
            
            if($logResetFlag){
                FundLogLib::logReset($user);
            }
        }
        
        return redirect('admin/user_list/desc')->with([
            'message' => 'Edited'
        ]);
    }

    public function userBulkDelete(Request $request){
        $this->validate($request, [
            'id_array' => 'required|string'
        ]);
        $idArrayPlane = $request->id_array;
        $idArray = explode(',',$idArrayPlane);
        // set_time_limit(0);
        foreach ($idArray as $key => $value) {
            $user = User::where('id',$value)->first();
            $user->delete();
        }
        return redirect('admin/user_list/desc')->with([
            'message' => 'The deletion process for the specified user has completed'
        ]);
    }
}
