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
        $user = Auth::user();
        if (!$user) {
            event(new NewUser($user = $this->create($request->all())));
        }

        //TODO: проверить права пользователя (спасибо Copilot)
        Auth::login($user, false);
        $user->auth = true;
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
