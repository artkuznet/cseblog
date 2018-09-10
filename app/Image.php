<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 't_images';
    public $timestamps = false;
    protected $primaryKey = 'guid';
    protected $casts = ['guid' => 'uuid']; // указываем тип данных поля guid
    protected $fillable=['guid','img','description'];

    public static function Get($guid=null,$tags=null)
    {
        $filter=[];
        if(!is_null($guid)) array_push($filter,['guid','=',$guid]); // если предан guid, добавляем условие в фильтр
        $Images = is_null($tags) ? // если запрос без тегов, то возвращаем все подряд, иначе фильтруем
            self::where($filter)->get() :
            self::where($filter)->whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('name', $tags);
            })->get();
        foreach ($Images as $image) $image->tags; // не забываем вернуть и теги
        return $Images;
    }

    public function tags()
    {
        // связь many to many таблиц t_images и t_tags
        return $this->belongsToMany(Tag::class,'t_image_has_tag','image_guid','tag_id','guid','id');
    }
}
