<?php
namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GeneratedImage extends Model
{
    use HasFactory;

    // append formatted created at
    protected $appends = [
        'formatted_created_at',
    ];

    // casts
    protected $casts = [
        'result' => 'array',
    ];

    /**
     * Paginated data.
     */
    public static function dataWithPagination(
        ?string $search,
        int $perPage,
        string $scope = 'user'
    ): LengthAwarePaginator {
        return self::query()
            ->when($scope === 'admin', fn($query) => $query->where('id', '!=', 1))
            ->when($scope === 'user', fn($query) => $query->where('generated_by', Auth::id()))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    // Get data by field
    public static function getDataByField(
        string $field,
        mixed $value,
        ?int $userId = null,
        array $with = []
    ): ?self {
        return self::query()
            ->with($with)
            ->where($field, $value)
            ->when($userId, fn($query) => $query->where('generated_by', $userId))
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