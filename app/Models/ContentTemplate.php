<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class ContentTemplate extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 1;

    protected $fillable = [
        'status' => 'boolean'
    ];

    /**
     * Get all active templates.
     */
    public static function getData(array $with = []): Collection
    {
        return self::query()
            ->with($with)
            ->where('status', self::STATUS_ACTIVE)
            ->select([
                'id',
                'name',
                'category_id',
                'unique_slug',
                'description',
            ])
            ->get();
    }

    /**
     * Get a single active template by field.
     */
    public static function getDataByField(
        string $field,
        mixed $value,
        array $with = []
    ): ?self {
        return self::query()
            ->with($with)
            ->where($field, $value)
            ->where('status', self::STATUS_ACTIVE)
            ->first();
    }


    public function category(): BelongsTo
    {
        return $this->belongsTo(ContentTemplateCategory::class, 'category_id')
            ->select(['id', 'category_name']);
    }

 
    public function fields(): HasMany
    {
        return $this->hasMany(ContentTemplateField::class, 'template_id');
    }
}
