<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingCountry;
use App\Models\ShippingState;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        $countries = ShippingCountry::withCount('states')->orderBy('name')->get();
        return view('admin.shipping.index', compact('countries'));
    }

    public function showStates(ShippingCountry $country)
    {
        $states = $country->states()->orderBy('name')->get();
        return view('admin.shipping.states', compact('country', 'states'));
    }

    // Country methods
    public function storeCountry(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:2|unique:shipping_countries',
            'default_shipping_fee' => 'required|numeric|min:0',
            'is_international' => 'nullable|boolean'
        ]);

        $validated['is_international'] = $request->boolean('is_international', false);

        ShippingCountry::create($validated);

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Shipping country added successfully.');
    }

    public function updateCountry(Request $request, ShippingCountry $country)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:2|unique:shipping_countries,code,' . $country->id,
            'default_shipping_fee' => 'required|numeric|min:0',
            'is_international' => 'nullable|boolean'
        ]);

        $validated['is_international'] = $request->boolean('is_international', false);

        $country->update($validated);

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Shipping country updated successfully.');
    }

    public function destroyCountry(ShippingCountry $country)
    {
        $country->delete();

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Shipping country deleted successfully.');
    }

    public function toggleCountry(ShippingCountry $country)
    {
        $country->update(['is_active' => !$country->is_active]);

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Country status updated successfully.');
    }

    // State methods
    public function storeState(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10',
            'shipping_fee' => 'required|numeric|min:0',
            'shipping_country_id' => 'required|exists:shipping_countries,id'
        ]);

        ShippingState::create($validated);

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Shipping state added successfully.');
    }

    public function updateState(Request $request, ShippingState $state)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10',
            'shipping_fee' => 'required|numeric|min:0',
            'shipping_country_id' => 'required|exists:shipping_countries,id'
        ]);

        $state->update($validated);

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Shipping state updated successfully.');
    }

    public function destroyState(ShippingState $state)
    {
        $state->delete();

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Shipping state deleted successfully.');
    }

    public function toggleState(ShippingState $state)
    {
        $state->update(['is_active' => !$state->is_active]);

        return redirect()->route('admin.shipping.index')
            ->with('success', 'State status updated successfully.');
    }

    // API methods for dynamic loading
    public function getStatesByCountry($countryId)
    {
        $states = ShippingState::where('shipping_country_id', $countryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'shipping_fee']);

        return response()->json($states);
    }

    public function getShippingFee($countryId, $stateId = null)
    {
        $country = ShippingCountry::findOrFail($countryId);
        $fee = $country->getShippingFee($stateId);

        return response()->json([
            'fee' => $fee,
            'currency' => get_currency_symbol()
        ]);
    }
} 