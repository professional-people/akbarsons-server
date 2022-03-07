<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $guarded = [];

    public function getMedicine()
    {
        return $this->hasOne(Medicine::class, 'id', 'medicine_id');
    }

    public function getAlertDuration()
    {
        return $this->hasOne(AlertDuration::class, 'id', 'alert_duration_id');
    }

    public function getStockType()
    {
        return $this->hasOne(StockType::class, 'id', 'stock_type');
    }

    public function getQuantity()
    {
        return $this->hasMany(StockDetail::class, 'stock_id', 'id')->where('status', '1');
    }

    public function getStockDetails()
    {
        return $this->hasOne(StockDetail::class, 'stock_id', 'id')->where('status', '1');
    }
}
