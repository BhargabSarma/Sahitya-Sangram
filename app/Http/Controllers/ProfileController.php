<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the authenticated user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user();
        $addresses = $user->addresses;
        $defaultAddress = $user->defaultAddress;

        return view('profile.show', compact('user', 'addresses', 'defaultAddress'));
    }


    public function index(Request $request)
    {
        $user = Auth::user();
        $addresses = $user->addresses;
        $defaultAddress = $user->defaultAddress;

        return view('profile.show', compact('addresses', 'defaultAddress'));
    }

    public function create()
    {
        return view('profile.addresses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'full_name'   => 'required|string|max:255',
            'type'        => 'required|string|max:50',
            'street_address' => 'required|string|max:255',
            'city'        => 'required|string|max:100',
            'state'       => 'required|string|max:100',
            'zip'         => 'required|string|max:20',
            'country'     => 'required|string|max:100',
            'phone'       => 'required|string|max:20',
            'is_default'  => 'nullable|boolean',
        ]);

        $user = $request->user();

        // If setting as default, reset others
        $isDefault = $request->input('is_default', false);
        if ($isDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            'name'           => $request->name,
            'full_name'      => $request->full_name,
            'type'           => $request->type,
            'street_address' => $request->street_address,
            'city'           => $request->city,
            'state'          => $request->state,
            'zip'            => $request->zip,
            'country'        => $request->country,
            'phone'          => $request->phone,
            'is_default'     => $isDefault,
        ]);

        return redirect()->route('profile.addresses.index')->with('success', 'Address added!');
    }

    public function setDefault(Request $request, Address $address)
    {
        $user = $request->user();

        if ($address->user_id !== $user->id) {
            abort(403);
        }

        $user->addresses()->update(['is_default' => false]);
        $address->is_default = true;
        $address->save();

        return back()->with('success', 'Default address set!');
    }

    public function edit(Address $address)
    {
        $user = Auth::user();
        if ($address->user_id !== $user->id) {
            abort(403);
        }
        return view('profile.addresses.edit', compact('address'));
    }

    public function destroy(Address $address)
    {
        $user = Auth::user();
        if ($address->user_id !== $user->id) {
            abort(403);
        }
        $address->delete();
        return back()->with('success', 'Address deleted!');
    }
}
