<?php

/**
 * Single Invoice Template
 *
 * Print-friendly invoice display with corporate branding.
 *
 * @package RFPlugin
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

// Check permissions
$invoice_id = get_the_ID();
$current_user = wp_get_current_user();
$invoice_user_id = get_post_field('post_author', $invoice_id);
$can_view = current_user_can('manage_options') || $current_user->ID == $invoice_user_id;

if (!$can_view && !is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

if (!$can_view) {
    include RFPLUGIN_PATH . 'templates/frontend/access-denied.php';
    return;
}

get_header();

// Get invoice data
$invoice_number = get_field('invoice_number') ?: sprintf('INV-%06d', $invoice_id);
$invoice_date = get_field('invoice_date') ?: get_the_date();
$due_date = get_field('due_date') ?: '';
$status = get_field('invoice_status') ?: 'pending';
$client_name = get_field('client_name') ?: '';
$client_email = get_field('client_email') ?: '';
$client_address = get_field('client_address') ?: '';
$items = get_field('invoice_items') ?: [];
$notes = get_field('invoice_notes') ?: '';
$subtotal = 0;
$tax_rate = get_field('tax_rate') ?: 0;

// Calculate totals
foreach ($items as $item) {
    $subtotal += floatval($item['quantity'] ?? 1) * floatval($item['price'] ?? 0);
}
$tax = $subtotal * ($tax_rate / 100);
$total = $subtotal + $tax;

// Status classes
$status_classes = [
    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
    'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
    'overdue' => 'bg-red-100 text-red-800 border-red-200',
    'cancelled' => 'bg-slate-100 text-slate-600 border-slate-200',
];
$status_class = $status_classes[$status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
?>

<main id="main-content" class="flex-grow min-h-screen bg-slate-50 print:bg-white" role="main">
    <div class="container mx-auto px-6 py-12 max-w-4xl print:p-0 print:max-w-none">

        <!-- Print Header (hidden on screen) -->
        <div class="hidden print:block mb-8">
            <img src="<?php echo esc_url(RFPLUGIN_URL . 'assets/images/logo.png'); ?>" alt="RoyalFoam" class="h-12 w-auto">
        </div>

        <!-- Controls (hidden on print) -->
        <div class="flex items-center justify-between mb-8 print:hidden rf-animate-up">
            <a href="<?php echo esc_url(admin_url('edit.php?post_type=rf_invoice')); ?>" class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">
                <span class="dashicons dashicons-arrow-left-alt2 mr-1" aria-hidden="true"></span>
                <?php esc_html_e('Back to Invoices', 'rfplugin'); ?>
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm hover:shadow">
                <span class="dashicons dashicons-printer" aria-hidden="true"></span>
                <?php esc_html_e('Print Invoice', 'rfplugin'); ?>
            </button>
        </div>

        <!-- Invoice Document -->
        <article class="bg-white shadow-xl rounded-2xl overflow-hidden print:shadow-none print:rounded-none rf-animate-up" itemscope itemtype="https://schema.org/Invoice">

            <!-- Header -->
            <header class="bg-slate-900 text-white p-8 md:p-12 print:bg-transparent print:text-black print:p-0 print:border-b-2 print:border-slate-900 print:mb-8">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight mb-4"><?php esc_html_e('Invoice', 'rfplugin'); ?></h1>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide border <?php echo esc_attr($status_class); ?>">
                            <?php echo esc_html(ucfirst($status)); ?>
                        </span>
                    </div>
                    <?php /* Logo could go here for screen view if desired */ ?>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-8 text-sm opacity-90 print:opacity-100">
                    <div>
                        <span class="block text-slate-400 text-xs uppercase tracking-wider mb-1 print:text-slate-500 max-w-2xl"><?php esc_html_e('Invoice #', 'rfplugin'); ?></span>
                        <span class="font-mono text-lg font-medium" itemprop="identifier"><?php echo esc_html($invoice_number); ?></span>
                    </div>
                    <div>
                        <span class="block text-slate-400 text-xs uppercase tracking-wider mb-1 print:text-slate-500"><?php esc_html_e('Date', 'rfplugin'); ?></span>
                        <span class="font-medium" itemprop="paymentDueDate"><?php echo esc_html($invoice_date); ?></span>
                    </div>
                    <?php if ($due_date) : ?>
                        <div>
                            <span class="block text-slate-400 text-xs uppercase tracking-wider mb-1 print:text-slate-500"><?php esc_html_e('Due Date', 'rfplugin'); ?></span>
                            <span class="font-medium"><?php echo esc_html($due_date); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </header>

            <div class="p-8 md:p-12 print:p-0">
                <!-- Client Info -->
                <section class="mb-12" itemprop="customer" itemscope itemtype="https://schema.org/Organization">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4 border-b border-slate-100 pb-2"><?php esc_html_e('Bill To', 'rfplugin'); ?></h2>
                    <div class="text-slate-700">
                        <?php if ($client_name) : ?>
                            <p class="font-bold text-lg text-slate-900 mb-1" itemprop="name"><?php echo esc_html($client_name); ?></p>
                        <?php endif; ?>
                        <?php if ($client_email) : ?>
                            <p class="mb-1" itemprop="email"><?php echo esc_html($client_email); ?></p>
                        <?php endif; ?>
                        <?php if ($client_address) : ?>
                            <p class="whitespace-pre-line" itemprop="address"><?php echo esc_html($client_address); ?></p>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Items Table -->
                <section class="mb-12">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b-2 border-slate-100 text-slate-500">
                                <th class="pb-3 font-semibold"><?php esc_html_e('Description', 'rfplugin'); ?></th>
                                <th class="pb-3 font-semibold text-right w-24"><?php esc_html_e('Qty', 'rfplugin'); ?></th>
                                <th class="pb-3 font-semibold text-right w-32"><?php esc_html_e('Price', 'rfplugin'); ?></th>
                                <th class="pb-3 font-semibold text-right w-32"><?php esc_html_e('Total', 'rfplugin'); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php foreach ($items as $item) :
                                $qty = floatval($item['quantity'] ?? 1);
                                $price = floatval($item['price'] ?? 0);
                                $line_total = $qty * $price;
                            ?>
                                <tr>
                                    <td class="py-4 font-medium text-slate-900"><?php echo esc_html($item['description'] ?? ''); ?></td>
                                    <td class="py-4 text-right text-slate-600"><?php echo esc_html($qty); ?></td>
                                    <td class="py-4 text-right text-slate-600"><?php echo esc_html(number_format($price, 2)); ?></td>
                                    <td class="py-4 text-right font-medium text-slate-900"><?php echo esc_html(number_format($line_total, 2)); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="border-t-2 border-slate-100">
                            <tr>
                                <td colspan="3" class="pt-4 text-right text-slate-500"><?php esc_html_e('Subtotal', 'rfplugin'); ?></td>
                                <td class="pt-4 text-right font-medium text-slate-900"><?php echo esc_html(number_format($subtotal, 2)); ?></td>
                            </tr>
                            <?php if ($tax_rate > 0) : ?>
                                <tr>
                                    <td colspan="3" class="pt-2 text-right text-slate-500"><?php printf(esc_html__('Tax (%s%%)', 'rfplugin'), esc_html($tax_rate)); ?></td>
                                    <td class="pt-2 text-right font-medium text-slate-900"><?php echo esc_html(number_format($tax, 2)); ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td colspan="3" class="pt-4 text-right text-lg font-bold text-slate-900"><?php esc_html_e('Total', 'rfplugin'); ?></td>
                                <td class="pt-4 text-right text-lg font-bold text-blue-600" itemprop="totalPaymentDue"><?php echo esc_html(number_format($total, 2)); ?> €</td>
                            </tr>
                        </tfoot>
                    </table>
                </section>

                <!-- Notes -->
                <?php if ($notes) : ?>
                    <section class="bg-slate-50 rounded-xl p-6 print:bg-transparent print:p-0 print:border print:border-slate-200">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2"><?php esc_html_e('Notes', 'rfplugin'); ?></h2>
                        <p class="text-slate-600 text-sm whitespace-pre-line"><?php echo esc_html($notes); ?></p>
                    </section>
                <?php endif; ?>
            </div>

            <!-- Footer -->
            <footer class="bg-slate-50 border-t border-slate-100 p-8 text-center text-slate-500 text-sm print:hidden">
                <p><?php esc_html_e('Thank you for your business.', 'rfplugin'); ?></p>
            </footer>

        </article>
    </div>
</main>

<?php get_footer(); ?>