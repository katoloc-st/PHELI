<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CompanyProfileController extends Controller
{
    /**
     * Display the company's profile.
     */
    public function show()
    {
        $user = Auth::user();
        return view('company-profile', compact('user'));
    }

    /**
     * Update the company's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'company_address' => ['nullable', 'string', 'max:500'],
            'company_phone' => ['nullable', 'string', 'max:20'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'business_license' => ['nullable', 'string', 'max:50'],
            'tax_code' => ['nullable', 'string', 'max:50'],
            'company_description' => ['nullable', 'string', 'max:1000'],
            'company_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'remove_logo' => ['nullable', 'string', 'in:0,1'],
        ]);

        // Handle logo removal
        if ($request->filled('remove_logo') && $request->remove_logo === '1') {
            // Delete old logo if exists
            if ($user->company_logo && Storage::disk('public')->exists($user->company_logo)) {
                Storage::disk('public')->delete($user->company_logo);
            }
            $user->company_logo = null;
        }
        // Handle logo upload
        elseif ($request->hasFile('company_logo')) {
            // Delete old logo if exists
            if ($user->company_logo && Storage::disk('public')->exists($user->company_logo)) {
                Storage::disk('public')->delete($user->company_logo);
            }

            // Store new logo
            $logoPath = $request->file('company_logo')->store('company_logos', 'public');
            $user->company_logo = $logoPath;
        }

        // Update company information
        $user->company_name = $request->company_name;
        $user->company_address = $request->company_address;
        $user->company_phone = $request->company_phone;
        $user->company_email = $request->company_email;
        $user->business_license = $request->business_license;
        $user->tax_code = $request->tax_code;
        $user->company_description = $request->company_description;

        $user->save();

        return back()->with('success', 'Thông tin công ty đã được cập nhật thành công!');
    }
}
