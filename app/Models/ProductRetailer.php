<?php

namespace App\Models;

use App\RetailerType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @property string|null $currency
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRetailer whereCurrency($value)
 * @property string|null $image
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRetailer whereImage($value)
 */
class ProductRetailer extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => RetailerType::class,
        'price' => 'decimal:2',
    ];

    protected $fillable = [
        'url',
        'type',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function hasType(RetailerType $retailerType): bool
    {
        return $this->type === $retailerType;
    }
}
