<?php

namespace Davidsneal\LaravelNovaCircleci;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class TriggerPipeline extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function __invoke()
    {
        $response = Http::post('https://circleci.com/api/v2/project/github/'.config('laravel-nova-circleci.username').'/'.config('laravel-nova-circleci.project').'/pipeline?circle-token='.config('laravel-nova-circleci.token'), config('laravel-nova-circleci.parameters'));

        return response()->json($response->json());
    }
}
