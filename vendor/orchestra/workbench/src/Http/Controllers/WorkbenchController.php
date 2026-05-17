<?php

namespace Orchestra\Workbench\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Orchestra\Workbench\Workbench;

class WorkbenchController extends Controller
{
    /**
     * Start page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function start(Request $request)
    {
        $workbench = Workbench::config();

        if (\is_null($workbench['user'])) {
            return $this->logout($workbench['guard']);
        }

        $request->session()->flush();

        return $this->login((string) $workbench['user'], $workbench['guard']);
    }

    /**
     * Retrieve the authenticated user identifier and class name.
     *
     * @param  string|null  $guard
     * @return array<string, mixed>
     *
     * @phpstan-return array{id?: string|int, className?: string}
     */
    public function user($guard = null)
    {
        $user = Auth::guard($guard)->user();

        if (! $user) {
            return [];
        }

        return [
            'id' => $user->getAuthIdentifier(),
            'className' => $user::class,
        ];
    }

    /**
     * Login using the given user ID / email.
     *
     * @param  string  $userId
     * @param  string|null  $guard
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login($userId, $guard = null)
    {
        $guard = $guard ?: config('auth.defaults.guard');

        /** @var \Illuminate\Contracts\Auth\UserProvider $provider */
        $provider = Auth::guard($guard)->getProvider(); // @phpstan-ignore method.notFound

        $user = Str::contains($userId, '@')
            ? $provider->retrieveByCredentials(['email' => $userId])
            : $provider->retrieveById($userId);

        /** @phpstan-ignore method.notFound */
        Auth::guard($guard)->login($user);

        /** @phpstan-ignore return.type */
        return redirect(Workbench::config('start'));
    }

    /**
     * Log the user out of the application.
     *
     * @param  string|null  $guard
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout($guard = null)
    {
        $guard = $guard ?: config('auth.defaults.guard');

        /** @phpstan-ignore method.notFound */
        Auth::guard($guard)->logout();

        Session::forget('password_hash_'.$guard);

        /** @phpstan-ignore return.type */
        return redirect(Workbench::config('start'));
    }
}
