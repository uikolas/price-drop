@component('mail::message')

@component('mail::panel')
# Price drop for: {{ $productRetailer->getProduct()->name }}

New best price: **@money($productRetailer->getPrice())**
Retailer: **{{ $productRetailer->getRetailer()->name }}**
@endcomponent

@component('mail::button', ['url' => route('products.show', [$productRetailer->getProduct()])])
View product
@endcomponent

Thanks,<br>
{{ config('app.name') }}

@endcomponent