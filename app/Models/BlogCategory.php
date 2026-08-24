<?php

namespace App\Models;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $table = 'blog_categories';

    protected $appends = [
        'formatted_created_at',
    ];

    protected function formattedCreatedAt(): Attribute
    {
        return Attribute::make(
            get: fn() => formatDateForUser($this->created_at),
        );
    }

    public function blogs()
    {
        return $this->hasOne(Blog::class, 'category', 'blog_category_id');
    }
}
