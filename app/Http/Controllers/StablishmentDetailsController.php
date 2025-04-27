<?php

namespace App\Http\Controllers;

use App\Models\StablishmentDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StablishmentDetailsController extends Controller
{
    // Show the form to edit stablishment details
    public function edit()
    {
        // Check access permissions
        if (session('employee_role') != 1) {
            abort(403, 'Acceso no autorizado');
        }

        $user = Auth::user();

        if ($user->stablishmentDetails) {
            $stablishmentDetails = $user->stablishmentDetails;
        } else {
            $stablishmentDetails = new StablishmentDetails();
            $stablishmentDetails->commercial_name = $user->name;
            $stablishmentDetails->email = $user->email;
        }

        return view('stablishment_details.edit', compact('stablishmentDetails'));
    }

    // Store or update stablishment details
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validated = $request->validate([
            'commercial_name' => 'nullable|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'cif' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        // If the user has a stablishment details record, update it or create a new one
        if ($user->stablishmentDetails) {
            $user->stablishmentDetails->update($validated);
        } else {
            $validated['user_id'] = $user->id;
            StablishmentDetails::create($validated);
        }

        return redirect()->route('stablishment_details.edit')
            ->with('success', 'Datos del establecimiento actualizados correctamente');
    }
}
