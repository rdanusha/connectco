<?php

/**
 * Provide a admin area view for the plugin
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
<?php
/**
 * Tabs names array
 */
$tabs = array(
    'settings' => __('Settings', 'connect-co'),
);
/**
 * current tab
 */
$current = (isset($_GET['tab'])) ? $_GET['tab'] : 'settings';
?>
<h1>Connect Co. </h1>

<h2 class="nav-tab-wrapper">
    <?php foreach ($tabs as $tab => $name): ?>
        <?php
        $class = ($tab == $current) ? 'nav-tab-active' : '';
        ?>
        <a class="nav-tab <?php echo $class; ?>"
           href="?page=connect-co-admin&tab=<?php echo $tab; ?>"><?php echo $name; ?></a>
    <?php endforeach; ?>
</h2>

<?php if ('settings' == $current) : ?>
    <div id="welcome-panel" class="welcome-panel">
        <div class="welcome-panel-content">
            <h2>Connect Co. plugin configurations</h2>
            <form method="post" name="connect_co_settings" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th scope="row">
                            <label for="connect_co_live_api_key"><?php echo __('Connect Co. live API key:', 'connect-co') ?></label>
                        </th>
                        <td>
                            <input type="text" id="connect_co_live_api_key" name="connect_co_live_api_key"
                                   class="regular-text" value="<?php echo get_option('connect_co_live_api_key'); ?>">
                            <p><?php echo __('Enter Connect Co. API live Key', 'connect-co') ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="connect_co_test_env"><?php echo __('Enable test environment?', 'connect-co') ?></label>
                        </th>
                        <td>
                            <input type="radio" id="connect_co_api_environment" name="connect_co_api_environment" class="regular-text"
                                   value="test" <?php echo (get_option('connect_co_api_environment')) == 'test' ? 'checked' : ''; ?>>
                            <label for="connect_co_api_environment"><?php echo __('Yes', 'connect-co') ?></label>
                            <input type="radio" id="connect_co_api_environment" name="connect_co_api_environment" class="regular-text"
                                   value="live" <?php echo (get_option('connect_co_api_environment')) == 'live' ? 'checked' : ''; ?>>
                            <label for="connect_co_api_environment"><?php echo __('No', 'connect-co') ?></label>
                        </td>
                    </tr>
                    <tr class="cc-test-api" <?php echo (get_option('connect_co_api_environment')) == 'test' ? '' : 'style="display: none"'; ?>>
                        <th scope="row">
                            <label for="connect_co_test_api_key"><?php echo __('Connect Co. test API key:', 'connect-co') ?></label>
                        </th>
                        <td>
                            <input type="text" id="connect_co_test_api_key" name="connect_co_test_api_key"
                                   class="regular-text" value="<?php echo get_option('connect_co_test_api_key'); ?>">
                            <p><?php echo __('Enter Connect Co. API test Key', 'connect-co') ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="connect_co_default_payment_type"><?php echo __('Default payment type', 'connect-co') ?></label>
                        </th>
                        <td>
                            <select id="connect_co_default_payment_type" name="connect_co_default_payment_type">
                                <?php if (isset($config['payment_types']) && !empty($config['payment_types'])): ?>
                                    <?php foreach ($config['payment_types'] as $key => $payment_type): ?>
                                        <option <?php echo (get_option('connect_co_default_payment_type')) == $payment_type ? 'selected' : ''; ?>
                                                value="<?php echo $key; ?>"><?php echo __($payment_type, 'connect-co'); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <p><?php echo __('Please select any payment type.', 'connect-co') ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="connect_co_average_package_size"><?php echo __('Average package size', 'connect-co') ?></label>
                        </th>
                        <td>
                            <select id="connect_co_average_package_size" name="connect_co_average_package_size">
                                <?php if (isset($config['package_sizes']) && !empty($config['package_sizes'])): ?>
                                    <?php foreach ($config['package_sizes'] as $key => $package_sizes): ?>
                                        <option <?php echo (get_option('connect_co_average_package_size')) == $package_sizes ? 'selected' : ''; ?>
                                                value="<?php echo $key; ?>"><?php echo __($package_sizes.' ( '.$key.' )', 'connect-co'); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="connect_co_average_weight_per_package"><?php echo __('Average weight per package', 'connect-co') ?></label>
                        </th>
                        <td>
                            <input type="number" id="connect_co_average_weight_per_package" min="0"
                                   name="connect_co_average_weight_per_package" class="small-text"
                                   value="<?php echo get_option('connect_co_average_weight_per_package'); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="connect_co_default_delivery_type"><?php echo __('Default delivery type', 'connect-co') ?></label>
                        </th>
                        <td>
                            <select id="connect_co_default_delivery_type" name="connect_co_default_delivery_type">
                                <?php if (isset($config['delivery_types']) && !empty($config['delivery_types'])): ?>
                                    <?php foreach ($config['delivery_types'] as $key => $delivery_type): ?>
                                        <option <?php echo (get_option('connect_co_default_delivery_type')) == $delivery_type ? 'selected' : ''; ?>
                                                value="<?php echo $key; ?>"><?php echo __($delivery_type, 'connect-co'); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <?php wp_nonce_field('connect_co_settings'); ?>
                <input type="hidden" name="action" value="save_connect_co_settings">
                <?php submit_button(); ?>
            </form>
        </div>
    </div>
<?php endif; ?>
