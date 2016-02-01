<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //
    protected $fillable = [
        'news_media_id', 'thumb_media_id', 'author','title', 'content_source_url', 'content', 'digest', 'show_cover_pic'
    ];
    protected $table = 'articles';
}
