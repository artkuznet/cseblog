<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 't_images';
    public $timestamps = false;
    protected $primaryKey = 'guid';
    protected $casts = ['guid' => 'uuid'];
    protected $fillable=['guid','img','description'];

    public static function Get($guid=null)
    {
        $Images = is_null($guid) ? self::all() : self::where('guid','=',$guid)->get();
        foreach ($Images as $image) $image->tags;
        return $Images;
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'t_image_has_tag','image_guid','tag_id','guid','id');
    }

}
