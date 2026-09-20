<?php

$prefixes = ['' => 'en', '/nl' => 'nl', '/id' => 'id'];

foreach ($prefixes as $prefix => $locale) {
    it("renders the cases landing, success stories and projects pages ({$locale})", function () use ($prefix) {
        foreach (['/cases', '/cases/success-stories', '/cases/projects'] as $path) {
            $response = $this->get($prefix . $path);

            $response->assertOk();
            expect(ltrim($response->getContent()))->toStartWith('<!DOCTYPE html>');
        }
    });

    it("keeps the case detail pages working ({$locale})", function () use ($prefix) {
        $this->get("{$prefix}/cases/online-reservation-system")->assertOk();
    });

    it("lists every project with an external link ({$locale})", function () use ($prefix) {
        $response = $this->get("{$prefix}/cases/projects");

        foreach (config('projects') as $project) {
            $response->assertSee('href="' . $project['url'] . '"', false);
        }
        $response->assertSee('rel="noopener noreferrer"', false);
    });
}

it('has a title and description for every project in every locale', function () {
    foreach (['en', 'nl', 'id'] as $locale) {
        foreach (array_keys(config('projects')) as $slug) {
            foreach (['title', 'description'] as $field) {
                $key = "projects.items.{$slug}.{$field}";

                expect(trans($key, [], $locale))->not->toBe($key, "Missing {$key} for {$locale}");
            }
        }
    }
});

it('shows the AI integration case study with its gallery', function () {
    $response = $this->get('/cases/ai-integration');

    $response->assertOk()
        ->assertSee('AI Integration')
        ->assertSee('OpenRouter')
        ->assertSee('images/cases/9-1.jpg')
        ->assertSee('images/cases/9-3.jpg');
    expect(file_exists(public_path('images/cases/9-3.jpg')))->toBeTrue();
});

it('lists the AI integration case first on the success stories page', function () {
    $html = $this->get('/cases/success-stories')->getContent();

    expect(strpos($html, 'cases/ai-integration'))->toBeLessThan(strpos($html, 'cases/online-reservation-system'));
});
