<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 't_news';
    public $timestamps = false;
    protected $fillable=['slug','preview','header','content'];

    public static function Get($slug = null,$from = null,$to = null,$header = null)
    {
        $filter=[]; // фильтр по дате и заголовкам
        if(!is_null($slug)) array_push($filter,['slug','=',$slug]);
        if(!is_null($from)) array_push($filter,['created_at','>=',$from]);
        if(!is_null($to)) array_push($filter,['created_at','<=',$to]);
        if(!is_null($header)) array_push($filter,['header','like','%'.$header.'%']);
        return self::where($filter)->get();
    }
}