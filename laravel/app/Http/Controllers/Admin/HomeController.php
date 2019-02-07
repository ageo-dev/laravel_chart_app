<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Admin;
use App\AppConfig;
use App\Libs\FundLogLib;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.home')->with([
            'headTitle' => 'Management screen  |  '.config('app.name', '').'Management screen',
            'menu' => 'home'
        ]);
    }

    public function adminList()
    {
        $pageNum = 0;
        if (isset($_GET['page'])) {
            $pageNum = $_GET['page'];
        }else{
            $pageNum = 1;
        }

        $lists = Admin::orderBy('updated_at', 'desc')->paginate($this->listPaginate());
        return view('admin.admin_list')->with([
            'headTitle' => 'Administrator list  |  '.config('app.name', '').'Management screen',
            'menu' => 'adminList',
            'lists'=>$lists,
            'pageNum' => $pageNum
            ]);
    }

    public function adminDelete(Request $request, Admin $admin)
    {
        $admin->delete();
        return redirect('admin/admin_list/?page='.$request->page_num);
    }
}
