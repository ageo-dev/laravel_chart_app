<?php

namespace App\Http\Controllers\Admin\Auth;
 
use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

use App\Role;
 
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
    protected $redirectTo = '/admin';
 
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
 
 
    public function showRegisterForm()
    {
        $roles = Role::get();
        return view('admin.auth.register')->with([
            'roles'=>$roles
        ]);
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255|unique:admins',
            'role_id' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'memo' => 'max:255',
        ]);
    }
 
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        if(empty($data['memo'])){
            $data['memo'] = '';
        }
        return Admin::create([
            'name' => $data['name'],
            'role_id' => $data['role_id'],
            'password' => bcrypt($data['password']),
            'memo' => $data['memo'],
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($this->create($request->all())));
        return redirect('admin/admin_list')->with([
                            'message'=>'Has registered'
                        ]);
    }
}