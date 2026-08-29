<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;

class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'sub', 'sup', 'small',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'ul', 'ol', 'li', 'blockquote', 'pre', 'code', 'hr',
        'a', 'img', 'figure', 'figcaption', 'span', 'div',
        'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'caption',
    ];

    private const ALLOWED_ATTRS = [
        'a' => ['href', 'title', 'target', 'rel', 'class', 'style'],
        'img' => ['src', 'alt', 'title', 'width', 'height', 'class', 'style'],
        'th' => ['colspan', 'rowspan', 'class', 'style'],
        'td' => ['colspan', 'rowspan', 'class', 'style'],
        'code' => ['class', 'style'],
        'pre' => ['class', 'style'],
        'span' => ['class', 'style'],
        'div' => ['class', 'style'],
        'p' => ['class', 'style'],
        'h1' => ['class', 'style'],
        'h2' => ['class', 'style'],
        'h3' => ['class', 'style'],
        'h4' => ['class', 'style'],
        'h5' => ['class', 'style'],
        'h6' => ['class', 'style'],
        'blockquote' => ['class', 'style'],
        'ul' => ['class', 'style'],
        'ol' => ['class', 'style'],
        'li' => ['class', 'style'],
        'table' => ['class', 'style'],
        'thead' => ['class', 'style'],
        'tbody' => ['class', 'style'],
        'tfoot' => ['class', 'style'],
        'tr' => ['class', 'style'],
        'figure' => ['class', 'style'],
        'figcaption' => ['class', 'style'],
    ];

    private const ALLOWED_STYLE_PROPERTIES = [
        'text-align' => '/^(left|right|center|justify|start|end)$/i',
        'margin' => '/^[a-zA-Z0-9\s\.\,\%\-]+$/',
        'margin-top' => '/^[a-zA-Z0-9\s\.\,\%\-]+$/',
        'margin-right' => '/^[a-zA-Z0-9\s\.\,\%\-]+$/',
        'margin-bottom' => '/^[a-zA-Z0-9\s\.\,\%\-]+$/',
        'margin-left' => '/^[a-zA-Z0-9\s\.\,\%\-]+$/',
        'padding' => '/^[a-zA-Z0-9\s\.\,\%\-]+$/',
        'padding-top' => '/^[a-zA-Z0-9\s\.\,\%\-]+$/',
        'padding-right' => '/^[a-zA-Z0-9\s\.\,\%\-]+$/',
        'padding-bottom' => '/^[a-zA-Z0-9\s\.\,\%\-]+$/',
        'padding-left' => '/^[a-zA-Z0-9\s\.\,\%\-]+$/',
        'width' => '/^[a-zA-Z0-9\s\.\,\%\-]+$/',
        'height' => '/^[a-zA-Z0-9\s\.\,\%\-]+$/',
        'max-width' => '/^[a-zA-Z0-9\s\.\,\%\-]+$/',
        'max-height' => '/^[a-zA-Z0-9\s\.\,\%\-]+$/',
        'float' => '/^(left|right|none)$/i',
        'display' => '/^(block|inline-block|inline|flex|none)$/i',
        'vertical-align' => '/^(top|middle|bottom|baseline|sub|super)$/i',
        'border-collapse' => '/^(collapse|separate)$/i',
    ];

    public function clean(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);

        try {
            $document->loadHTML(
                '<?xml encoding="UTF-8"><div>'.$html.'</div>',
                LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
            );

            $root = $document->documentElement;
            $this->cleanChildren($root);

            $output = '';
            foreach ($root->childNodes as $child) {
                $output .= $document->saveHTML($child);
            }

            return $output;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }
    }

    private function cleanChildren(DOMNode $node): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child->nodeType === XML_PI_NODE) {
                $node->removeChild($child);

                continue;
            }

            if ($child->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }

            $tag = strtolower($child->tagName);

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                $node->removeChild($child);

                continue;
            }

            $this->sanitizeAttributes($child);
            $this->cleanChildren($child);
        }
    }

    private function sanitizeAttributes(DOMElement $element): void
    {
        $tag = strtolower($element->tagName);
        $allowed = self::ALLOWED_ATTRS[$tag] ?? [];

        foreach (iterator_to_array($element->attributes) as $attribute) {
            $name = strtolower($attribute->nodeName);
            $value = $attribute->nodeValue;

            if (! in_array($name, $allowed, true)) {
                $element->removeAttribute($name);

                continue;
            }

            if ($name === 'style') {
                $cleanStyle = $this->sanitizeStyle((string) $value);
                if ($cleanStyle !== '') {
                    $element->setAttribute('style', $cleanStyle);
                } else {
                    $element->removeAttribute('style');
                }

                continue;
            }

            if (in_array($name, ['href', 'src'], true) && ! $this->isSafeUrl($name, $value)) {
                $element->removeAttribute($name);
            }
        }
    }

    private function sanitizeStyle(string $style): string
    {
        $declarations = explode(';', $style);
        $safeDeclarations = [];

        foreach ($declarations as $declaration) {
            $declaration = trim($declaration);
            if ($declaration === '' || ! str_contains($declaration, ':')) {
                continue;
            }

            [$prop, $val] = explode(':', $declaration, 2);
            $prop = strtolower(trim($prop));
            $val = trim($val);

            // Block dangerous strings
            if (preg_match('/(expression|javascript|behavior|vbscript|-moz-binding|url\s*\()/i', $val)) {
                continue;
            }

            if (isset(self::ALLOWED_STYLE_PROPERTIES[$prop])) {
                $pattern = self::ALLOWED_STYLE_PROPERTIES[$prop];
                if (preg_match($pattern, $val)) {
                    $safeDeclarations[] = "{$prop}: {$val}";
                }
            }
        }

        return implode('; ', $safeDeclarations);
    }

    private function isSafeUrl(string $attribute, string $value): bool
    {
        // Browsers strip ASCII control characters (C0 + DEL) and tabs/newlines from
        // URLs before resolving the scheme. Strip the same set so that obfuscated
        // schemes such as "jav\x0Ascript:" are rejected, not allowed through.
        $stripped = preg_replace('/[\x00-\x20\x7f]/', '', rawurldecode((string) $value)) ?? '';

        $scheme = strtolower((string) parse_url($stripped, PHP_URL_SCHEME));

        if ($scheme === '') {
            return true;
        }

        if (preg_match('/^[a-z][a-z0-9+.-]*$/', $scheme) !== 1) {
            return false;
        }

        $allowed = $attribute === 'src' ? ['http', 'https'] : ['http', 'https', 'mailto', 'tel'];

        return in_array($scheme, $allowed, true);
    }
}
