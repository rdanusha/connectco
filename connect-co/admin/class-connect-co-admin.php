<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Connect_Co
 * @subpackage Connect_Co/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Connect_Co
 * @subpackage Connect_Co/admin
 * @author     Anusha Priyamal <rdanusha@gmail.com>
 */
class Connect_Co_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $connect_co The ID of this plugin.
     */
    private $connect_co;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * API end point of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $connect_co_api The current version of this plugin.
     */
    private $connect_co_api;

    /**
     * API key of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $connect_co_api_key The current version of this plugin.
     */
    private $connect_co_api_key;

    /**
     * API key of this plugin user (merchant).
     *
     * @since    1.0.0
     * @access   private
     * @var      string $merchant_api_key The current version of this plugin.
     */
    private $connect_co_merchant_api_key;

    /**
     * API end point of this plugin user (test or live URL).
     *
     * @since    1.0.0
     * @access   private
     * @var      string $merchant_api The current version of this plugin.
     */
    private $connect_co_merchant_api;

    /**
     * API environment of this plugin user (test or live).
     *
     * @since    1.0.0
     * @access   private
     * @var      string $merchant_api The current version of this plugin.
     */
    private $connect_co_merchant_environment;


    const API_URL = array(
        'live' => 'http://api.connectcoapps.lk/api/',
        'test' => 'http://testbed.connectcoapps.lk/api/'
    );


    /**
     * Initialize the class and set its properties.
     *
     * @param string $connect_co The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */


    public function __construct($connect_co, $version)
    {

        $this->connect_co = $connect_co;
        $this->version = $version;
        $this->connect_co_api = "http://connectcoapps.lk/api/";
        $this->connect_co_api_key = "Nuk010211WQAASdc";
        $this->set_connect_co_merchant_api_settings();

    }

    public function set_connect_co_merchant_api_settings()
    {
        $this->connect_co_merchant_environment = get_option('connect_co_api_environment');
        if ($this->connect_co_merchant_environment == 'live') {
            $this->connect_co_merchant_api = self::API_URL['live'];
            $this->connect_co_merchant_api_key = get_option('connect_co_live_api_key');
        } else {
            $this->connect_co_merchant_api = self::API_URL['test'];
            $this->connect_co_merchant_api_key = get_option('connect_co_test_api_key');
        }
    }


    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Connect_Co_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Connect_Co_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->connect_co, plugin_dir_url(__FILE__) . 'css/connect-co-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Connect_Co_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Connect_Co_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->connect_co, plugin_dir_url(__FILE__) . 'js/connect-co-admin.js', array('jquery'), $this->version, false);
        wp_localize_script($this->connect_co, 'connect_co_ajax',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('connect_co_ajax_submit')
            )
        );
    }

    /**
     * Registers a new options page under Settings.
     *
     * @since    1.0.0
     */
    public function add_settings_page()
    {
        /**
         * Add submenu page to the Settings main menu.
         */
        add_options_page('Connect Co.',
            __('Connect Co.', 'connect-co'),
            'manage_options',
            'connect-co-admin', array($this, 'admin_page_interface'));
    }

    /**
     * Callback function for the admin settings page.
     *
     * @since    1.0.0
     */
    public function admin_page_interface()
    {
        /**
         *  include settings page UI
         */
        $config = $this->connect_co_settings_page_config();
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/connect-co-admin-display.php';
    }


    /**
     * Callback function for the woocommerce order edit custom fields.
     *
     * @since    1.0.0
     */
    public function display_data_in_order_details($order)
    {
        /**
         *  include order details custom field section
         */
        $config = $this->connect_co_order_details_page_config($order);
        if ($config) {
            require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/connect-co-admin-order-details-edit-display.php';
        }
    }


    /**
     * Callback function for Connect Co. settings page forms submit
     *
     * @since    1.0.0
     */
    public function save_connect_co_settings()
    {
        if (isset($_POST['_wpnonce']) &&
            wp_verify_nonce($_POST['_wpnonce'], 'connect_co_settings') &&
            $_POST['action'] == 'save_connect_co_settings'
        ) {

            $error = false;
            $api_environment = (isset($_POST['connect_co_api_environment'])) ? $_POST['connect_co_api_environment'] : '';
            $live_api_key = (isset($_POST['connect_co_live_api_key'])) ? $_POST['connect_co_live_api_key'] : '';
            $test_api_key = (isset($_POST['connect_co_test_api_key'])) ? $_POST['connect_co_test_api_key'] : '';
            $default_payment_type = (isset($_POST['connect_co_default_payment_type'])) ? $_POST['connect_co_default_payment_type'] : '';
            $average_package_size = (isset($_POST['connect_co_average_package_size'])) ? $_POST['connect_co_average_package_size'] : '';
            $average_weight_per_package = (isset($_POST['connect_co_average_weight_per_package'])) ? $_POST['connect_co_average_weight_per_package'] : '';
            $default_delivery_type = (isset($_POST['connect_co_default_delivery_type'])) ? $_POST['connect_co_default_delivery_type'] : '';


            if (empty($api_environment)) {
                update_option('connect_co_admin_notification',
                    json_encode(array('error', 'Please choose API environment', false)));
                $error = true;
            }
            if ($api_environment == 'live' && empty($live_api_key)) {
                update_option('connect_co_admin_notification',
                    json_encode(array('error', 'Connect Co. live API key can\'t be empty.', false)));
                $error = true;
            } else {
                $validate_live_api_key = $this->validate_api_key($api_environment, $live_api_key);
                if (!$validate_live_api_key) {
                    update_option('connect_co_admin_notification',
                        json_encode(array('error', 'Connect Co. live API key is invalid', false)));
                    $error = true;
                }
            }
            if ($api_environment == 'test' && empty($test_api_key)) {
                update_option('connect_co_admin_notification',
                    json_encode(array('error', 'Connect Co. test API key can\'t be empty.', false)));
                $error = true;
            } else {
                $validate_test_api_key = $this->validate_api_key($api_environment, $test_api_key);
                if (!$validate_test_api_key) {
                    update_option('connect_co_admin_notification',
                        json_encode(array('error', 'Connect Co. test API key is invalid.', false)));
                    $error = true;
                }
            }
            if ($average_weight_per_package == 0) {
                update_option('connect_co_admin_notification',
                    json_encode(array('error', 'Please set value to Average weight per package.', false)));
                $error = true;
            }


            if (!$error) {
                update_option('connect_co_api_environment', $api_environment);
                update_option('connect_co_live_api_key', $live_api_key);
                update_option('connect_co_test_api_key', $test_api_key);
                update_option('connect_co_default_payment_type', $default_payment_type);
                update_option('connect_co_average_package_size', $average_package_size);
                update_option('connect_co_average_weight_per_package', $average_weight_per_package);
                update_option('connect_co_default_delivery_type', $default_delivery_type);

                $this->set_connect_co_merchant_api_settings();
                update_option('connect_co_admin_notification',
                    json_encode(array('success', 'Settings saved.', true)));
            }
        }
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit();
    }

    public function send_api_request($url, $args)
    {
        $response = wp_remote_request($url, $args);

        if (is_wp_error($response)) {
            Connect_Co_Admin::write_log($response);
            return false;
        }
        if ($response['response']['code'] == 422) {
            $body = wp_remote_retrieve_body($response);
            return json_decode($body);
        }
        if ($response['response']['code'] != 200) {
            Connect_Co_Admin::write_log($response);
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $decode_response = json_decode($body);
        if ($decode_response->status != 'success') {
            Connect_Co_Admin::write_log($decode_response);
            return false;
        }
        return $decode_response;
    }

    public static function write_log($log)
    {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }

    public function get_plugin_config_settings()
    {
        $url = $this->connect_co_api;
        $url .= 'config/get/';
        $args = array('method' => 'GET',
            'headers' => array('Authorization' => 'Bearer ' . $this->connect_co_api_key)
        );
        $response = $this->send_api_request($url, $args);
        if ($response) {
            return $response->data;
        }
    }

    public function connect_co_settings_page_config()
    {
        $settings = $this->get_plugin_config_settings();

        if (empty($settings)) {
            return false;
        }

        return array('delivery_types' => $settings->delivery_types,
            'payment_types' => $settings->payment_types,
            'package_sizes' => $settings->package_sizes);
    }

    public function get_order_details_page_config_settings()
    {
        $url = $this->connect_co_api;
        $url .= 'config/get?cities=1';
        $args = array('method' => 'GET',
            'headers' => array('Authorization' => 'Bearer ' . $this->connect_co_api_key)
        );
        $response = $this->send_api_request($url, $args);
        if ($response) {
            return $response->data;
        }
    }

    public function get_merchant_pickup_locations()
    {
        $url = $this->connect_co_merchant_api;
        $url .= 'order/pickup-locations';
        $args = array('method' => 'GET',
            'headers' => array('Authorization' => 'Bearer ' . $this->connect_co_merchant_api_key)
        );
        $response = $this->send_api_request($url, $args);
        if ($response) {
            return $response->data;
        }
    }

    public function get_cities()
    {
        $url = $this->connect_co_api;
        $url .= 'cities/get';
        $args = array('method' => 'GET',
            'headers' => array('Authorization' => 'Bearer ' . $this->connect_co_api_key)
        );
        $response = $this->send_api_request($url, $args);
        if ($response) {
            return $response->data;
        }
    }

    public function get_city_by_id($city_id)
    {
        $cities = $this->get_cities();
        if ($cities) {
            foreach ($cities as $city) {
                if ($city->id == $city_id) {
                    return $city;
                }
            }
        }
    }

    public function get_pickup_location_by_id($location_id)
    {
        $pickup_locations = $this->get_merchant_pickup_locations();
        if ($pickup_locations) {
            foreach ($pickup_locations as $pickup_location) {
                if ($pickup_location->id == $location_id) {
                    return $pickup_location;
                }
            }
        }
    }

    public function connect_co_order_details_page_config($order)
    {
        $settings = $this->get_order_details_page_config_settings();
        $this->get_order_items($order->id);


        if (empty($settings)) {
            return false;
        }

        $package_sizes = $this->make_package_sizes_array($settings->package_sizes);
        $cites = $this->make_cities_array($settings->cities);
        $time_windows = $this->make_time_windows_array($settings->scheduled_delivery->time_windows);

        $delivery_city_availability = $this->delivery_city_availability_check($cites, $order);

        //**START SET CONNECT CO DELIVERY INFORMATION SECTION FROM FIELDS**//
        $pickup_location_field_options = $this->get_pickup_location_field_options($order);
        $package_weight_field_options = $this->get_package_weight_field_options($order);
        $notes_field_options = $this->get_notes_field_options($order);
        $payment_type_field_options = $this->get_payment_type_field_options($settings, $order);
        $delivery_type_field_options = $this->get_delivery_type_field_options($settings, $order);
        $package_size_field_options = $this->get_package_size_field_options($package_sizes, $order);
        $city_field_options = $this->get_city_field_options($cites, $order);
        $scheduled_date_field_options = $this->get_scheduled_date_field_options($order);
        $scheduled_time_window_field_options = $this->get_scheduled_time_window_field_options($time_windows, $order);
        //**END SET CONNECT CO DELIVERY INFORMATION SECTION FROM FIELDS**//

        $is_submitted = get_post_meta($order->get_id(), 'cc_submit', true);

        return array(
            'delivery_types' => $delivery_type_field_options,
            'payment_types' => $payment_type_field_options,
            'package_weight' => $package_weight_field_options,
            'package_sizes' => $package_size_field_options,
            'pickup_locations' => $pickup_location_field_options,
            'scheduled_date' => $scheduled_date_field_options,
            'time_window' => $scheduled_time_window_field_options,
            'notes' => $notes_field_options,
            'cities' => $city_field_options,
            'delivery_city_availability' => $delivery_city_availability,
            'is_submitted' => $is_submitted,
        );
    }


    /**
     * Set Custom Notifications
     *
     * #notifications[0] = (string) Type of notification: error, updated or update-nag
     * #notifications[1] = (string) Message
     * #notifications[2] = (boolean) is_dismissible?
     */
    public function connect_co_admin_notifications()
    {
        $screen = get_current_screen();

        if (!in_array($screen->id, array('settings_page_connect-co-admin', 'shop_order'))) return;

        $notifications = get_option('connect_co_admin_notification');

        if (!empty($notifications)) {

            $notifications = json_decode($notifications);
            $error_type = $notifications[0];
            $message = $notifications[1];
            $is_dismissible = (isset($notifications[2]) && $notifications[2] == true) ? 'is-dismissible' : "";

            switch ($error_type) {
                case 'error': # red
                case 'warning': # yellow
                case 'success': # green
                case 'info': # blue
                    $class = "notice-$error_type";
                    break;
                default:
                    # Defaults to error just in case
                    $class = 'error';
                    break;
            }

            require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/connect-co-admin-notices.php';

            # Let's reset the notification
            update_option('connect_co_admin_notification', false);
        }
    }

    /**
     * Callback function for WooCommerce order details Connect Co. form submit
     *
     * @since    1.0.0
     */
    public function submit_order_to_connect_co_ajx()
    {
        if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'connect_co_ajax_submit') &&
            $_POST['action'] == 'submit_order_to_connect_co') {

            $cc_pickup_location = (isset($_POST['cc_pickup_location'])) ? $_POST['cc_pickup_location'] : '';
            $cc_payment_type = (isset($_POST['cc_payment_type'])) ? $_POST['cc_payment_type'] : '';
            $cc_delivery_type = (isset($_POST['cc_delivery_type'])) ? $_POST['cc_delivery_type'] : '';
            $cc_package_weight = (isset($_POST['cc_package_weight'])) ? $_POST['cc_package_weight'] : '';
            $cc_package_size = (isset($_POST['cc_package_size'])) ? $_POST['cc_package_size'] : '';
            $cc_notes = (isset($_POST['cc_notes'])) ? $_POST['cc_notes'] : '';
            $cc_scheduled_date = (isset($_POST['cc_scheduled_date'])) ? $_POST['cc_scheduled_date'] : '';
            $cc_time_window = (isset($_POST['cc_time_window'])) ? $_POST['cc_time_window'] : '';
            $cc_city = (isset($_POST['cc_city'])) ? $_POST['cc_city'] : '';

            $order_id = (isset($_POST['order_id'])) ? $_POST['order_id'] : '';

            if ($cc_delivery_type == 2 || $cc_delivery_type == 3) {//Same-day, Scheduled
                if (empty($cc_scheduled_date)) {
                    $data = array(
                        'status' => 'error',
                        'message' => array('Required fields can not be empty')
                    );
                }
                if ($cc_delivery_type == 3) {//Scheduled
                    if (empty($cc_time_window)) {
                        $data = array(
                            'status' => 'error',
                            'message' => array('Required fields can not be empty')
                        );
                    }
                }
                $result = json_encode($data);
                echo $result;
                exit();
            }

            if (!empty($cc_pickup_location) && !empty($cc_payment_type) && !empty($cc_delivery_type) &&
                !empty($cc_package_weight) && !empty($cc_package_size) && !empty($cc_city) && !empty($order_id)
            ) {


                $order = wc_get_order($order_id);
                $shipping_first_name = $order->get_shipping_first_name();
                $shipping_last_name = $order->get_shipping_last_name();
                $billing_phone = $order->get_billing_phone();

                $billing_email = $order->get_billing_email();
                $shipping_address = $order->get_address('shipping');

                $city = $this->get_city_by_id($cc_city);
                $city_name = '';
                if ($city) {
                    $city_name = $city->city_name;
                }

                $location = $this->get_pickup_location_by_id($cc_pickup_location);
                $latitude = '';
                $longitude = '';
                $pickup_location = '';

                $order_items = $this->get_order_items($order_id);

                if ($location) {
                    $latitude = $location->latitude;
                    $longitude = $location->longitude;
                    $pickup_location = $location->address;
                }

                if ($cc_delivery_type == 1) {
                    $cc_scheduled_date = '';
                    $cc_time_window = '';
                }

                $args = array(
                    "order_reference" => $order_id,
                    "pickup_location" => $pickup_location,
                    "pickup_lat" => $latitude,
                    "pickup_lng" => $longitude,
                    "customer_name" => $shipping_first_name . ' ' . $shipping_last_name,
                    "customer_email" => $billing_email,
                    "delivery_location" => implode(', ', $shipping_address),
                    "nearest_delivery_location" => $city_name,
                    "contact_1" => $billing_phone,
                    "contact_2" => "",
                    "location_url" => "",
                    "payment_type" => $cc_payment_type,
                    "amount_to_be_collected" => 100,
                    "package_weight" => $cc_package_weight,
                    "package_size" => $cc_package_size,
                    "delivery_type" => $cc_delivery_type,
                    "scheduled_date" => $cc_scheduled_date,
                    "scheduled_tw" => $cc_time_window,
                    "notes" => $cc_notes,
                    "order_items" => $order_items,
                    "provider" => "W"
                );


                $response = $this->create_connect_co_order($args);

                if ($response) {
                    if (isset($response->status) && $response->status == 'success') {
                        if (!add_post_meta($order_id, 'cc_pickup_location', $cc_pickup_location, true)) {
                            update_post_meta($order_id, 'cc_pickup_location', $cc_pickup_location);
                        }
                        if (!add_post_meta($order_id, 'cc_payment_type', $cc_payment_type, true)) {
                            update_post_meta($order_id, 'cc_payment_type', $cc_payment_type);
                        }
                        if (!add_post_meta($order_id, 'cc_delivery_type', $cc_delivery_type, true)) {
                            update_post_meta($order_id, 'cc_delivery_type', $cc_delivery_type);
                        }
                        if (!add_post_meta($order_id, 'cc_package_weight', $cc_package_weight, true)) {
                            update_post_meta($order_id, 'cc_package_weight', $cc_package_weight);
                        }
                        if (!add_post_meta($order_id, 'cc_package_size', $cc_package_size, true)) {
                            update_post_meta($order_id, 'cc_package_size', $cc_package_size);
                        }
                        if (!add_post_meta($order_id, 'cc_notes', $cc_notes, true)) {
                            update_post_meta($order_id, 'cc_notes', $cc_notes);
                        }
                        if (!add_post_meta($order_id, 'cc_city', $cc_city, true)) {
                            update_post_meta($order_id, 'cc_city', $cc_city);
                        }
                        if (!add_post_meta($order_id, 'cc_scheduled_date', $cc_scheduled_date, true)) {
                            update_post_meta($order_id, 'cc_scheduled_date', $cc_scheduled_date);
                        }
                        if (!add_post_meta($order_id, 'cc_time_window', $cc_time_window, true)) {
                            update_post_meta($order_id, 'cc_time_window', $cc_time_window);
                        }
                        if (!add_post_meta($order_id, 'cc_submit', true, true)) {
                            update_post_meta($order_id, 'cc_submit', true);
                        }
                        if (isset($response->order_tracking_link)) {
                            if (!add_post_meta($order_id, 'cc_order_tracking_link', $response->order_tracking_link, true)) {
                                update_post_meta($order_id, 'cc_order_tracking_link', true);
                            }
                        }
                        $data = array(
                            'status' => 'success',
                            'message' => array('Order successfully submitted')
                        );
                    } else {

                        if ($response == !null) {
                            $error_text = '<ul class="connect-co-error-items">';
                            foreach ($response as $errors) {
                                $error_text .= '<li> - ' . $errors[0] . '</li>';
                            }
                            $error_text .= '</ul>';
                            $data = array(
                                'status' => 'error',
                                'message' => array($error_text)
                            );
                        }
                    }
                }

            } else {
                $data = array(
                    'status' => 'error',
                    'message' => array('Required fields can not be empty')
                );
            }
            $result = json_encode($data);
            echo $result;
        }
        exit();
    }


    public function calculate_connect_co_delivery_cost_ajx()
    {
        if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'connect_co_ajax_submit') &&
            $_POST['action'] == 'calculate_connect_co_delivery_cost') {

            $cc_delivery_type = (isset($_POST['cc_delivery_type'])) ? $_POST['cc_delivery_type'] : '';
            $cc_package_weight = (isset($_POST['cc_package_weight'])) ? $_POST['cc_package_weight'] : '';
            $cc_city = (isset($_POST['cc_city'])) ? $_POST['cc_city'] : '';

            if (!empty($cc_delivery_type) && !empty($cc_package_weight) && !empty($cc_city)) {

                $args = array(
                    "delivery_type" => $cc_delivery_type,
                    "package_weight" => $cc_package_weight,
                    "city_id" => $cc_city
                );

                $delivery_cost = $this->get_delivery_cost($args);

                if ($delivery_cost) {
                    $data = array(
                        'status' => 'success',
                        'message' => number_format($delivery_cost, 2)
                    );
                } else {
                    $data = array(
                        'status' => 'error',
                        'message' => 'something went wrong while calculating delivery cost'
                    );
                }
            } else {
                $data = array(
                    'status' => 'error',
                    'message' => array('Required fields can not be empty')
                );
            }
            $result = json_encode($data);
            echo $result;
        }
        exit();
    }

    public function check_cash_on_delivery_availability_ajx()
    {
        if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'connect_co_ajax_submit') &&
            $_POST['action'] == 'check_cash_on_delivery_availability') {

            $cc_city = (isset($_POST['cc_city'])) ? $_POST['cc_city'] : '';

            if (!empty($cc_city)) {

                $availability_status = $this->get_cash_on_delivery_availability_status($cc_city);

                if ($availability_status) {
                    $data = array(
                        'status' => 'success',
                        'message' => $availability_status
                    );
                } else {
                    $data = array(
                        'status' => 'error',
                        'message' => 'Cash on delivery is not available for selected city'
                    );
                }
            } else {
                $data = array(
                    'status' => 'error',
                    'message' => array('Required fields can not be empty')
                );
            }
            $result = json_encode($data);
            echo $result;
        }
        exit();
    }

    public function get_cash_on_delivery_availability_status($city_id)
    {
        $body_args = array('nearest_delivery_location_id' => $city_id);

        $url = $this->connect_co_merchant_api;
        $url .= 'order/cod/availability';
        $args = array('method' => 'POST',
            'headers' => array('Authorization' => 'Bearer ' . $this->connect_co_merchant_api_key),
            'body' => $body_args
        );

        $response = $this->send_api_request($url, $args);

        if ($response) {
            return $response->availability;
        }
    }


    public function check_delivery_methods_availability_ajx()
    {
        if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'connect_co_ajax_submit') &&
            $_POST['action'] == 'check_delivery_methods_availability') {

            $cc_delivery_type = (isset($_POST['cc_delivery_type'])) ? $_POST['cc_delivery_type'] : '';
            $cc_city = (isset($_POST['cc_city'])) ? $_POST['cc_city'] : '';

            if (!empty($cc_delivery_type) && !empty($cc_city)) {

                $args = array(
                    "delivery_type" => $cc_delivery_type,
                    "nearest_delivery_location_id" => $cc_city
                );

                $payment_methods_availability = $this->get_delivery_methods_availability($args);

                if ($payment_methods_availability) {
                    $data = array(
                        'status' => 'success',
                        'message' => $payment_methods_availability
                    );
                } else {
                    $data = array(
                        'status' => 'error',
                        'message' => 'Selected delivery method is not available for selected city'
                    );
                }
            } else {
                $data = array(
                    'status' => 'error',
                    'message' => array('Required fields can not be empty')
                );
            }
            $result = json_encode($data);
            echo $result;
        }
        exit();
    }


    public function get_delivery_methods_availability($delivery_methods_args)
    {
        $url = $this->connect_co_merchant_api;
        $url .= 'order/delivery/availability';
        $args = array('method' => 'POST',
            'headers' => array('Authorization' => 'Bearer ' . $this->connect_co_merchant_api_key),
            'body' => $delivery_methods_args
        );
        $response = $this->send_api_request($url, $args);

        if ($response) {
            return $response->availability;
        }
    }


    public function get_delivery_cost($cost_args)
    {
        $url = $this->connect_co_merchant_api;
        $url .= 'order/cost';
        $args = array('method' => 'POST',
            'headers' => array('Authorization' => 'Bearer ' . $this->connect_co_merchant_api_key),
            'body' => $cost_args
        );
        $response = $this->send_api_request($url, $args);

        if ($response) {
            return $response->data;
        }
    }


    public function create_connect_co_order($order_values)
    {
        $url = $this->connect_co_merchant_api;
        $url .= 'order/create';
        $args = array('method' => 'POST',
            'headers' => array('Authorization' => 'Bearer ' . $this->connect_co_merchant_api_key),
            'body' => $order_values
        );
        $response = $this->send_api_request($url, $args);

        if ($response) {
            return $response;
        }
    }

    /**
     * @param $package_sizes
     * @return array
     */
    private function make_package_sizes_array($package_sizes)
    {
        $package_sizes = (array)$package_sizes;
        array_walk($package_sizes, function (&$val, $key) {
            $val = $val . " ($key)";
        });

        return $package_sizes;
    }

    /**
     * @param $pickup_locations
     * @return array
     */
    private function make_pickup_locations_array($pickup_locations)
    {
        $pickup_locations_array = array();
        $primary_location_id = '';
        foreach ($pickup_locations as $pickup_location) {
            $pickup_locations_array[$pickup_location->id] = $pickup_location->address;
            if ($pickup_location->type) {
                $primary_location_id = $pickup_location->id;
            };
        }
        return array('pickup_locations' => $pickup_locations_array, 'primary_location_id' => $primary_location_id);
    }

    /**
     * @param $cites
     * @return array
     */
    private function make_cities_array($cites): array
    {
        $cites = (array)$cites;
        $cites_array = array('' => '- select a city -');
        foreach ($cites as $city) {
            $cites_array[$city->id] = $city->city_name;
        }
        return $cites_array;
    }

    private function make_time_windows_array($time_windows)
    {
        $time_windows = (array)$time_windows;
        $time_windows_array = array('' => '- select a time window -');
        foreach ($time_windows as $key => $time_window) {
            $time_windows_array[$key] = $time_window->label;
        }
        return $time_windows_array;
    }

    /**
     * @param $order
     * @return array
     */
    private function get_package_weight_field_options($order): array
    {
        //Package Weight Field Options
        $connect_co_order_package_weight = get_post_meta($order->get_id(), 'cc_package_weight', true);
        $package_weight = $this->get_order_weight($order->get_id());
        $selected_package_weight = (!empty($connect_co_order_package_weight)) ? $connect_co_order_package_weight : $package_weight;
        $package_weight_field_options = array(
            'type' => 'number',
            'id' => 'cc_package_weight',
            'label' => 'Package weight:',
            'value' => $selected_package_weight,
            'wrapper_class' => 'form-field-wide',
            'custom_attributes' => array('required' => true, 'min' => 0, 'step' => '0.01'),
            'cols' => 2,
            'rows' => 2,
            'class' => 'cc-check-delivery-cost'
        );
        return $package_weight_field_options;
    }


    /**
     * @param $order
     * @return array
     */
    private function get_scheduled_date_field_options($order): array
    {
        //Scheduled date Field Options
        $connect_co_order_scheduled_date = get_post_meta($order->get_id(), 'cc_scheduled_date', true);
        $selected_scheduled_date = (!empty($connect_co_order_scheduled_date)) ? $connect_co_order_scheduled_date : date('Y-m-d');
        $scheduled_date_field_options = array(
            'type' => 'text',
            'id' => 'cc_scheduled_date',
            'label' => 'Scheduled Date:',
            'value' => $selected_scheduled_date,
            'wrapper_class' => 'form-field-wide',
            'custom_attributes' => array(),
        );
        return $scheduled_date_field_options;
    }


    /**
     * @param $settings
     * @param $order
     * @return array
     */
    private function get_payment_type_field_options($settings, $order): array
    {
        //Payment Type Field Options
        $connect_co_order_payment_type = get_post_meta($order->get_id(), 'cc_payment_type', true);
        $order_payment_type = ($order->get_payment_method() == 'cod') ? '2' : '';
        $selected_payment_type = (!empty($connect_co_order_payment_type)) ? $connect_co_order_payment_type : $order_payment_type;
        $payment_type_field_options = array(
            'id' => 'cc_payment_type',
            'label' => 'Payment type:',
            'selected' => true,
            'value' => $selected_payment_type,
            'options' => (array)$settings->payment_types,
            'wrapper_class' => 'form-field-wide',
            'required' => true,
        );
        return $payment_type_field_options;
    }

    /**
     * @param $order
     * @return array
     */
    private function get_pickup_location_field_options($order): array
    {
        //Pickup Location Field Options
        $pickup_locations = $this->get_merchant_pickup_locations();

        if (!empty($pickup_locations)) {
            $pickup_location_data = $this->make_pickup_locations_array($pickup_locations);
        }

        $connect_co_order_pickup_location = get_post_meta($order->get_id(), 'cc_pickup_location', true);
        $primary_location_id = $pickup_location_data['primary_location_id'];
        $selected_pickup_location = (!empty($connect_co_order_pickup_location)) ? $connect_co_order_pickup_location : $primary_location_id;
        $pickup_location_field_options = array(
            'id' => 'cc_pickup_location',
            'label' => 'Pickup location:',
            'selected' => true,
            'value' => $selected_pickup_location,
            'options' => $pickup_location_data['pickup_locations'],
            'wrapper_class' => 'form-field-wide',
            'required' => true,
        );
        return $pickup_location_field_options;
    }

    /**
     * @param array $package_sizes
     * @param $order
     * @return array
     */
    private function get_package_size_field_options(array $package_sizes, $order): array
    {
        //Package Size Field Options
        $connect_co_order_package_size = get_post_meta($order->get_id(), 'cc_package_size', true);
        $connect_co_settings_package_size = get_option('connect_co_average_package_size');
        $selected_package_size = (!empty($connect_co_order_package_size)) ? $connect_co_order_package_size : $connect_co_settings_package_size;
        $package_size_field_options = array(
            'id' => 'cc_package_size',
            'label' => 'Package size:',
            'selected' => true,
            'value' => $selected_package_size,
            'options' => $package_sizes,
            'wrapper_class' => 'form-field-wide',
            'required' => true,
        );
        return $package_size_field_options;
    }

    /**
     * @param $order
     * @return array
     */
    private function get_notes_field_options($order): array
    {
        //Notes Field Options
        $connect_co_order_notes = get_post_meta($order->get_id(), 'cc_notes', true);
        $notes_field_options = array(
            'id' => 'cc_notes',
            'label' => 'Notes:',
            'value' => $connect_co_order_notes,
            'wrapper_class' => 'form-field-wide'
        );
        return $notes_field_options;
    }

    /**
     * @param array $cites
     * @param $order
     * @return array
     */
    private function get_city_field_options(array $cites, $order): array
    {
        //City Field Options
        $connect_co_order_city = get_post_meta($order->get_id(), 'cc_city', true);
        $connect_co_delivery_city = $this->delivery_city_availability_check($cites, $order);
        $selected_city = (!empty($connect_co_order_city)) ? $connect_co_order_city : $connect_co_delivery_city;

        $city_field_options = array(
            'id' => 'cc_city',
            'label' => 'City:',
            'selected' => true,
            'value' => $selected_city,
            'options' => $cites,
            'wrapper_class' => 'form-field-wide',
            'required' => true,
            'description' => 'If location is not found, please choose the nearest city',
            'class' => 'cc-check-delivery-cost'
        );
        return $city_field_options;
    }

    /**
     * @param $settings
     * @param $order
     * @return array
     */
    private function get_delivery_type_field_options($settings, $order): array
    {
        //Payment Type Field Options
        $connect_co_order_delivery_type = get_post_meta($order->get_id(), 'cc_delivery_type', true);
        $connect_co_settings_delivery_type = get_option('connect_co_default_delivery_type');
        $selected_delivery_type = (!empty($connect_co_order_delivery_type)) ? $connect_co_order_delivery_type : $connect_co_settings_delivery_type;
        $payment_type_field_options = array(
            'id' => 'cc_delivery_type',
            'label' => 'Delivery type:',
            'selected' => true,
            'value' => $selected_delivery_type,
            'options' => (array)$settings->delivery_types,
            'wrapper_class' => 'form-field-wide',
            'required' => true,
            'class' => 'cc-check-delivery-cost'
        );
        return $payment_type_field_options;
    }


    /**
     * @param $time_windows
     * @param $order
     * @return array
     */
    private function get_scheduled_time_window_field_options($time_windows, $order): array
    {
        //Time window Field Options
        $connect_co_order_time_window = get_post_meta($order->get_id(), 'cc_time_window', true);
        $selected_time_window = (!empty($connect_co_order_time_window)) ? $connect_co_order_time_window : '';
        $time_windows_field_options = array(
            'id' => 'cc_time_window',
            'label' => 'Scheduled Time Window :',
            'selected' => true,
            'value' => $selected_time_window,
            'options' => $time_windows,
            'wrapper_class' => 'form-field-wide',
            'required' => true,
        );
        return $time_windows_field_options;
    }


    public function delivery_city_availability_check(array $cites, $order)
    {
        $order_shipping_city = $order->get_shipping_city();
        return array_search($order_shipping_city, $cites);
    }

    public function get_order_items($order_id)
    {
        $order = wc_get_order($order_id);
        $order_items = array();
        $total_package_weight = 0;
        foreach ($order->get_items() as $item_key => $item) {
            $product = $item->get_product();

            $item_id = $item->get_id();
            $item_name = $item->get_name();
            $quantity = $item->get_quantity();
            $item_weight = $product->get_weight();

            $product_type = $product->get_type();
            $product_sku = $product->get_sku();
            $product_price = $product->get_price();

            $total_package_weight += $item_weight;

            $order_items[] = array('sku' => $product_sku,
                'item_name' => $item_name,
                'qty' => $quantity);

        }
        return $order_items;
    }

    public function get_order_weight($order_id)
    {
        $order = wc_get_order($order_id);
        $total_package_weight = 0;
        foreach ($order->get_items() as $item_key => $item) {
            $product = $item->get_product();
            $quantity = $item->get_quantity();
            $item_weight = $product->get_weight();
            $total_package_weight += $item_weight * $quantity;
        }
        return $total_package_weight;
    }

    public function validate_api_key($api_environment, $api_key)
    {
        if (!empty($api_key) && !empty($api_environment)) {
            $url = self::API_URL[$api_environment];
            $url .= 'key-validate';
            $args = array('method' => 'POST',
                'headers' => array('Authorization' => 'Bearer ' . $api_key),
            );
            $response = $this->send_api_request($url, $args);

            if ($response->status == 'success') {
                return true;
            }
        }
        return false;
    }

    function set_custom_edit_shop_order_columns($columns)
    {
        $columns['connect_co_status'] = __('Connect Co. Status', 'connect-co');
        return $columns;
    }

    function custom_shop_order_column($column, $post_id)
    {
        if ($column == 'connect_co_status') {
            $is_submitted = get_post_meta($post_id, 'cc_submit', true);
            if ($is_submitted == '1') {
                echo "<span class='cc-list-view-submit'>" . __('Submitted', 'connect-co') . "</span>";
            } else {
                echo "<span class='cc-list-view-pending'>" . __('Pending ', 'connect-co') . "</span>";
            }
        }
    }

}
