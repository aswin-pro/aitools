<?php
namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class GeneratedContent extends Model
{
    use HasFactory;

    // static statuses
    public const CODE_GENERATOR = 'code_generator';
    public const SPEECH_TO_TEXT = 'speech_to_text';
    public const TEXT_TO_SPEECH = 'text_to_speech';

    // fillable
    protected $fillable = [
        'generation_id',
        'generated_by',
        'name',
        'type',
        'lang',
        'content',
        'word_count',
        'parameters',
        'schedule',
        'bookmark',
        'status',
    ];

    // append formatted created at
    protected $appends = [
        'formatted_created_at',
    ];

    /**
     * Paginated data.
     */
    public static function dataWithPagination(
        ?string $search,
        int $perPage,
        array $with = [],
        string $scope = 'user',
        ?string $type = null,
    ): LengthAwarePaginator {
        return self::query()
            ->with($with)
            ->when($scope === 'admin', fn($query) => $query->where('id', '!=', 1))
            ->when($scope === 'user', fn($query) => $query->where('generated_by', Auth::id()))
            ->when($type, function ($query) use ($type) {
                match ($type) {
                    'content-generator' => $query->whereNotIn('type', [
                        self::CODE_GENERATOR,
                        self::SPEECH_TO_TEXT,
                        self::TEXT_TO_SPEECH,
                    ]),
                    'code-generator'    => $query->where('type', self::CODE_GENERATOR),
                    'speech-to-text'    => $query->where('type', self::SPEECH_TO_TEXT),
                    'text-to-speech'    => $query->where('type', self::TEXT_TO_SPEECH),
                    default             => null,
                };
            })
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

    // user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by', 'id')->select(['id', 'name']);
    }

    // template
    public function template(): BelongsTo
    {
        return $this->belongsTo(ContentTemplate::class, 'type', 'unique_slug')->select(['id', 'name', 'unique_slug'])->where('status', ContentTemplate::STATUS_ACTIVE);
    }
}
