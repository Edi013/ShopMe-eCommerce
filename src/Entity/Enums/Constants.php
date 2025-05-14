<?php
namespace App\Entity\Enums;

enum Constants: string
{
    case NOT_ENOUGH_STOCK = 'not-enough-stock';
    case PRODUCT_NOT_FOUND = 'product-not-found';
    case INVALID_QUANTITY = 'invalid-quantity';
    case UNAUTHORIZED = 'unauthorized';
    case INTERNAL_ERROR = 'internal-error';
    case SUCCESS = 'success';
    case FAILED = 'failed';

    public function message(string $extra = ''): string
    {
        $base = match($this) {
            self::NOT_ENOUGH_STOCK => 'Not enough stock available.',
            self::PRODUCT_NOT_FOUND => 'The requested product was not found.',
            self::INVALID_QUANTITY => 'The quantity is invalid.',
            self::UNAUTHORIZED => 'You are not authorized to perform this action.',
            self::INTERNAL_ERROR => 'An unexpected error occurred.',
            self::SUCCESS => 'Operation completed successfully.',
            self::FAILED => 'Operation failed.',
        };

        return $extra ? "{$base} {$extra}" : $base;
    }

    public function code(): int
    {
        return match($this) {
            self::NOT_ENOUGH_STOCK => 400,
            self::PRODUCT_NOT_FOUND => 404,
            self::INVALID_QUANTITY => 400,
            self::UNAUTHORIZED => 401,
            self::INTERNAL_ERROR => 500,
            self::SUCCESS => 200,
            self::FAILED => 0,
        };
    }
}
