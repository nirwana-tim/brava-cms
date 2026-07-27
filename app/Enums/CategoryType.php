<?php

namespace App\Enums;

enum CategoryType: string
{
    case Product = 'product';
    case Blog = 'blog';
    case Portfolio = 'portfolio';
}
