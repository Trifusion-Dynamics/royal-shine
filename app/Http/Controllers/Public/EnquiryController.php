<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Product;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnquiryController extends Controller
{
    public function create(Request $request, SettingsService $settings)
    {
        $product = null;
        if ($request->filled('product')) {
            $product = Product::find($request->get('product'));
        }

        return view('pages.enquiry', compact('product', 'settings'));
    }

    public function store(Request $request, SettingsService $settings)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'product_id' => ['nullable', 'exists:products,id'],
            'product_reference' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $enquiry = Enquiry::create([
            'product_id' => $request->get('product_id'),
            'name' => $request->get('name'),
            'mobile' => $request->get('mobile'),
            'email' => $request->get('email'),
            'product_reference' => $request->get('product_reference'),
            'message' => $request->get('message'),
            'status' => 'new',
        ]);

        return redirect()->route('enquiry.success')
            ->with('success', 'Your enquiry has been submitted. We will contact you soon.');
    }

    public function success(SettingsService $settings)
    {
        return view('pages.enquiry-success', compact('settings'));
    }
}
