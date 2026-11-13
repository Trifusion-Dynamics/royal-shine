<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnquiryRequest;
use App\Models\Enquiry;
use App\Models\Product;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Log;
use App\Mail\EnquiryReceived;
use Illuminate\Support\Facades\Mail;

class EnquiryController extends Controller
{
    public function create(\Illuminate\Http\Request $request, SettingsService $settings)
    {
        $product = null;
        if ($request->filled('product')) {
            $product = Product::where('status', 'published')->find($request->get('product'));
        }

        return view('pages.enquiry', compact('product', 'settings'));
    }

    public function store(EnquiryRequest $request, SettingsService $settings)
    {
        // Honeypot spam protection: real users never fill this hidden field.
        if ($request->filled('website')) {
            return redirect()->route('enquiry.success')
                ->with('success', 'Your enquiry has been submitted. We will contact you soon.');
        }

        $validated = $request->validated();

        // Basic duplicate protection: ignore identical submissions from the
        // same mobile number within the last 2 minutes.
        $duplicate = Enquiry::where('mobile', $validated['mobile'])
            ->where('message', $validated['message'])
            ->where('created_at', '>=', now()->subMinutes(2))
            ->exists();

        if ($duplicate) {
            return redirect()->route('enquiry.success')
                ->with('success', 'Your enquiry has been submitted. We will contact you soon.');
        }

        $product = null;
        if (! empty($validated['product_id'])) {
            $product = Product::where('status', 'published')->find($validated['product_id']);
        }

        $enquiry = Enquiry::create([
            'product_id' => $product?->id,
            'name' => $validated['name'],
            'mobile' => $validated['mobile'],
            'email' => $validated['email'] ?? null,
            'product_reference' => $validated['product_reference'] ?? ($product?->sku ?? null),
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        // Email notification: optional and controlled by admin setting.
        if ($settings->get('email_notifications_enabled', false)) {
            $adminEmail = $settings->get('email');
            if ($adminEmail) {
                try {
                    Mail::to($adminEmail)->send(new EnquiryReceived($enquiry));
                } catch (\Throwable $e) {
                    Log::error('Enquiry notification could not be sent.', [
                        'enquiry_id' => $enquiry->id,
                        'exception' => get_class($e),
                        'message' => $e->getMessage(),
                    ]);
                }
            }
        }

        return redirect()->route('enquiry.success')
            ->with('success', 'Your enquiry has been submitted successfully. We will contact you soon.');
    }

    public function contact(\Illuminate\Http\Request $request, SettingsService $settings)
    {
        return view('pages.contact-enquiry', compact('settings'));
    }

    public function contactStore(EnquiryRequest $request, SettingsService $settings)
    {
        if ($request->filled('website')) {
            return redirect()->route('contact.success')
                ->with('success', 'Your message has been submitted. We will contact you soon.');
        }

        $validated = $request->validated();

        $duplicate = Enquiry::where('mobile', $validated['mobile'])
            ->where('message', $validated['message'])
            ->where('created_at', '>=', now()->subMinutes(2))
            ->exists();

        if ($duplicate) {
            return redirect()->route('contact.success')
                ->with('success', 'Your message has been submitted. We will contact you soon.');
        }

        $enquiry = Enquiry::create([
            'product_id' => null,
            'name' => $validated['name'],
            'mobile' => $validated['mobile'],
            'email' => $validated['email'] ?? null,
            'product_reference' => null,
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        if ($settings->get('email_notifications_enabled', false)) {
            $adminEmail = $settings->get('email');
            if ($adminEmail) {
                try {
                    Mail::to($adminEmail)->send(new EnquiryReceived($enquiry));
                } catch (\Throwable $e) {
                    Log::error('Contact notification could not be sent.', [
                        'enquiry_id' => $enquiry->id,
                        'exception' => get_class($e),
                        'message' => $e->getMessage(),
                    ]);
                }
            }
        }

        return redirect()->route('contact.success')
            ->with('success', 'Your message has been submitted successfully. We will contact you soon.');
    }

    public function success(SettingsService $settings)
    {
        return view('pages.enquiry-success', compact('settings'));
    }

    public function contactSuccess(SettingsService $settings)
    {
        return view('pages.enquiry-success', compact('settings'));
    }
}
