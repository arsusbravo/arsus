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

    public function cases()
    {
        return view('pages.cases');
    }

    public function caseShow($slug)
    {
        // Check if translation exists for this case
        $caseKey = "cases.{$slug}";
        if (!__("{$caseKey}.title")) {
            abort(404);
        }

        // Get case data from translations
        $case = [
            'title' => __("{$caseKey}.title"),
            'subtitle' => __("{$caseKey}.subtitle"),
            'description' => __("{$caseKey}.description"),
            'challenge' => __("{$caseKey}.challenge"),
            'solution' => __("{$caseKey}.solution"),
            'features' => __("{$caseKey}.features"),
            'results' => __("{$caseKey}.results"),
            'tech_stack' => $this->getCaseTechStack($slug),
            'images' => $this->getCaseImageCount($slug),
            'case_number' => $this->getCaseNumber($slug),
        ];

        return view('pages.case-detail', compact('case', 'slug'));
    }

    private function getCaseTechStack($slug)
    {
        $techStacks = [
            'online-reservation-system' => ['Laravel', 'Vue.js', 'MySQL', 'Tailwind CSS'],
            'online-order-system' => ['Laravel', 'PHP', 'MySQL', 'Bootstrap'],
            'financial-calculators' => ['PHP', 'JavaScript', 'MySQL', 'CSS3'],
            'digitalized-form' => ['Laravel', 'Vue.js', 'PostgreSQL', 'AWS S3'],
            'document-signing' => ['Laravel', 'Node.js', 'MongoDB', 'Docker'],
            'lead-generation' => ['React', 'Laravel API', 'Redis', 'Elasticsearch'],
        ];
        return $techStacks[$slug] ?? [];
    }

    private function getCaseImageCount($slug)
    {
        $imageCounts = [
            'online-reservation-system' => 7,
            'online-order-system' => 5,
            'financial-calculators' => 3,
            'digitalized-form' => 3,
            'document-signing' => 3,
            'lead-generation' => 3,
        ];
        return $imageCounts[$slug] ?? 3;
    }

    private function getCaseNumber($slug)
    {
        $caseNumbers = [
            'online-reservation-system' => 1,
            'online-order-system' => 2,
            'financial-calculators' => 3,
            'digitalized-form' => 4,
            'document-signing' => 5,
            'lead-generation' => 6,
        ];
        return $caseNumbers[$slug] ?? 1;
    }

    public function caseShowOld($slug)
    {
        // Old static case data - keeping for reference
        $cases = [
            'online-reservation-system' => [
                'title' => 'Online Reservation System',
                'subtitle' => 'Vehicle Rental Management Platform',
                'description' => 'A bespoke application developed for a prominent rental car company to streamline operations. The platform enables customers to browse available vehicles and secure reservations for specific dates and times, while providing administrators with comprehensive management tools.',
                'challenge' => 'The rental company needed a modern, efficient system to replace manual booking processes and provide real-time inventory management across multiple locations.',
                'solution' => 'We developed a dual-interface platform with an intuitive customer-facing booking system and a powerful administrative dashboard. The solution includes real-time availability tracking, automated confirmation emails, and comprehensive reporting tools.',
                'features' => [
                    'Vehicle inventory display with images, pricing, and specifications',
                    'Advanced search and filtering capabilities',
                    'Real-time availability and booking confirmation',
                    'Secure administrator dashboard with authentication',
                    'Calendar and list views for reservation tracking',
                    'Sales statistics and performance metrics',
                    'Multi-location management system',
                    'Automated email notifications',
                ],
                'tech_stack' => ['Laravel', 'Vue.js', 'MySQL', 'Tailwind CSS'],
                'results' => [
                    '70% reduction in booking processing time',
                    '45% increase in online reservations',
                    '95% customer satisfaction rate',
                    'Real-time inventory accuracy'
                ],
                'images' => 7,
                'case_number' => 1,
            ],
            'online-order-system' => [
                'title' => 'Online Order System',
                'subtitle' => 'Members-Only Food Ordering Platform',
                'description' => 'Sophisticated solution facilitates the seamless placement of food orders by registered members on a weekly basis. The platform restricts access to authorized users only, offering a secure ordering environment for a specialized catering company.',
                'challenge' => 'The catering company needed a secure, members-only platform to manage weekly food orders across multiple locations with different menus and support for international clientele.',
                'solution' => 'We created a comprehensive ordering platform with member authentication, multi-location menu management, and multilingual support. The system includes admin tools for menu updates, order management, and customer communication.',
                'features' => [
                    'Weekly diverse menus across various locations',
                    'Member authentication and authorization',
                    'Order placement and confirmation system',
                    'Administration menu management (add, edit, remove items)',
                    'Comprehensive order overview with modification capabilities',
                    'Client data management',
                    'Email content management and notifications',
                    'Web content oversight',
                    'User group management',
                    'Chinese language support',
                ],
                'tech_stack' => ['Laravel', 'PHP', 'MySQL', 'Bootstrap'],
                'results' => [
                    '80% reduction in order processing errors',
                    '60% increase in weekly orders',
                    'Successful multi-location deployment',
                    'Enhanced customer satisfaction'
                ],
                'images' => 5,
                'case_number' => 2,
            ],
            'financial-calculators' => [
                'title' => 'Financial Calculators',
                'subtitle' => 'Interactive Mortgage & Loan Calculator Platform',
                'description' => 'A sophisticated online calculator platform developed for a financial sector business. The solution integrates complex financial formulas to enable mortgage, credit, and loan calculations, enhancing customer engagement and supporting informed financial decision-making.',
                'challenge' => 'The financial institution needed an engaging tool to help potential customers understand their borrowing capacity while capturing leads for follow-up.',
                'solution' => 'We developed an interactive calculator platform with real-time calculations, lead capture functionality, and secure data storage. The system provides instant results while collecting valuable customer information for relationship building.',
                'features' => [
                    'Interactive calculator form embedded on front page',
                    'Comprehensive financial data collection (annual income, partnerships, savings)',
                    'Real-time calculation of maximum loan/mortgage/credit qualification',
                    'Results delivered via interface or email',
                    'Secure visitor profile storage in database',
                    'Automated email delivery system',
                    'Customer communication and engagement tools',
                    'Responsive design for mobile and desktop',
                ],
                'tech_stack' => ['PHP', 'JavaScript', 'MySQL', 'CSS3'],
                'results' => [
                    '250% increase in qualified leads',
                    '40% conversion rate improvement',
                    '3x increase in customer engagement',
                    'Reduced manual calculation time by 90%'
                ],
                'images' => 3,
                'case_number' => 3,
            ],
            'digitalized-form' => [
                'title' => 'Digitalized Claim Form',
                'subtitle' => 'Paperless Claims Processing System',
                'description' => 'Modern digital solution transforming traditional paper-based claim submissions into an efficient, automated workflow. The platform streamlines the entire claims process from submission to approval with secure document management.',
                'challenge' => 'The organization faced inefficiencies with paper-based claim processing, leading to delays, errors, and storage challenges.',
                'solution' => 'We implemented a fully digital claims management system with automated workflows, document scanning, secure storage, and real-time status tracking for both claimants and administrators.',
                'features' => [
                    'Digital form submission with validation',
                    'Document upload and scanning capabilities',
                    'Automated workflow routing',
                    'Real-time claim status tracking',
                    'Secure document storage and retrieval',
                    'Admin approval and processing tools',
                    'Email notifications at each stage',
                    'Comprehensive reporting and analytics',
                ],
                'tech_stack' => ['Laravel', 'Vue.js', 'PostgreSQL', 'AWS S3'],
                'results' => [
                    '85% faster claim processing',
                    '95% reduction in paper usage',
                    '60% decrease in processing errors',
                    'Improved audit trail compliance'
                ],
                'images' => 3,
                'case_number' => 4,
            ],
            'document-signing' => [
                'title' => 'Effortless Document Signing',
                'subtitle' => 'Secure E-Signature Platform',
                'description' => 'Comprehensive e-signature solution enabling remote document signing with enterprise-grade security, authentication, and legal compliance. The platform eliminates the need for physical presence in document execution.',
                'challenge' => 'The organization needed a legally compliant, secure solution for remote document signing that would maintain the integrity and authenticity of signed documents.',
                'solution' => 'We built a robust e-signature platform with multi-factor authentication, audit trails, certificate-based signatures, and full legal compliance. The system supports multiple signers and complex document workflows.',
                'features' => [
                    'Secure document upload and preparation',
                    'Multi-factor authentication',
                    'Certificate-based digital signatures',
                    'Multiple signer support and workflows',
                    'Complete audit trail logging',
                    'Document verification and validation',
                    'Legal compliance (eIDAS, ESIGN Act)',
                    'Mobile-responsive signing interface',
                    'Automated reminder notifications',
                ],
                'tech_stack' => ['Laravel', 'Node.js', 'MongoDB', 'Docker'],
                'results' => [
                    '100% paperless document execution',
                    '75% reduction in signing turnaround time',
                    'Full legal compliance achieved',
                    '99.9% signature verification accuracy'
                ],
                'images' => 3,
                'case_number' => 5,
            ],
            'lead-generation' => [
                'title' => 'Lead Generation Platform',
                'subtitle' => 'Marketing Automation & CRM Integration',
                'description' => 'Comprehensive marketing automation system designed to capture, nurture, and convert leads through intelligent form builders, CRM integration, and advanced analytics. The platform optimizes the entire lead generation funnel.',
                'challenge' => 'The marketing team struggled with disconnected tools, manual data entry, and lack of visibility into lead performance and conversion metrics.',
                'solution' => 'We created an all-in-one lead generation platform with drag-and-drop form builders, seamless CRM integration, automated nurture campaigns, and real-time analytics dashboard for data-driven decision making.',
                'features' => [
                    'Drag-and-drop form builder',
                    'Landing page creator with templates',
                    'CRM integration (Salesforce, HubSpot, custom)',
                    'Automated email nurture campaigns',
                    'Lead scoring and qualification',
                    'Real-time analytics and reporting',
                    'A/B testing capabilities',
                    'Conversion tracking and attribution',
                    'Team collaboration tools',
                ],
                'tech_stack' => ['React', 'Laravel API', 'Redis', 'Elasticsearch'],
                'results' => [
                    '180% increase in qualified leads',
                    '45% improvement in conversion rates',
                    '60% reduction in cost per lead',
                    '10x faster lead response time'
                ],
                'images' => 3,
                'case_number' => 6,
            ],
        ];

        // Check if case exists
        if (!array_key_exists($slug, $cases)) {
            abort(404);
        }

        $case = $cases[$slug];

        return view('pages.case-detail', compact('case', 'slug'));
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
