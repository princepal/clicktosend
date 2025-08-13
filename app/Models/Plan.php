<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_name',
        'price',
        'currency',
        'sale_price',
        'frequency',
        'plan_id',
        'short_description',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    /**
     * Get the formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Get the formatted sale price
     */
    public function getFormattedSalePriceAttribute()
    {
        if ($this->sale_price) {
            return '$' . number_format($this->sale_price, 2);
        }
        return null;
    }

    /**
     * Check if plan is on sale
     */
    public function getIsOnSaleAttribute()
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->price;
    }

    /**
     * Get the discount percentage
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->is_on_sale) {
            $discount = (($this->price - $this->sale_price) / $this->price) * 100;
            return round($discount, 0);
        }
        return 0;
    }

    public function userplans()
    {
        return $this->hasMany(UserPlan::class);
    }
}
