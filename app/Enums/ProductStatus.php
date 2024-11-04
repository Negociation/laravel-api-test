<?php
namespace App\Enums;

enum ProductStatus: string
{
    case PUBLISHED = 'published';
    case TRASH = 'trash';
    case DRAFT = 'draft';
}