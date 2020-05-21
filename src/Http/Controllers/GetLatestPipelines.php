<?php

namespace Davidsneal\LaravelNovaCircleci\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Davidsneal\LaravelNovaCircleci\Models\Pipeline;
use Davidsneal\LaravelNovaCircleci\Http\Resources\PipelineResource;

class GetLatestPipelines extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function __invoke()
    {
        $pipelines = Pipeline::orderBy('created_at', 'desc')
            ->limit(config('laravel-nova-circleci.latest_limit'))
            ->get();

        $pipelines = $pipelines->map(function($pipeline) {
            return ['model' => $pipeline];
        });

        PipelineResource::withoutWrapping();

        return PipelineResource::collection($pipelines);
    }
}
