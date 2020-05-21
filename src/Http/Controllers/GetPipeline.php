<?php

namespace Davidsneal\LaravelNovaCircleci\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Davidsneal\LaravelNovaCircleci\Models\Pipeline;
use Davidsneal\LaravelNovaCircleci\Http\Resources\PipelineResource;

class GetPipeline extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function __invoke(Pipeline $pipeline)
    {
        $response = Http::get('https://circleci.com/api/v2/project/github/'.config('laravel-nova-circleci.username').'/'.config('laravel-nova-circleci.project').'/pipeline/'.$pipeline->pipeline_number.'?circle-token='.config('laravel-nova-circleci.token'));

        PipelineResource::withoutWrapping();

        return new PipelineResource([
            'model' => $pipeline,
            'response' =>  collect($response->json()),
        ]);
    }
}
