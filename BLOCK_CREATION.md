# ACF Block Creation Manual

This manual explains how to add new custom blocks to the RoyalFoam Premium Blocks library using the modular architecture.

## Automated Flow Overview

The RoyalFoam plugin uses an automated `BlockLoader` that scans the `includes/ACF/Blocks/Definitions/` directory. Each PHP class in that directory represents a unique block.

---

## Step 1: Create the Block Definition

Create a new PHP file in `includes/ACF/Blocks/Definitions/`. The filename should match the class name (e.g., `MyNewBlock.php`).

```php
<?php
namespace RFPlugin\ACF\Blocks\Definitions;

use RFPlugin\ACF\Blocks\BaseBlock;

class MyNewBlock extends BaseBlock {
    // 1. Return the block slug (used for template name)
    public function getName(): string {
        return 'my-new-block';
    }

    // 2. Return the display name in the editor
    public function getTitle(): string {
        return __('My New Block', 'rfplugin');
    }

    // 3. Define the ACF fields
    public function registerFields(): void {
        acf_add_local_field_group([
            'key' => 'group_block_my_new_block',
            'title' => $this->getTitle(),
            'fields' => [
                [
                    'key' => 'field_my_block_text',
                    'label' => __('Display Text', 'rfplugin'),
                    'name' => 'display_text',
                    'type' => 'text',
                ],
            ],
            'location' => [
                [['param' => 'block', 'operator' => '==', 'value' => 'acf/' . $this->getName()]],
            ],
        ]);
    }
}
```

---

## Step 2: Create the Frontend Template

Create a matching component template in `templates/blocks/`. The filename must match the string returned by `getName()` in Step 1.

**File Path**: `templates/blocks/my-new-block.php`

```php
<?php
/**
 * My New Block Template
 */

$text = get_field('display_text');
$id = $block['id'];

?>
<div id="block-<?php echo esc_attr($id); ?>" class="my-custom-block">
    <?php if ($text): ?>
        <h2><?php echo esc_html($text); ?></h2>
    <?php endif; ?>
</div>
```

---

## Step 3: Customizing Block Settings

If you need to change the block icon, category, or support settings, you can override the `$settings` property in your block class:

```php
class MyNewBlock extends BaseBlock {
    protected array $settings = [
        'icon' => 'format-gallery',
        'description' => 'A beautiful custom gallery block.',
        'supports' => [
            'align' => ['wide', 'full'],
            'mode' => true
        ]
    ];
    // ... getName, getTitle, registerFields ...
}
```

---

## Best Practices

1. **Prefixing**: Always use the `rf-` prefix for CSS classes in your templates (e.g., `<div class="rf-hero">`).
2. **Translatability**: Use `__()` or `_e()` functions for all text strings to ensure the block is translation-ready.
3. **Sanitization**: Always use `esc_html()`, `esc_attr()`, or `esc_url()` when outputting field data in templates.
