@component('mail::message')

@component('mail::panel')
# Price drop for: {{ $productRetailer->product->name }}

New best price: **{{ $productRetailer->price }}**

Retailer: **{{ $productRetailer->type->name }}**
@endcomponent

@component('mail::button', ['url' => route('products.show', [$productRetailer->product])])
    View product
@endcomponent

@endcomponent
