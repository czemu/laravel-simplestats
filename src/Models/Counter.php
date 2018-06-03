<?php

namespace Czemu\Simplestats\Models;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    public $table = 'simplestats_counters';
    protected $fillable = ['name', 'item_id', 'sum'];
}
