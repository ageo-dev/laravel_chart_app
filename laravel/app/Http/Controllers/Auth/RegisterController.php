<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Libs\FundLogLib;
use App\ChargeLog;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // ここを編集する場合UserController のuserEditExecute()も編集する必要あり
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|alpha_num|min:6',
            'first_fund' => 'required|numeric|min:1|max:10000000000000000',
            'start_date' => 'required|date',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if(empty($data['memo'])){
            $data['memo'] = '';
        }
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'rank_id' => 1,
            'first_fund' => $data['first_fund'],
            'total_fund' => $data['first_fund'],
            'now_fund' => $data['first_fund'],
            'investment_count' => 1,
            'start_date' => $data['start_date'],
            'log_done_date' => $data['start_date'],
            'memo' => $data['memo'],
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($this->create($request->all())));
        $user = User::where('email',$request->email)->first();
        $chargeLog = new ChargeLog;
        $chargeLog->user_id = $user->id;
        $chargeLog->fund = $user->first_fund;
        $chargeLog->charge_datetime = $user->start_date;
        $chargeLog->save();
        FundLogLib::makeLog($user);
        return redirect('admin/user_list/desc')->with([
                            'message'=>'Has registered'
                        ]);
    }
}
