<?php
/**
 * Plugin Name: One-Click Maintenance Mode
 * Plugin URI: https://github.com/yourusername/one-click-maintenance
 * Description: Easily activate a maintenance mode screen with one click, customizable content, and modern clean design.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: one-click-maintenance
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('OCM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('OCM_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('OCM_PLUGIN_VERSION', '1.0.0');

/**
 * Main Plugin Class
 */
class OneClickMaintenance {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    /**
     * Initialize the plugin
     */
    public function init() {
        // Load text domain for translations
        load_plugin_textdomain('one-click-maintenance', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Initialize admin functionality
        if (is_admin()) {
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_init', array($this, 'register_settings'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        }

        // Add AJAX handler for disabling maintenance mode
        add_action('wp_ajax_ocm_disable_maintenance', array($this, 'ajax_disable_maintenance'));
        add_action('wp_ajax_nopriv_ocm_disable_maintenance', array($this, 'ajax_disable_maintenance'));
        
        // Check if maintenance mode is enabled
        if (get_option('ocm_maintenance_enabled', false)) {
            add_action('template_redirect', array($this, 'show_maintenance_page'));
        }
        
        // Enqueue frontend styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Set default options
        $default_options = array(
            'ocm_maintenance_enabled' => false,
            'ocm_title' => __('We\'re Under Maintenance', 'one-click-maintenance'),
            'ocm_description' => __('We are currently performing scheduled maintenance. We will be back online shortly.', 'one-click-maintenance'),
            'ocm_font_family' => 'DM Sans',
            'ocm_font_size' => 'medium',
            'ocm_background_type' => 'color',
            'ocm_background_color' => '#f8f9fa',
            'ocm_background_image' => '',
            'ocm_logo' => '',
            'ocm_countdown_enabled' => false,
            'ocm_countdown_date' => '',
            'ocm_footer_message' => __('For urgent matters, please contact us at support@yoursite.com', 'one-click-maintenance'),
            'ocm_custom_css' => ''
        );
        
        foreach ($default_options as $option => $value) {
            if (get_option($option) === false) {
                add_option($option, $value);
            }
        }
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Disable maintenance mode on deactivation
        update_option('ocm_maintenance_enabled', false);
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            __('Maintenance Mode', 'one-click-maintenance'),
            __('Maintenance Mode', 'one-click-maintenance'),
            'manage_options',
            'one-click-maintenance',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        $settings = array(
            'ocm_maintenance_enabled',
            'ocm_title',
            'ocm_description',
            'ocm_font_family',
            'ocm_font_size',
            'ocm_background_type',
            'ocm_background_color',
            'ocm_background_image',
            'ocm_logo',
            'ocm_countdown_enabled',
            'ocm_countdown_date',
            'ocm_footer_message',
            'ocm_custom_css'
        );
        
        foreach ($settings as $setting) {
            register_setting('ocm_settings', $setting);
        }
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'settings_page_one-click-maintenance') {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('wp-color-picker');
        
        wp_enqueue_script(
            'ocm-admin-js',
            OCM_PLUGIN_URL . 'js/admin.js',
            array('jquery', 'wp-color-picker'),
            OCM_PLUGIN_VERSION,
            true
        );
        
        wp_enqueue_style(
            'ocm-admin-css',
            OCM_PLUGIN_URL . 'css/admin.css',
            array(),
            OCM_PLUGIN_VERSION
        );
    }
    
    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_frontend_scripts() {
        if (get_option('ocm_maintenance_enabled', false) && !is_user_logged_in()) {
            wp_enqueue_style(
                'ocm-frontend-css',
                OCM_PLUGIN_URL . 'css/style.css',
                array(),
                OCM_PLUGIN_VERSION
            );
            
            // Enqueue Google Fonts
            $font_family = get_option('ocm_font_family', 'DM Sans');
            if ($font_family !== 'inherit') {
                $font_url = 'https://fonts.googleapis.com/css2?family=' . urlencode($font_family) . ':wght@300;400;500;600;700&display=swap';
                wp_enqueue_style('ocm-google-fonts', $font_url, array(), null);
            }
            
            // Enqueue countdown script if enabled
            if (get_option('ocm_countdown_enabled', false)) {
                wp_enqueue_script(
                    'ocm-countdown-js',
                    OCM_PLUGIN_URL . 'js/countdown.js',
                    array('jquery'),
                    OCM_PLUGIN_VERSION,
                    true
                );
                
                wp_localize_script('ocm-countdown-js', 'ocm_countdown', array(
                    'date' => get_option('ocm_countdown_date', ''),
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('ocm_disable_maintenance'),
                    'labels' => array(
                        'days' => __('Days', 'one-click-maintenance'),
                        'hours' => __('Hours', 'one-click-maintenance'),
                        'minutes' => __('Minutes', 'one-click-maintenance'),
                        'seconds' => __('Seconds', 'one-click-maintenance')
                    )
                ));
            }
        }
    }
    
    /**
     * Show maintenance page
     */
    public function show_maintenance_page() {
        // Allow logged-in administrators to access the site
        if (current_user_can('manage_options')) {
            return;
        }
        
        // Set 503 header for SEO
        status_header(503);
        nocache_headers();
        
        // Load maintenance page template
        include OCM_PLUGIN_PATH . 'templates/maintenance-page.php';
        exit;
    }
    
    /**
     * Admin page content
     */
    public function admin_page() {
        include OCM_PLUGIN_PATH . 'templates/admin-page.php';
    }

    /**
     * AJAX handler to disable maintenance mode when countdown ends
     */
    public function ajax_disable_maintenance() {
        // Verify nonce for security
        if (!wp_verify_nonce($_POST['nonce'], 'ocm_disable_maintenance')) {
            wp_die('Security check failed');
        }

        // Disable maintenance mode
        update_option('ocm_maintenance_enabled', false);

        // Send success response
        wp_send_json_success(array(
            'message' => __('Maintenance mode disabled successfully', 'one-click-maintenance')
        ));
    }
}

// Initialize the plugin
new OneClickMaintenance();
