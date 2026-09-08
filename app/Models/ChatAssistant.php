<?php
namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatAssistant extends Model
{
    use HasFactory;

    // static statuses
    public const STATUS_ACTIVE  = 1;
    public const STATUS_DELETED = 0;

    // append formatted created at
    protected $appends = [
        'formatted_created_at',
    ];

    protected $fillable = [
        'chat_assistant_id',
        'chat_assistant_image',
        'chat_assistant_name',
        'chat_assistant_expert',
        'chat_assistant_message',
        'chat_created_at',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * Paginated data.
     */
    public static function dataWithPagination(
        ?string $search,
        int $perPage
    ): LengthAwarePaginator {
        return self::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('chat_assistant_name', 'like', "%{$search}%")
                        ->orWhere('chat_assistant_expert', 'like', "%{$search}%");
                });
            })
            ->where('status', self::STATUS_ACTIVE)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    // get data by field
    public static function getDataByField(
        string $field,
        mixed $value,
    ): ?self {
        return self::query()
            ->where($field, $value)
            ->where('status', self::STATUS_ACTIVE)
            ->first();
    }

    // formatted created at
    protected function formattedCreatedAt(): Attribute
    {
        return Attribute::make(
            get: fn() => formatDateOnlyForUser($this->created_at),
        );
    }
}
