<?php

use App\Services\HtmlSanitizer;

test('drops disallowed tags and keeps allowed content', function () {
    $sanitizer = new HtmlSanitizer;

    $result = $sanitizer->clean('<p>Hello</p><script>alert(1)</script><iframe src="https://evil.test"></iframe>');

    expect($result)->toContain('<p>Hello</p>')
        ->and($result)->not->toContain('script')
        ->and($result)->not->toContain('iframe');
});

test('drops style attributes even on allowed tags', function () {
    $sanitizer = new HtmlSanitizer;

    $result = $sanitizer->clean('<p style="position:fixed;top:0">Safe</p>');

    expect($result)->toContain('Safe')
        ->and($result)->not->toContain('style=');
});

test('strips javascript and data urls from href', function () {
    $sanitizer = new HtmlSanitizer;

    $result = $sanitizer->clean(
        '<a href="javascript:alert(1)">Click</a>'.
        '<a href="data:text/html,%3Cscript%3Ealert(1)%3C/script%3E">Pwn</a>'.
        '<a href="  java&#x73;cript:alert(1)">Encoded</a>'
    );

    expect($result)->not->toContain('javascript:')
        ->and($result)->not->toContain('data:text/html')
        ->and($result)->not->toContain('java&#x73;cript');
});

test('keeps safe hrefs and strips unsafe src schemes', function () {
    $sanitizer = new HtmlSanitizer;

    $result = $sanitizer->clean(
        '<a href="https://brava.id" target="_blank">Link</a>'.
        '<a href="mailto:hi@brava.id">Mail</a>'.
        '<a href="tel:+6281234567890">Tel</a>'.
        '<img src="https://brava.id/img.jpg" alt="ok">'.
        '<img src="data:image/png;base64,AAAA" alt="bad">'
    );

    expect($result)->toContain('href="https://brava.id"')
        ->and($result)->toContain('href="mailto:hi@brava.id"')
        ->and($result)->toContain('href="tel:+6281234567890"')
        ->and($result)->toContain('src="https://brava.id/img.jpg"')
        ->and($result)->not->toContain('data:image/png');
});

test('returns null and empty input untouched', function () {
    $sanitizer = new HtmlSanitizer;

    expect($sanitizer->clean(null))->toBeNull()
        ->and($sanitizer->clean(''))->toBe('')
        ->and($sanitizer->clean('   '))->toBe('   ');
});
