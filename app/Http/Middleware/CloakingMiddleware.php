<?php

namespace App\Http\Middleware;

use Closure;
use App\Geo\IP;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpFoundation\Response;

class CloakingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $IP = new IP();
        if (!empty($IP->traits) && !$this->checkBot($IP) && config('app.env') === 'production') {
            return $this->kick();
        }
        return $next($request);
    }

    function checkBot(IP $IP): bool
    {
        if ($this->checkIsp($IP)) {
            return true;
        } elseif ($this->checkOrg($IP)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check whether bot or not
     * @return bool true for bot
     */
    private function checkIsp(IP $IP): bool
    {
        return $this->str_contains($IP->traits->isp, 'google');
    }

    function str_contains(string $haystack, array|string $needle): bool
    {
        $haystack = strtolower($haystack);
        $needle = is_string($needle) ? strtolower($needle) : array_map('strtolower', $needle);

        if (is_array($needle)) {
            foreach ($needle as $n) {
                if (str_contains($haystack, $n)) {
                    return true;
                }
            }
        } elseif (str_contains($haystack, $needle)) {
            return true;
        }

        return false;
    }

    private function checkOrg(IP $IP): bool
    {
        if (empty($IP->traits->organization)) {
            return false;
        }
        return $this->str_contains($IP->traits->organization, 'google');
    }

    private function kick(): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        return redirect(config('app.web'));
    }
}
