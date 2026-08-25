<?php

test('site webmanifest file exists and is valid json', function () {
    $path = public_path('site.webmanifest');
    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    $data = json_decode($content, true);

    expect($data)->toBeArray()
        ->and($data['name'])->toBe('BRAVA CMS')
        ->and($data['short_name'])->toBe('BRAVA')
        ->and($data['display'])->toBe('standalone')
        ->and($data['start_url'])->toBe('/')
        ->and($data['icons'])->toBeArray()
        ->and(count($data['icons']))->toBeGreaterThan(0);
});

test('manifest.json file exists and is valid json', function () {
    $path = public_path('manifest.json');
    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    $data = json_decode($content, true);

    expect($data)->toBeArray()
        ->and($data['name'])->toBe('BRAVA CMS')
        ->and($data['display'])->toBe('standalone');
});

test('service worker sw.js file exists and has correct cache strategy', function () {
    $path = public_path('sw.js');
    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    expect($content)->toContain('brava-cms-cache')
        ->and($content)->toContain('navigate')
        ->and($content)->toContain('Stale-While-Revalidate');
});

test('offline fallback html file exists and is valid', function () {
    $path = public_path('offline.html');
    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    expect($content)->toContain('Anda Sedang Offline');
});

test('guest layout contains pwa meta tags and manifest link', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSee('name="theme-color" content="#13247d"', false);
    $response->assertSee('rel="manifest" href="/site.webmanifest"', false);
    $response->assertSee('name="mobile-web-app-capable" content="yes"', false);
});
