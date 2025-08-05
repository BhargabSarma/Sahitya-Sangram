<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class DeliveryAgentController extends Controller
{
    public function showCourierPartners()
    {

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.shiprocket.token'),
            'Content-Type' => 'application/json',
        ])->get('https://apiv2.shiprocket.in/v1/external/courier/serviceability/', [
            'pickup_postcode' => '781021', // origin pincode
            'delivery_postcode' => '781019', // destination pincode
            'cod' => 0,
            'weight' => 1
        ]);
        $partners = $response->json()['data']['available_courier_companies'] ?? [];
        $default = DB::table('default_courier_partner')->first();
        return view('admin.courier_partners', compact('partners', 'default'));
    }

    public function setDefaultCourierPartner(Request $request)
    {
        $request->validate([
            'courier_company_id' => 'required',
            'courier_name' => 'required',
            'shipping_price' => 'required|numeric'
        ]);
        DB::table('default_courier_partner')->truncate();
        DB::table('default_courier_partner')->insert([
            'courier_company_id' => $request->courier_company_id,
            'courier_name' => $request->courier_name,
            'shipping_price' => $request->shipping_price,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return redirect()->back()->with('success', 'Default courier partner set!');
    }
}
