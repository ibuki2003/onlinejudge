<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lang extends Model
{
    protected $fillable = ['id', 'name', 'extension', 'compile', 'exec'];
    protected $keyType = 'string';

    public static function get_map(){
        return Lang::all()->pluck('name', 'id');
    }
}
