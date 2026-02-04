<?php
/**
 * ACF Block Loader Service
 * 
 * Automatically discovers and registers custom ACF blocks.
 * 
 * @package RFPlugin\ACF\Blocks
 * @since 1.0.0
 */

namespace RFPlugin\ACF\Blocks;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * BlockLoader class
 */
class BlockLoader
{
    /**
     * Registered block instances
     * 
     * @var BaseBlock[]
     */
    private array $blocks = [];

    /**
     * Initialize the block loader
     * 
     * @return void
     */
    public function init(): void
    {
        add_action('init', [$this, 'registerBlockCategory']);
        add_action('acf/init', [$this, 'loadBlocks']);
    }

    /**
     * Register custom block category
     * 
     * @return void
     */
    public function registerBlockCategory(): void
    {
        add_filter('block_categories_all', function ($categories) {
            return array_merge(
                $categories,
                [
                    [
                        'slug'  => 'rfplugin-blocks',
                        'title' => __('RoyalFoam Premium Blocks', 'rfplugin'),
                        'icon'  => 'admin-plugins',
                    ],
                ]
            );
        }, 10, 1);
    }

    /**
     * Discover and load all blocks from the Definitions directory
     * 
     * @return void
     */
    public function loadBlocks(): void
    {
        if (!function_exists('acf_register_block_type')) {
            return;
        }

        $definitionsPath = __DIR__ . '/Definitions';
        if (!is_dir($definitionsPath)) {
            return;
        }

        $files = scandir($definitionsPath);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || substr($file, -4) !== '.php') {
                continue;
            }

            $className = 'RFPlugin\\ACF\\Blocks\\Definitions\\' . substr($file, 0, -4);
            
            if (class_exists($className)) {
                $block = new $className();
                if ($block instanceof BaseBlock) {
                    $this->blocks[] = $block;
                    $this->registerBlock($block);
                }
            }
        }
    }

    /**
     * Register a single block with WordPress and ACF
     * 
     * @param BaseBlock $block
     * @return void
     */
    private function registerBlock(BaseBlock $block): void
    {
        acf_register_block_type($block->getSettings());
        $block->registerFields();
    }
}
