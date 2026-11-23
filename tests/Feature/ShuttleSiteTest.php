<?php

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

$locales = ['' => 'en', '/nl' => 'nl', '/id' => 'id'];

// a missing translation renders its key, e.g. "shuttle.home.hero_title"
const RAW_KEY = '/\bshuttle\.(meta|boot|nav|rooms|signs|sound|home|about|services|log|comms|return_shuttle|open_comms|game)\b/';

foreach ($locales as $prefix => $locale) {
    it("renders the shuttle pages ({$locale})", function () use ($prefix, $locale) {
        foreach (['/', '/about-us', '/services', '/contact'] as $path) {
            $url = $prefix === '' ? $path : $prefix . ($path === '/' ? '' : $path);
            $response = $this->get($url);

            $response->assertOk();
            $html = $response->getContent();
            expect($html)->toContain('<html lang="' . $locale . '"')
                ->toContain('id="shuttle-config"')
                ->not->toMatch(RAW_KEY);
        }
    });

    it("renders the mission log pages without raw translation keys ({$locale})", function () use ($prefix) {
        foreach (['/cases', '/cases/success-stories', '/cases/projects', '/cases/lead-generation'] as $path) {
            expect($this->get($prefix . $path)->assertOk()->getContent())->not->toMatch(RAW_KEY);
        }
    });
}

it('renders the cockpit minigame HUD skeleton on the home page without raw translation keys', function () {
    $html = $this->get('/')->getContent();

    expect($html)->toContain('id="cockpit-hint"')
        ->toContain('id="game-crosshair"')
        ->toContain('id="cockpit-game"')
        ->toContain('id="game-alert"')
        ->toContain('id="game-stats"')
        ->toContain('id="game-score"')
        ->toContain('id="game-time"')
        ->toContain('id="game-complete"')
        ->toContain('id="game-exit"')
        ->not->toMatch(RAW_KEY);
});

it('lists every case on the accomplished page, linking to its report', function () {
    $html = $this->get('/cases/success-stories')->getContent();

    foreach (['ai-integration', 'online-reservation-system', 'lead-generation'] as $slug) {
        expect($html)->toContain('cases/' . $slug);
    }
});

it('returns 404 for an unknown case', function () {
    $this->get('/cases/does-not-exist')->assertNotFound();
});

it('offers the language switcher with the same page in every locale', function () {
    $html = $this->get('/nl/services')->getContent();

    expect($html)->toContain('hreflang="en" href="' . url('/services') . '"')
        ->toContain('hreflang="id" href="' . url('/id/services') . '"');
});

describe('contact form', function () {
    beforeEach(fn () => RateLimiter::clear('contact-form:127.0.0.1'));

    $valid = [
        'name' => 'Test Pilot',
        'email' => 'pilot@example.com',
        'company' => 'Orbit BV',
        'subject' => 'quote',
        'message' => 'Hello, I would like a quote for a new webshop.',
    ];

    it('sends the mail and answers in the visitor\'s language', function () use ($valid) {
        Mail::fake();

        $this->postJson('/nl/contact', $valid)
            ->assertOk()
            ->assertJson(['success' => true, 'message' => __('shuttle.comms.server_sent', [], 'nl')]);

        Mail::assertSent(ContactFormMail::class, fn ($mail) => $mail->data['email'] === 'pilot@example.com');
    });

    it('rejects invalid input', function () use ($valid) {
        Mail::fake();

        $this->postJson('/contact', ['email' => 'nope'] + $valid)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        Mail::assertNothingSent();
    });

    it('silently drops bots that fill the honeypot', function () use ($valid) {
        Mail::fake();

        $this->postJson('/contact', $valid + ['website' => 'http://spam.example'])->assertOk()->assertJson(['success' => true]);

        Mail::assertNothingSent();
    });

    it('limits a sender to three messages per hour', function () use ($valid) {
        Mail::fake();

        foreach (range(1, 3) as $i) {
            $this->postJson('/contact', $valid)->assertOk();
        }

        $this->postJson('/contact', $valid)->assertStatus(429)->assertJson(['success' => false]);
    });
});
