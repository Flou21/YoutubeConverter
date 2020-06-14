<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string|null title
 * @property string|null filename
 */
class YoutubeData extends Model
{
    /**
     * @var string
     */
    protected $table = 'youtube_data';

    /**
     * @var int
     */
    public $id;

    protected $fillable = [
        'filename', 'title'
    ];
}
