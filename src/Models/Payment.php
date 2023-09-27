<?php

namespace Vibraniuum\Pamtechoga\Models;

use Helix\Fabrick\Icon;
use Helix\Lego\Models\Contracts\Searchable;
use Helix\Lego\Models\Model as LegoModel;
use Helix\Lego\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Payment extends LegoModel implements Searchable
{

    protected $table = 'pamtechoga_payments';

    protected $appends = ['overall_balance'];

    public function getOverallBalanceAttribute()
    {
        $status = 'CONFIRMED';

        $sumOfOverallOrdersAmount = Order::where('organization_id', $this->organization_id)
            ->where('status', '<>', 'CANCELED')
            ->select(DB::raw('SUM(volume * unit_price) AS total'))
            ->first();

        $sumOfOverallPaymentsAmount = Payment::where('organization_id', $this->organization_id)
            ->where('status', $status)
            ->sum('amount');

        $totalDebtOwed = max($sumOfOverallOrdersAmount?->total - $sumOfOverallPaymentsAmount, 0);

        return $totalDebtOwed;
    }

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public function getEditRoute(): string
    {
        return route('lego.pamtechoga.payments.edit', $this);
    }

    public static function getDisplayKeyName(): string
    {
        return 'amount';
    }

    public static function searchableIcon(): string
    {
        return static::icon();
    }

    public static function searchableIndexRoute(): string
    {
        return route('lego.pamtechoga.payments.index');
    }

    public function scopeGlobalSearch($query, $value)
    {
        return $query->where('amount', 'LIKE', '%' . $value . '%');
    }

    public function searchableName(): string
    {
        return $this->amount;
    }

    public function searchableDescription(): string
    {
        return Str::limit($this->amount ?? '', 60);
    }

    public function searchableRoute(): string
    {
        return route('lego.pamtechoga.payments.edit', $this);
    }

    public function depotOrder()
    {
       return $this->belongsTo(DepotOrder::class);
    }

    public function customerOrder()
    {
       return $this->belongsTo(Order::class);
    }

    public function organization()
    {
       return $this->belongsTo(Organization::class);
    }

    public function user()
    {
       return $this->belongsTo(User::class);
    }
}
