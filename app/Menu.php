<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //
    protected $fillable = [
        'mp_id', 'type', 'name','key', 'url', 'order', 'parent'
    ];
    protected $table = 'mp_menu';
    public $timestamps = false;
}
