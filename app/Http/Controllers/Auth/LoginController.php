<?php

namespace App\Http\Controllers\Auth;

use App\Dicts\UserTypeDict;
use App\Events\NewUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Login user
     *
     * @param LoginRequest $request
     * @return User
     */
    public function login(LoginRequest $request) : User
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            event(new NewUser($user = $this->create($request->all())));
        }
        //TODO: проверить права пользователя (спасибо Copilot)
        Auth::login($user);

        return $user;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data): User
    {
        $user = (new User())->forceFill([
            'name' => $data['account']['account_name'],
            'permission' => $data['proof']['signer']['permission'],
            'key' => $data['proofKey'],
            'type' => UserTypeDict::USER,
            'active' => 1,
            'auth_type' => $data['authType'],
        ]);

        $user->save();

        return $user;
    }

    /**
     * Logout to the application.
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        Auth::logout();
        return redirect()->back();
    }
}
