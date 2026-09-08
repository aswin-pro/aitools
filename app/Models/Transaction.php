<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    use HasFactory;

    // static statuses
    static $ACTIVE = 1;

    // static statuses
    public const STATUS_ACTIVE  = 1;

    public const PAYMENT_PENDING = 'PENDING';
    public const PAYMENT_SUCCESS = 'SUCCESS';
    public const PAYMENT_FAILED  = 'FAILED';
    public const PAYMENT_CANCELLED  = 'CANCELLED';


    // append formatted created at
    protected $appends = [
        'formatted_created_at',
        'formatted_amount'
    ];


   /**
     * Paginated data.
     */
    public static function dataWithPagination(
        ?string $search,
        int $perPage,
        array $with,
        string $scope = 'user',
        string $transactionType = 'all'
    ): LengthAwarePaginator {
        return self::query()
            ->with($with)
            ->when($scope === 'admin', fn($query) => $query->where('id', '!=', 1))
            ->when($scope === 'user', fn($query) => $query->where('user_id', Auth::id()))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('transaction_id', 'like', "%{$search}%")
                        ->orWhere('payment_gateway_name', 'like', "%{$search}%");
                });
            })
            ->when(
                $scope == 'admin' && $transactionType == 'online',
                fn($query) => $query->where('payment_gateway_name', '!=', 'Bank Transfer')
            )
            ->when(
                $scope == 'admin' && $transactionType == 'offline',
                fn($query) => $query->where('payment_gateway_name', 'Bank Transfer')
            )
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    protected function formattedCreatedAt(): Attribute
    {
        return Attribute::make(
            get: fn() => formatDateOnlyForUser($this->created_at),
        );
    }

    protected function formattedAmount(): Attribute
    {
        return Attribute::make(
            get: fn() => formatCurrency($this->transaction_amount, $this->transaction_currency),
        );
    }

    // user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->select(['id', 'name']);
    }

    // plan
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id')->where('status', '!=', Plan::$DELETED)->select(['id', 'name']);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'transaction_currency', 'iso_code')
            ->select(['iso_code', 'symbol']);
    }
}
