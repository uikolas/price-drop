<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Money\Currency;
use Money\Money;

class ProductRetailer extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'url'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }

    /**
     * @return Money|null
     */
    public function getPrice()
    {
        return $this->attributes['price'] ? new Money($this->attributes['price'], new Currency('EUR')) : null;
    }

    /**
     * @return Retailer
     */
    public function getRetailer()
    {
        return $this->retailer()->getResults();
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product()->getResults();
    }
}
