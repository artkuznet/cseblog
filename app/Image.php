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

    public static function Get($guid=null,$tags=null)
    {
        $filter=[];
        if(!is_null($guid)) array_push($filter,['guid','=',$guid]);
        $Images = is_null($tags) ?
            self::where($filter)->get() :
            self::where($filter)->whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('name', $tags);
            })->get();
        foreach ($Images as $image) $image->tags;
        return $Images;
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'t_image_has_tag','image_guid','tag_id','guid','id');
    }
}
