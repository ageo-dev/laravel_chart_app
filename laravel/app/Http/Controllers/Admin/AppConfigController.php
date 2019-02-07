<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

use App\AppConfig;
use App\Rank;

class AppConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function settingView(){
        $appConfig = AppConfig::first();
        $rank = Rank::get();
        return view('admin.config_setting')->with([
            'headTitle' => 'Configuration  |  '.config('app.name', '').'Management screen',
            'appConfig' => $appConfig,
            'ranks' => $rank
        ]);
    }

    public function setting(Request $request)
    {
        $this->validate($request, [
            'update_hour' => 'required|numeric|between:0,23',
            'update_minute' => 'required|numeric|between:0,59',
            'default_per' => 'required|numeric',
            'list_paginate' => 'required|numeric|between:1,300',
        ]);
        $appConfig = AppConfig::first();
        $appConfig->update_hour = $request->update_hour;
        $appConfig->update_minute = $request->update_minute;
        $appConfig->default_per = $request->default_per;
        $appConfig->list_paginate = $request->list_paginate;
        $appConfig->save();
        return redirect()->route('admin.configSettingView')->with([
            'headTitle' => 'Configuration  |  '.config('app.name', '').'Management screen',
            'appConfig' => $appConfig,
            'message' => 'Changed'
        ]);
    }

    public function roleSetting(Request $request)
    {
        $i = 1;
        foreach ($request->all() as $key => $value) {
            if($key != '_token'){
                $this->validate($request, [
                    'rank_name'.$i => 'required|string|max:50|unique:ranks,name,'.$i,
                ]);     
                $i++;
            }
        }

        $dupValiFlag = false;
        $i = 1;
        foreach ($request->all() as $key => $value) {
            if($key != '_token'){
                $n = 0;
                foreach ($request->all() as $k => $v) {
                    if($key != $k){
                        if($value == $v){
                            $dupValiFlag = true;
                        }
                    }
                }
            }
        }

        if($dupValiFlag){
            return redirect()->route('admin.configSettingView')->with([
                'dupValiFlag' => true
            ]);
        }else{
            $i = 1;
            foreach ($request->all() as $key => $value) {
                if($key != '_token'){
                    $rec = Rank::where('id',$i)->first();
                    $rec->name = $value;
                    $rec->save();
                    $i++;
                }
            }
            return redirect()->route('admin.configSettingView')->with([
                'message' => 'Changed'
            ]);
        }
    }
}
