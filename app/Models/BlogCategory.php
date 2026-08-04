<?php

namespace App\Models;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $table = 'blog_categories';

    public function blogs()
    {
        return $this->hasOne(Blog::class, 'category', 'blog_category_id');
    }
}
