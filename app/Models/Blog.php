<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BlogCategory;

class Blog extends Model
{
    protected $table = 'blogs';

    public function blogCategory()
    {
        return $this->belongsTo(BlogCategory::class, 'category', 'blog_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}
