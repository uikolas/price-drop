<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessProductRetailer;
use App\Models\ProductRetailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TriggerProductRetailerUpdate extends Controller
{
    public function __invoke(Request $request, ProductRetailer $retailer): RedirectResponse
    {
        $this->authorize('view', $retailer);

        $this->dispatch(new ProcessProductRetailer($retailer));

        return redirect()
            ->route('products.show', [$retailer->product])
            ->with('success', 'Product retailer update triggered');
    }
}
