<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $session = $request->hasSession() ? $request->session() : null;
        $flash = $session ? $session->get('flash') : [];
        return array_merge(parent::share($request), [
            'flash' => [
                'qrcode' => $flash['qrcode'] ?? null,
                'message' => fn() => $request->session()->get('success') ??
                    $request->session()->get('error') ??
                    $request->session()->get('info') ??
                    $request->session()->get('warning'),
                'type' => fn() => match (true) {
                    $request->session()->has('error') => 'error',
                    $request->session()->has('warning') => 'warning',
                    $request->session()->has('info') => 'info',
                    default => 'success',
                },
                'display' => fn() => $request->session()->get('display'),
            ],
            'auth' => [
                'user' => fn() => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'role' => $request->user()->role,
                    'avatar' => $request->user()->avatar,
                ] : null,
                'is_impersonating' => $request->session()->has('impersonator_user_id'),
                'impersonator_name' => fn() => $request->session()->has('impersonator_user_id')
                    ? \App\Models\User::find($request->session()->get('impersonator_user_id'))?->name
                    : null,
            ],
            // Mantendo o Ziggy ativo para as rotas
            'ziggy' => fn() => [
                ...(new \Tighten\Ziggy\Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ]);
    }
}
