<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressesController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->addresses()->orderByDesc('is_primary')->get();
        return response()->json(['addresses' => $addresses]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $count = $user->addresses()->count();
        if ($count >= 3) {
            return response()->json(['error' => 'Maximum 3 alamat diperbolehkan.'], 422);
        }

        $data = $request->validate([
            'label' => 'nullable|string|max:100',
            'address' => 'required|string',
            'city' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'phone' => 'nullable|string',
            'is_primary' => 'sometimes|boolean',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        // If setting primary, unset others
        if (!empty($data['is_primary'])) {
            $user->addresses()->update(['is_primary' => false]);
        }

        $address = $user->addresses()->create($data);

        if ($request->wantsJson()) {
            return response()->json(['address' => $address]);
        }

        return redirect()->back()->with('status', 'address-added');
    }

    public function destroy(Address $address)
    {
        $user = Auth::user();
        if ($address->user_id !== $user->id) {
            abort(403);
        }

        $address->delete();
        if ($request->wantsJson()) {
            return response()->json(['deleted' => true]);
        }

        return redirect()->back()->with('status', 'address-deleted');
    }

    public function update(Request $request, Address $address)
    {
        $user = Auth::user();
        if ($address->user_id !== $user->id) {
            abort(403);
        }

        $data = $request->validate([
            'label' => 'nullable|string|max:100',
            'address' => 'required|string',
            'city' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'phone' => 'nullable|string',
            'is_primary' => 'sometimes|boolean',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        if (!empty($data['is_primary'])) {
            $user->addresses()->update(['is_primary' => false]);
        }

        $address->update($data);

        if ($request->wantsJson()) {
            return response()->json(['address' => $address]);
        }

        return redirect()->back()->with('status', 'address-updated');
    }
}
