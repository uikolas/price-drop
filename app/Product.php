<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Money\Currency;
use Money\Money;

class Product extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productRetailers()
    {
        return $this->hasMany(ProductRetailer::class);
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user()->getResults();
    }

    /**
     * @return ProductRetailer[]|Collection
     */
    public function getProductRetailers()
    {
        return $this->productRetailers()->getResults();
    }

    /**
     * @return ProductRetailer|null
     */
    public function getBestProductRetailer()
    {
        $notNullProductRetailers = $this->getProductRetailers()->reject(function (ProductRetailer $productRetailer) {
            return null === $productRetailer->getPrice();
        });

        $sortedProductRetailers = $notNullProductRetailers->sortBy('price');

        return $sortedProductRetailers->first();
    }

}
