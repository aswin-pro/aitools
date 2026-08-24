<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Blog extends Model
{
    protected $table = 'blogs';

protected $appends = [
    'formatted_created_at',
];

protected function formattedCreatedAt(): Attribute
{
    return Attribute::make(
        get: fn() => formatDateForUser($this->created_at),
    );
}
    public function blogCategory()
    {
        return $this->belongsTo(BlogCategory::class, 'category', 'blog_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}
