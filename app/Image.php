<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $guid
 * @property string $img
 * @property string $description
 */
class Image extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 't_images';
    /**
     * @var string
     */
    protected $primaryKey = 'guid';
    /**
     * @var array
     */
    protected $casts = ['guid' => 'uuid']; // указываем тип данных поля guid
    /**
     * @var string[]
     */
    protected $fillable = [
        'guid',
        'img',
        'description',
    ];

    /**
     * @param string|null $guid
     * @param iterable|null $tags
     * @return Collection
     */
    public static function get(string $guid = null, iterable $tags = null): Collection
    {
        $filter = [];
        if (null !== $guid) {
            $filter[] = ['guid', '=', $guid]; // если предан guid, добавляем условие в фильтр
        }
        $images = null === $tags // если запрос без тегов, то возвращаем все подряд, иначе фильтруем
            ? self::where($filter)->get()
            : self::where($filter)->whereHas('tags', function (Builder $query) use ($tags) {
                $query->whereIn('name', $tags);
            })->get();
        foreach ($images as $image) {
            $image->tags; // не забываем вернуть и теги
        }

        return $images;
    }

    /**
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        // связь many to many таблиц t_images и t_tags
        return $this->belongsToMany(Tag::class, 't_image_has_tag', 'image_guid', 'tag_id', 'guid', 'id');
    }
}
