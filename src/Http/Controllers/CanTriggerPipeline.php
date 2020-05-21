<?php

namespace Davidsneal\LaravelNovaCircleci\Http\Controllers;

use App\Http\Controllers\Controller;
use Davidsneal\LaravelNovaCircleci\Models\Pipeline;

class CanTriggerPipeline extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function __invoke()
    {
        $pipeline = Pipeline::orderBy('created_at', 'desc')
            ->first();

        $can = ! $pipeline || $pipeline->created_at < now()->subMinutes(config('laravel-nova-circleci.throttle'));

        if (! $can) {
            $wait = $pipeline->created_at->diffInMinutes(now()->subMinutes(config('laravel-nova-circleci.throttle')));
        }

        return response()->json([
            'can' => $can,
            'wait' => $wait ?? null,
            'throttle' => config('laravel-nova-circleci.throttle'),
        ]);
    }
}
