<?php

namespace Vibraniuum\Pamtechoga\Models;

use Helix\Fabrick\Icon;
use Helix\Lego\Models\Contracts\Searchable;
use Helix\Lego\Models\Model as LegoModel;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\Cast\Double;

class Order extends LegoModel implements Searchable
{

    protected $table = 'pamtechoga_customer_orders';

    protected $casts = [
        "made_down_payment" => "boolean"
    ];

    protected $appends = ['total_payment', 'balance'];

    public function getTotalPaymentAttribute()
    {
        $totalPayment = Payment::where('customer_order_id', $this->id)->where('status', 'CONFIRMED')->sum('amount');
        return $totalPayment;
    }

    public function getBalanceAttribute()
    {
        $orderAmount = (int) $this->volume * $this->unit_price;
        $totalPayment = Payment::where('customer_order_id', $this->id)->where('status', 'CONFIRMED')->sum('amount');
        return $orderAmount - $totalPayment;
    }

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public function getEditRoute(): string
    {
        return route('lego.pamtechoga.orders.edit', $this);
    }

    public static function getDisplayKeyName(): string
    {
        return 'volume';
    }

    public static function searchableIcon(): string
    {
        return static::icon();
    }

    public static function searchableIndexRoute(): string
    {
        return route('lego.pamtechoga.orders.index');
    }

    public function scopeGlobalSearch($query, $value)
    {
        return $query->where('volume', 'LIKE', '%' . $value . '%');
    }

    public function searchableName(): string
    {
        return $this->volume;
    }

    public function searchableDescription(): string
    {
        return Str::limit($this->volume ?? '', 60);
    }

    public function searchableRoute(): string
    {
        return route('lego.pamtechoga.orders.edit', $this);
    }

    public function product()
    {
       return $this->belongsTo(Product::class);
    }

    public function organization()
    {
       return $this->belongsTo(Organization::class);
    }

    public function branch()
    {
       return $this->belongsTo(Branch::class);
    }

    public function driver()
    {
       return $this->belongsTo(Driver::class);
    }

    public function orderDebt()
    {
        return $this->hasOne(OrderDebt::class);
    }

    public function payments()
    {
       return $this->hasMany(Payment::class, 'customer_order_id', 'id')->where('status', 'CONFIRMED');
    }
}
