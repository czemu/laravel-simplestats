<?php

namespace Czemu\Simplestats\Models\Counter;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    public $table = 'simplestats_counter_days';
    protected $fillable = ['counter_id', 'sum', 'day'];
}
