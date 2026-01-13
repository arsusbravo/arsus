<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('pages.index');
    }

    public function services()
    {
        return view('pages.services');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request)
    {
        // Rate limiting - max 3 submissions per hour per IP
        $key = 'contact-form:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => 'Too many submission attempts. Please try again in ' . ceil($seconds / 60) . ' minutes.'
            ], 429);
        }

        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:100',
            'subject' => 'required|string|in:general,quote,web,mobile,cloud,support,other',
            'message' => 'required|string|min:10|max:5000',
        ]);

        // Honeypot check - if website field exists and is filled, reject silently
        if ($request->filled('website')) {
            Log::info('Spam attempt detected - honeypot filled', [
                'ip' => $request->ip(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your message. We will get back to you soon.'
            ]);
        }

        try {
            // Prepare email data
            $emailData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'company' => $validated['company'] ?? 'Not provided',
                'subject' => $this->getSubjectLabel($validated['subject']),
                'message' => $validated['message'],
                'timestamp' => now()->format('F j, Y, g:i a T'),
            ];

            // Send email to company
            Mail::to(config('mail.from.address'))->send(new ContactFormMail($emailData));

            // Increment rate limiter
            RateLimiter::hit($key, 3600); // 1 hour

            // Log successful submission
            Log::info('Contact form submitted successfully', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your message. We will get back to you within 24 hours.'
            ]);

        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again or use the email link on this page to contact us directly.'
            ], 500);
        }
    }

    private function getSubjectLabel($subject)
    {
        $labels = [
            'general' => 'General Inquiry',
            'quote' => 'Request a Quote',
            'web' => 'Web Development',
            'mobile' => 'Mobile Development',
            'cloud' => 'Cloud Solutions',
            'support' => 'Technical Support',
            'other' => 'Other',
        ];

        return $labels[$subject] ?? 'General Inquiry';
    }
}
