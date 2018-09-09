<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 't_news';
    public $timestamps = false;
    protected $fillable=['slug','preview','header','content'];

    public static function Get($slug = null)
    {
        return is_null($slug) ? self::all() : self::where('slug','=',$slug)->get();
    }
}
