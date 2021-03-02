<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\{
    Builder,
    Collection,
    Model
};

/**
 * @property string $header
 * @property string $content
 * @property string $slug
 * @property string $preview
 * @method static Builder where($filter)
 */
class News extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 't_news';
    /**
     * @var string[]
     */
    protected $fillable = ['slug',
        'preview',
        'header',
        'content',
    ];

    /**
     * @param string|null $slug
     * @param string|null $from
     * @param string|null $to
     * @param string|null $header
     * @return Collection
     */
    public static function get(string $slug = null, string $from = null, string $to = null, string $header = null): Collection
    {
        $filter = []; // фильтр по дате и заголовкам
        if (null !== $slug) {
            $filter[] = ['slug', '=', $slug];
        }
        if (null !== $from) {
            $filter[] = ['created_at', '>=', $from];
        }
        if (null !== $to) {
            $filter[] = ['created_at', '<=', $to];
        }
        if (null !== $header) {
            $filter[] = ['header', 'like', '%' . $header . '%'];
        }

        return self::where($filter)->get();
    }
}