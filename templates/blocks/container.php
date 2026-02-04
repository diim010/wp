<?php
/**
 * Container Block Template
 */

$id = 'rf-container-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

$padding = get_field('padding') ?: 'medium';
$bg_type = get_field('bg_type') ?: 'none';

$padding_classes = [
    'none'   => 'rf-py-0',
    'small'  => 'rf-py-8',
    'medium' => 'rf-py-16 md:rf-py-24',
    'large'  => 'rf-py-24 md:rf-py-32',
];

$bg_classes = [
    'none'   => 'rf-bg-transparent',
    'slate'  => 'rf-bg-slate-50',
    'white'  => 'rf-bg-white',
    'glass'  => 'rf-glass-card rf-mx-4 md:rf-mx-0',
];

$className = 'rf-section-container ' . $padding_classes[$padding] . ' ' . $bg_classes[$bg_type];
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}

?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="rf-container rf-mx-auto rf-px-8">
        <InnerBlocks />
    </div>
</div>
