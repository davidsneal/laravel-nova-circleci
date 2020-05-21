<?php

namespace Davidsneal\LaravelNovaCircleci\Models;

use Illuminate\Database\Eloquent\Model;

class Pipeline extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'circleci_pipelines';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $with = ['user'];

    /**
     * The user that triggered the pipeline.
     */
    public function user()
    {
        return $this->belongsTo(config('laravel-nova-circleci.user_model'), 'user_id', 'id');
    }
}
