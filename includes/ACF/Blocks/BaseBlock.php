<?php
/**
 * Base ACF Block Abstract Class
 * 
 * Provides a standardized structure for defining ACF blocks.
 * 
 * @package RFPlugin\ACF\Blocks
 * @since 1.0.0
 */

namespace RFPlugin\ACF\Blocks;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * BaseBlock class
 */
abstract class BaseBlock
{
    /**
     * Block configuration
     * 
     * @var array
     */
    protected array $settings = [];

    /**
     * Get block name (slug)
     * 
     * @return string
     */
    abstract public function getName(): string;

    /**
     * Get block title
     * 
     * @return string
     */
    abstract public function getTitle(): string;

    /**
     * Get block settings
     * 
     * @return array
     */
    public function getSettings(): array
    {
        return array_merge([
            'name'            => $this->getName(),
            'title'           => $this->getTitle(),
            'description'     => '',
            'render_template' => RFPLUGIN_PATH . 'templates/blocks/' . $this->getName() . '.php',
            'category'        => 'rfplugin-blocks',
            'icon'            => 'admin-generic',
            'keywords'        => [],
            'mode'            => 'preview',
            'supports'        => [
                'align' => true,
                'mode'  => false,
                'jsx'   => true,
            ],
        ], $this->settings);
    }

    /**
     * Register ACF fields for the block
     * 
     * @return void
     */
    abstract public function registerFields(): void;
}
