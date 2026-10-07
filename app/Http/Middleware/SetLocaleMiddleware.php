<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Supported application locales.
     *
     * @var array<string>
     */
    protected array $supportedLocales = ['id', 'en'];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', $request->cookie('app_locale'));

        if (! is_string($locale) || ! in_array($locale, $this->supportedLocales, true)) {
            $locale = (string) config('app.locale', 'id');
            if (! in_array($locale, $this->supportedLocales, true)) {
                $locale = 'id';
            }
        }

        app()->setLocale($locale);
        Carbon::setLocale($locale);

        if (! session()->has('locale')) {
            session(['locale' => $locale]);
        }

        return $next($request);
    }
}
