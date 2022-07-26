@component('mail::message')

@component('mail::panel')
# Price drop for: {{ $productRetailer->product->name }}

New best price: **{{ $productRetailer->price }}** **{{ $productRetailer->currency }}**

Retailer: **{{ $productRetailer->type->name }}**

@isset($productRetailer->image)
<img src="{{ $productRetailer->image }}" style="width:30%" alt="Image">
@endisset

@endcomponent

@component('mail::button', ['url' => route('products.show', [$productRetailer->product])])
    View product
@endcomponent

@endcomponent
