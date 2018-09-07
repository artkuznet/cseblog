<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 't_news';


    public static function Get($slug = null)
    {
        return is_null($slug) ? self::all() : self::where('slug','=',$slug)->get();
    }
}
