<?php

namespace App\Http\Controllers;

use App\Dicts\UserTypeDict;
use App\Events\NewUser;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Login user
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse //: User
    {
        /** @var User $user */
        $user = User::whereName($request->get('account')['account_name'])->first();
        if (!$user) {
            event(new NewUser($user = $this->create($request->all())));
        }

        Auth::login($user, false);
        return response()->json(['user' => $user]);
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
            'api_token' => Str::random(80),
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
