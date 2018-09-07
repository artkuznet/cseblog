<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 't_images';

    public static function GetAll()
    {
        $Images=self::all();
        foreach ($Images as $image) $image->tags;
        return $Images;
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'t_image_has_tag','image_guid','tag_id','guid','id');
    }

}
