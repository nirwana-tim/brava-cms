<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Referencing Content Tables
    |--------------------------------------------------------------------------
    |
    | Content stores media references as plain URL strings (relative or
    | absolute), not foreign keys. This map lists every table/column that may
    | hold a reference to a media file. It is used by the media usage check
    | (delete protection) and the cleanup command to keep references in sync.
    |
    */

    'referencing' => [
        'portfolio_items' => ['photo', 'og_image'],
        'blogs' => ['featured_image', 'og_image', 'content'],
        'promos' => ['image'],
        'services' => ['photo'],
        'team_members' => ['avatar'],
        'testimonials' => ['avatar'],
        'users' => ['avatar'],
        'settings' => ['value'],
        'page_seos' => ['og_image'],
    ],

];
