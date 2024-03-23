<?php

namespace App\Models;

use App\Casts\PriceCast;
use App\Price;
use App\RetailerType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ProductRetailer
 *
 * @property int $id
 * @property string $url
 * @property Price|null $price
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
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRetailer whereCurrency($value)
 * @property string|null $image
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRetailer whereImage($value)
 * @property \Illuminate\Support\Carbon|null $price_updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRetailer wherePriceUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductRetailer extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'type',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => RetailerType::class,
            'price' => PriceCast::class,
            'price_updated_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function hasType(RetailerType $retailerType): bool
    {
        return $this->type === $retailerType;
    }

    public function updatePrice(Price $price): void
    {
        $this->price = $price;
        $this->price_updated_at = now();
    }

    public function resetPrice(): void
    {
        $this->price = null;
        $this->price_updated_at = null;
    }
}
