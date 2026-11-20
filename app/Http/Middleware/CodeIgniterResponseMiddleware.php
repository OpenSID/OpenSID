<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Bridge the CodeIgniter legacy session with Laravel's session store
 * and resolve any buffered CodeIgniter output into a proper HTTP response.
 */
class CodeIgniterResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        $ciVars = $request->session()->get('__ci_vars', []);

        // Before dispatching the request we will copy the current Laravel session
        // into the global $_SESSION array so that legacy CodeIgniter controllers
        // and libraries are able to read session values in the way they expect.
        foreach ($request->session()->all() as $key => $value) {
            // Flash and temp keys marked as expired in __ci_vars are skipped here
            // since MY_Session will remove them during initialisation and they must
            // not be propagated into $_SESSION or pushed back into the store later.
            if (isset($ciVars[$key]) && ($ciVars[$key] === 'old' || (is_int($ciVars[$key]) && $ciVars[$key] < time()))) {
                continue;
            }

            $_SESSION[$key] = $value;
        }

        // We will snapshot the session ID and the set of keys present in the store
        // before dispatching so that we can detect whether the session was destroyed
        // or regenerated and accurately identify any deletions made to $_SESSION.
        $sessionIdBefore   = $request->session()->getId();
        $sessionKeysBefore = array_keys($request->session()->all());

        $response = $next($request);

        // If the session ID changed during the request the session was destroyed or
        // regenerated, such as during a logout or re-authentication flow, and we
        // must skip reconciliation entirely to avoid polluting the new session store.
        if ($request->session()->getId() !== $sessionIdBefore) {
            // Clear the superglobal $_SESSION to prevent any stale data from being pushed
            // back into the new session store, since the old session was destroyed or
            // regenerated and any keys present in $_SESSION are no longer valid.
            $_SESSION = [];

            return $this->resolveCodeIgniterResponse($request, $response);
        }

        // Here we collect every key managed exclusively by MY_Session, including
        // the internal metadata keys and any flash or temp data keys registered
        // inside __ci_vars, so they are never touched during reconciliation.
        $ciManagedKeys = array_merge(
            ['__ci_vars', '__ci_last_regenerate'],
            array_keys($request->session()->get('__ci_vars', [])),
        );

        // Keys forgotten or mutated in the store during the request (e.g. via
        // MY_Session::unset_userdata() or __set()) must be re-synced into $_SESSION
        // here, otherwise the push-back loop below would resurrect the stale value.
        $storeAfter = $request->session()->all();
        foreach ($sessionKeysBefore as $key) {
            if (in_array($key, $ciManagedKeys, true)) {
                continue;
            }

            if (! array_key_exists($key, $storeAfter)) {
                unset($_SESSION[$key]);
            } elseif (array_key_exists($key, $_SESSION ?? []) && $_SESSION[$key] !== $storeAfter[$key]) {
                $_SESSION[$key] = $storeAfter[$key];
            }
        }

        // Next we will remove any keys from the store that were unset directly on
        // $_SESSION during the request, considering only keys that were present
        // before dispatching so that flash data written via MY_Session is preserved.
        foreach ($sessionKeysBefore as $key) {
            if (in_array($key, $ciManagedKeys, true)) {
                continue;
            }

            if (! array_key_exists($key, $_SESSION ?? [])) {
                $request->session()->forget($key);
            }
        }

        // Finally we will push any additions or mutations made to $_SESSION back into
        // the store, skipping MY_Session managed keys to prevent $_SESSION from ever
        // overwriting internal metadata or flash data written during the request.
        foreach ($_SESSION ?? [] as $key => $value) {
            if (in_array($key, $ciManagedKeys, true)) {
                continue;
            }

            $request->session()->put($key, $value);
        }

        return $this->resolveCodeIgniterResponse($request, $response);
    }

    /**
     * Resolve any buffered CodeIgniter output into a proper Symfony response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function resolveCodeIgniterResponse(Request $request, SymfonyResponse $response): SymfonyResponse
    {
        // If the response was already flagged as a CodeIgniter response by a previous
        // middleware layer we can return it directly without applying any further
        // processing or transformation to the outgoing response.
        if ($response->headers->has('X-CodeIgniter')) {
            return $response;
        }

        // If CodeIgniter was not bootstrapped for this request there is nothing
        // further to resolve and we may return the original response as-is without
        // any additional processing being applied to the outgoing response.
        if (! function_exists('get_instance')) {
            return $response;
        }

        // When CodeIgniter has accumulated output in its buffer during the request,
        // such as when a controller echoes content directly instead of returning a
        // value, we will convert that buffer into a proper Symfony response.
        $ci = &get_instance();

        if (! empty($ci?->output?->get_output())) {
            return $ci->output->toResponse($request);
        }

        return $response;
    }
}
