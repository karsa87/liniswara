<?php

namespace App\Http\Controllers\Admin;

use App\Events\UserLogin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthController extends Controller
{
    public function login()
    {
        return view('admin.auth.login');
    }

    public function submit_login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);

        try {
            $user = User::with([
                'roles' => function ($qRole) {
                    $qRole->withoutGlobalScope('exclude_developer');
                },
                'role',
            ])->whereEmail($request->email)->first();

            if (empty($user)) {
                return response()->json([
                    'message' => 'Email not found',
                ], Response::HTTP_NOT_FOUND);
            } elseif (! \Hash::check(request('password'), $user->password)) {
                return response()->json([
                    'message' => 'Password incorrect',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                Auth::login($user, false);

                if (
                    $user->can_access_marketing
                    || $user->isDeveloper()
                ) {
                    Auth::guard('marketing')->login($user, false);
                }

                event(new UserLogin($user));

                return response()->json();
            }
        } catch (Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout()
    {
        $user = auth()->user();
        if (
            $user->can_access_marketing
            || $user->isDeveloper()
        ) {
            Auth::guard('marketing')->logout();
        }
        \Session::flush();
        Auth::logout();

        return redirect()->route('auth.login');
    }
}
