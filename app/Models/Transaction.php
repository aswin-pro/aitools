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


    // append formatted created at
    protected $appends = [
        'formatted_created_at',
        'formatted_amount'
    ];


    public static function dataWithPagination(
        ?string $search,
        int $perPage,
        array $with,
        string $for,
        string $transactionType,
    ): LengthAwarePaginator {
        return self::query()
            ->with($with)
            ->when($for == 'admin', fn($query) => $query->where('id', '!=', 1))
            ->when($for == 'user', fn($query) => $query->where('user_id', Auth::id()))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('transaction_id', 'like', "%{$search}%")
                        ->orWhere('payment_gateway_name', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })

            //online - offline -> Admin 
            ->when(
                $for == 'admin' && $transactionType == 'online',
                fn($query) => $query->where('payment_gateway_name', '!=', 'Bank Transfer')
            )
            ->when(
                $for == 'admin' && $transactionType == 'offline',
                fn($query) => $query->where('payment_gateway_name', 'Bank Transfer')
            )

            ->where('status', self::$ACTIVE)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }



    protected function formattedCreatedAt(): Attribute
    {
        return Attribute::make(
            get: fn() => formatDateForUser($this->created_at),
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
