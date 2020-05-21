<?php

namespace Davidsneal\LaravelNovaCircleci\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PipelineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'pipeline_id' => $this['model']->pipeline_id,
            'number' => $this['model']->pipeline_number,
            'user' => $this['model']->user->name,
            'notes' => $this['model']->notes,
            'state' => $this['response']['state'] ?? 'loading',
            'createdAt' => $this['model']->created_at,
        ];
    }
}
