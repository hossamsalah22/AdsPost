<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $with = ['media'];
    protected $appends = ['image'];

    public function getImageAttribute() {
        return $this->getFirstMediaUrl('posts');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
