<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 */
class Tag extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 't_tags';
    /**
     * @var string[]
     */
    protected $hidden = ['pivot']; // скрываем pivot
    /**
     * @var string[]
     */
    protected $fillable = ['name'];
}