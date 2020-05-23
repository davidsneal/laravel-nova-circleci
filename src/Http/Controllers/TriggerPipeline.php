<?php

namespace Davidsneal\LaravelNovaCircleci\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Davidsneal\LaravelNovaCircleci\Models\Pipeline;

class TriggerPipeline extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function __invoke($notes)
    {
        $response = Http::post('https://circleci.com/api/v2/project/'.config('laravel-nova-circleci.vcs').'/'.config('laravel-nova-circleci.username').'/'.config('laravel-nova-circleci.project').'/pipeline?circle-token='.config('laravel-nova-circleci.token'), config('laravel-nova-circleci.parameters'));

        $json = $response->json();

        if ($json['number']) {
            Pipeline::create([
                'pipeline_number' => $json['number'],
                'pipeline_id' => $json['id'],
                'notes' => $notes,
                'user_id' => request()->user()->id,
                'vcs' => config('laravel-nova-circleci.vcs'),
                'username' => config('laravel-nova-circleci.username'),
                'project' => config('laravel-nova-circleci.project'),
            ]);
        }

        return response()->json($json);
    }
}
