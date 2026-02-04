<?php
/**
 * Validator Utility
 * 
 * Data validation helpers for the plugin.
 * 
 * @package RFPlugin\Utils
 * @since 1.0.0
 */

namespace RFPlugin\Utils;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Validator class
 * 
 * @since 1.0.0
 */
class Validator
{
    /**
     * Validate product ID
     * 
     * @param mixed $productId Product ID to validate
     * @return bool
     */
    public static function isValidProductId($productId): bool
    {
        if (!is_numeric($productId)) {
            return false;
        }

        $post = get_post($productId);
        return $post && $post->post_type === 'product';
    }

    /**
     * Validate invoice data structure
     * 
     * @param array<string, mixed> $data Invoice data
     * @return bool
     */
    public static function isValidInvoiceData(array $data): bool
    {
        $requiredFields = ['customer_name', 'customer_email', 'products'];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return false;
            }
        }

        if (!is_email($data['customer_email'])) {
            return false;
        }

        if (!is_array($data['products'])) {
            return false;
        }

        return true;
    }

    /**
     * Validate specifications array
     * 
     * @param array<string, mixed> $specs Specifications to validate
     * @return bool
     */
    public static function isValidSpecifications(array $specs): bool
    {
        $numericFields = ['height', 'width', 'length'];
        
        foreach ($numericFields as $field) {
            if (isset($specs[$field]) && !is_numeric($specs[$field])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Sanitize and validate dimension
     * 
     * @param mixed $value Dimension value
     * @param float $min Minimum allowed value
     * @param float $max Maximum allowed value
     * @return float|null
     */
    public static function sanitizeDimension($value, float $min = 0, float $max = 10000): ?float
    {
        $float = floatval($value);
        
        if ($float < $min || $float > $max) {
            return null;
        }

        return $float;
    }
}
