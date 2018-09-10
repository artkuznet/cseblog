<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 't_tags';
    protected $hidden = ['pivot'];
    protected $fillable = ['name'];
    public $timestamps = false;
}