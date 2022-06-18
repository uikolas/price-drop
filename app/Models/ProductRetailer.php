<?php

namespace App\Models;

use App\RetailerType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductRetailer
 *
 * @property int $id
 * @property string $url
 * @property string|null $price
 * @property RetailerType::class $type
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @method static \Database\Factories\ProductRetailerFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRetailer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRetailer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRetailer query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRetailer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRetailer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRetailer wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRetailer whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRetailer whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRetailer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRetailer whereUrl($value)
 * @mixin \Eloquent
 */
class ProductRetailer extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => RetailerType::class,
    ];

    protected $fillable = [
        'url',
        'type',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function hasType(RetailerType $retailerType): bool
    {
        return $this->type === $retailerType;
    }

    public function hasLowerPriceThan(?ProductRetailer $bestProductRetailer): bool
    {
        if ($bestProductRetailer === null) {
            return true;
        }

        if ($this->price === null) {
            return false;
        }

        return \bccomp($this->price, $bestProductRetailer->price, 2) === -1;
    }
}
