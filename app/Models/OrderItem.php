<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class OrderItem
 *
 * @package App\Models
 */
class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['order_id', 'product_id', 'qty', 'unit_price', 'line_total'];

    /**
     * Get the order that owns the item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product that owns the item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
