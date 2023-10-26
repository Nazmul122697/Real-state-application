<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        $user_info = Auth::user();

        // ASSIGN SESSION VALUE OF AUTHENTICATED USER
        session(['user.is_super_admin' => $user_info->is_super_admin]);
        session(['user.ses_user_id' => $user_info->id]);
        session(['user.ses_email' => $user_info->email]);
        session(['user.ses_user_pk_no' => $user_info->teamUser['user_pk_no']]);
        session(['user.ses_full_name' => $user_info->teamUser['user_fullname']]);
        session(['user.ses_role_lookup_pk_no' => $user_info->role]);
        session(['user.ses_role_name' => $user_info->userRole['lookup_name']]);
        session(['user.user_type' => $user_info->user_type]);
        session(['user.is_bypass' => $user_info->teamUser['is_bypass']]);
        session(['user.bypass_date' => $user_info->teamUser['bypass_date']]);
        session(['user.ses_auto_dist' => $user_info->teamUser['auto_distribute']]);
        session(['user.ses_dist_date' => $user_info->teamUser['distribute_date']]);


    }
}
