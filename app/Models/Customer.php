<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Customer
 *
 * @package App\Models
 */
class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'phone'];

    /**
     * Get the orders for the customer.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
