<?php

/**
 * Provide a section to add delivery information
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Connect_Co
 * @subpackage Connect_Co/admin/partials
 */

?>
    <br class="clear"/>
    <br class="clear"/>
    <div class="connect-co-container">
        <h3 style="margin: 2px"><?php echo __('Connect Co. Delivery Information', 'connect-co') ?></h3>
        <div class="">
            <?php if ($config['is_submitted'] == '1') : ?>
                <div id="cc-info" class="connect-co-info">
                    Order submitted to the Connect Co.
                </div>
            <?php endif; ?>
            <div id="cc-success" class="connect-co-success" style="display: none"></div>
            <div id="cc-error" class="connect-co-error" style="display: none"></div>

            <?php if (!$config['delivery_city_availability']): ?>
                <div class="connect-co-error">
                    <?php
                    $message = 'Connect Co. delivery is not available for the city of shipping address. Please choose the nearest city.';
                    echo __($message, 'connect-co')
                    ?>
                </div>
            <?php endif; ?>
            <div class="connect-co-delivery-cost">Delivery cost:<br><b><span id="cc-delivery-cost">0.00</span></b> LKR
            </div>
            <?php
            woocommerce_wp_select($config['pickup_locations']);
            woocommerce_wp_select($config['payment_types']);
            woocommerce_wp_text_input($config['package_weight']);
            woocommerce_wp_select($config['package_sizes']);
            woocommerce_wp_textarea_input($config['notes']);
            woocommerce_wp_select($config['cities']);
            woocommerce_wp_select($config['delivery_types']);
            ?>
            <div class="cc-delivery-date" style="display: none">
                <?php
                woocommerce_wp_text_input($config['scheduled_date']);
                ?>
            </div>
            <div class="cc-time-window" style="display: none">
                <?php
                woocommerce_wp_select($config['time_window']);
                ?>
            </div>
            <br class="clear"/>
            <br class="clear"/>
            <input type="hidden" id="cc_order_id" name="cc_order_id" value="<?php echo $order->get_id(); ?>">
            <button type="button" id="connect-co-submit" class="button"
                <?php echo ($config['is_submitted'] == '1') ? 'disabled' : ''; ?>
                <?php echo (!$config['delivery_city_availability']) ? 'disabled' : ''; ?>>
                <?php echo __(' Submit to Connect Co.', 'connect-co') ?>
            </button>
        </div>
    </div>
<?php if (!$config['delivery_city_availability']): ?>
    <?php
    $class = 'error';
    $is_dismissible = '';
    include_once 'connect-co-admin-notices.php';
    ?>
<?php endif; ?>