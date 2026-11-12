<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\User;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Mail\EnquiryReceived;
use Filament\Notifications\Notification as FilamentNotification;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EnquiryController extends Controller
{
    public function create(Request $request, SettingsService $settings)
    {
        $product = null;
        if ($request->filled('product')) {
            $product = Product::where('status', 'published')->find($request->get('product'));
        }

        return view('pages.enquiry', compact('product', 'settings'));
    }

    public function store(Request $request, SettingsService $settings)
    {
        return $this->save($request, $settings, 'product');
    }

    public function contact(Request $request, SettingsService $settings)
    {
        return $this->save($request, $settings, 'contact');
    }

    private function save(Request $request, SettingsService $settings, string $type)
    {
        // Honeypot spam protection: real users never fill this hidden field.
        if ($request->filled('website')) {
            return redirect()->route('enquiry.success')
                ->with('success', 'Your enquiry has been submitted. We will contact you soon.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:20'],
            'email' => [$type === 'contact' ? 'required' : 'nullable', 'email', 'max:255'],
            'subject' => [$type === 'contact' ? 'required' : 'nullable', 'string', 'max:255'],
            'product_id' => ['nullable', Rule::exists('products', 'id')->where('status', 'published')],
            'product_reference' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        // Basic duplicate protection: ignore identical submissions from the
        // same mobile number within the last 2 minutes.
        $duplicate = Enquiry::where('mobile', $request->get('mobile'))
            ->where('message', $request->get('message'))
            ->where('enquiry_type', $type)
            ->where('created_at', '>=', now()->subMinutes(2))
            ->exists();

        if ($duplicate) {
            return redirect()->route('enquiry.success')
                ->with('success', 'Your enquiry has been submitted. We will contact you soon.');
        }

        $product = isset($validated['product_id']) ? Product::find($validated['product_id']) : null;
        $enquiry = Enquiry::create([
            'product_id' => $type === 'product' ? $product?->id : null,
            'name' => $validated['name'],
            'mobile' => $validated['mobile'],
            'email' => $validated['email'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'enquiry_type' => $type,
            'product_reference' => $type === 'product' ? ($product?->sku ?? ($validated['product_reference'] ?? null)) : null,
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        $admins = User::where('is_admin', true)->get();
        if ($admins->isNotEmpty()) {
            FilamentNotification::make()
                ->title($type === 'contact' ? 'New contact message' : 'New product enquiry')
                ->body('A customer message is ready for review.')
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->sendToDatabase($admins);
        }

        try {
            $adminEmail = $settings->get('email');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new EnquiryReceived($enquiry));
            }
        } catch (TransportExceptionInterface $exception) {
            Log::error('Enquiry notification could not be sent.', [
                'enquiry_id' => $enquiry->id,
                'exception' => $exception::class,
            ]);
        }

        return redirect()->route('enquiry.success')
            ->with('success', 'Your enquiry has been submitted. We will contact you soon.');
    }

    public function success(SettingsService $settings)
    {
        return view('pages.enquiry-success', compact('settings'));
    }
}
