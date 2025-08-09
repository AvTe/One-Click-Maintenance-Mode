<?php
/**
 * Admin Settings Page Template
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Handle form submission
if (isset($_POST['submit']) && wp_verify_nonce($_POST['ocm_nonce'], 'ocm_save_settings')) {
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
        if (isset($_POST[$setting])) {
            $value = sanitize_text_field($_POST[$setting]);
            if ($setting === 'ocm_description' || $setting === 'ocm_footer_message' || $setting === 'ocm_custom_css') {
                $value = wp_kses_post($_POST[$setting]);
            }
            update_option($setting, $value);
        } else {
            // Handle checkboxes
            if ($setting === 'ocm_maintenance_enabled' || $setting === 'ocm_countdown_enabled') {
                update_option($setting, false);
            }
        }
    }
    
    echo '<div class="notice notice-success"><p>' . __('Settings saved successfully!', 'one-click-maintenance') . '</p></div>';
}

// Get current settings
$maintenance_enabled = get_option('ocm_maintenance_enabled', false);
$title = get_option('ocm_title', '');
$description = get_option('ocm_description', '');
$font_family = get_option('ocm_font_family', 'DM Sans');
$font_size = get_option('ocm_font_size', 'medium');
$background_type = get_option('ocm_background_type', 'color');
$background_color = get_option('ocm_background_color', '#f8f9fa');
$background_image = get_option('ocm_background_image', '');
$logo = get_option('ocm_logo', '');
$countdown_enabled = get_option('ocm_countdown_enabled', false);
$countdown_date = get_option('ocm_countdown_date', '');
$footer_message = get_option('ocm_footer_message', '');
$custom_css = get_option('ocm_custom_css', '');
?>

<div class="wrap ocm-admin-container">
    <div class="ocm-admin-header">
        <h1><?php _e('One-Click Maintenance Mode', 'one-click-maintenance'); ?></h1>
        <p><?php _e('Easily activate a maintenance mode screen with one click, customizable content, and modern clean design.', 'one-click-maintenance'); ?></p>
        
        <div class="ocm-status-indicator <?php echo $maintenance_enabled ? 'ocm-status-active' : 'ocm-status-inactive'; ?>">
            <span class="ocm-status-dot"></span>
            <?php echo $maintenance_enabled ? __('Maintenance Mode Active', 'one-click-maintenance') : __('Maintenance Mode Inactive', 'one-click-maintenance'); ?>
        </div>
    </div>
    
    <div class="ocm-admin-content">
        <div class="ocm-settings-panel">
            <form method="post" action="">
                <?php wp_nonce_field('ocm_save_settings', 'ocm_nonce'); ?>
                
                <!-- Main Settings -->
                <div class="ocm-section">
                    <h2 class="ocm-section-title"><?php _e('Main Settings', 'one-click-maintenance'); ?></h2>
                    
                    <div class="ocm-field-group">
                        <label for="ocm_maintenance_enabled">
                            <?php _e('Enable Maintenance Mode', 'one-click-maintenance'); ?>
                        </label>
                        <label class="ocm-toggle-switch">
                            <input type="checkbox" id="ocm_maintenance_enabled" name="ocm_maintenance_enabled" value="1" <?php checked($maintenance_enabled); ?>>
                            <span class="ocm-toggle-slider"></span>
                        </label>
                        <p class="ocm-field-description"><?php _e('When enabled, non-logged-in users will see the maintenance page.', 'one-click-maintenance'); ?></p>
                    </div>
                </div>
                
                <!-- Content Settings -->
                <div class="ocm-section">
                    <h2 class="ocm-section-title"><?php _e('Content Settings', 'one-click-maintenance'); ?></h2>
                    
                    <div class="ocm-field-group">
                        <label for="ocm_title"><?php _e('Title', 'one-click-maintenance'); ?></label>
                        <input type="text" id="ocm_title" name="ocm_title" value="<?php echo esc_attr($title); ?>" placeholder="<?php _e('We\'re Under Maintenance', 'one-click-maintenance'); ?>">
                    </div>
                    
                    <div class="ocm-field-group">
                        <label for="ocm_description"><?php _e('Description', 'one-click-maintenance'); ?></label>
                        <textarea id="ocm_description" name="ocm_description" placeholder="<?php _e('We are currently performing scheduled maintenance...', 'one-click-maintenance'); ?>"><?php echo esc_textarea($description); ?></textarea>
                    </div>
                    
                    <div class="ocm-field-group">
                        <label for="ocm_footer_message"><?php _e('Footer Message', 'one-click-maintenance'); ?></label>
                        <textarea id="ocm_footer_message" name="ocm_footer_message" placeholder="<?php _e('For urgent matters, please contact us...', 'one-click-maintenance'); ?>"><?php echo esc_textarea($footer_message); ?></textarea>
                    </div>
                </div>
                
                <!-- Design Settings -->
                <div class="ocm-section">
                    <h2 class="ocm-section-title"><?php _e('Design Settings', 'one-click-maintenance'); ?></h2>
                    
                    <div class="ocm-field-group">
                        <label for="ocm_font_family"><?php _e('Font Family', 'one-click-maintenance'); ?></label>
                        <select id="ocm_font_family" name="ocm_font_family">
                            <option value="DM Sans" <?php selected($font_family, 'DM Sans'); ?>>DM Sans</option>
                            <option value="Inter" <?php selected($font_family, 'Inter'); ?>>Inter</option>
                            <option value="Roboto" <?php selected($font_family, 'Roboto'); ?>>Roboto</option>
                            <option value="Open Sans" <?php selected($font_family, 'Open Sans'); ?>>Open Sans</option>
                            <option value="Lato" <?php selected($font_family, 'Lato'); ?>>Lato</option>
                            <option value="Poppins" <?php selected($font_family, 'Poppins'); ?>>Poppins</option>
                            <option value="inherit" <?php selected($font_family, 'inherit'); ?>><?php _e('Use Theme Font', 'one-click-maintenance'); ?></option>
                        </select>
                    </div>
                    
                    <div class="ocm-field-group">
                        <label><?php _e('Font Size', 'one-click-maintenance'); ?></label>
                        <div class="ocm-radio-group">
                            <label class="ocm-radio-option">
                                <input type="radio" name="ocm_font_size" value="small" <?php checked($font_size, 'small'); ?>>
                                <?php _e('Small', 'one-click-maintenance'); ?>
                            </label>
                            <label class="ocm-radio-option">
                                <input type="radio" name="ocm_font_size" value="medium" <?php checked($font_size, 'medium'); ?>>
                                <?php _e('Medium', 'one-click-maintenance'); ?>
                            </label>
                            <label class="ocm-radio-option">
                                <input type="radio" name="ocm_font_size" value="large" <?php checked($font_size, 'large'); ?>>
                                <?php _e('Large', 'one-click-maintenance'); ?>
                            </label>
                        </div>
                    </div>
                    
                    <div class="ocm-field-group">
                        <label><?php _e('Background Type', 'one-click-maintenance'); ?></label>
                        <div class="ocm-radio-group">
                            <label class="ocm-radio-option">
                                <input type="radio" name="ocm_background_type" value="color" <?php checked($background_type, 'color'); ?>>
                                <?php _e('Color', 'one-click-maintenance'); ?>
                            </label>
                            <label class="ocm-radio-option">
                                <input type="radio" name="ocm_background_type" value="image" <?php checked($background_type, 'image'); ?>>
                                <?php _e('Image', 'one-click-maintenance'); ?>
                            </label>
                        </div>
                    </div>
                    
                    <div class="ocm-field-group" id="ocm-background-color-field">
                        <label for="ocm_background_color"><?php _e('Background Color', 'one-click-maintenance'); ?></label>
                        <div class="ocm-color-picker-wrapper">
                            <input type="text" id="ocm_background_color" name="ocm_background_color" value="<?php echo esc_attr($background_color); ?>" class="ocm-color-picker">
                        </div>
                    </div>
                    
                    <div class="ocm-field-group" id="ocm-background-image-field">
                        <label for="ocm_background_image"><?php _e('Background Image', 'one-click-maintenance'); ?></label>
                        <input type="hidden" id="ocm_background_image" name="ocm_background_image" value="<?php echo esc_attr($background_image); ?>">
                        <button type="button" class="ocm-upload-button" id="ocm-upload-bg"><?php _e('Choose Image', 'one-click-maintenance'); ?></button>
                        <button type="button" class="ocm-remove-button" id="ocm-remove-bg" style="<?php echo empty($background_image) ? 'display:none;' : ''; ?>"><?php _e('Remove', 'one-click-maintenance'); ?></button>
                        <?php if (!empty($background_image)): ?>
                            <img src="<?php echo esc_url($background_image); ?>" class="ocm-image-preview" id="ocm-bg-preview">
                        <?php endif; ?>
                    </div>
                    
                    <div class="ocm-field-group">
                        <label for="ocm_logo"><?php _e('Logo', 'one-click-maintenance'); ?></label>
                        <input type="hidden" id="ocm_logo" name="ocm_logo" value="<?php echo esc_attr($logo); ?>">
                        <button type="button" class="ocm-upload-button" id="ocm-upload-logo"><?php _e('Choose Logo', 'one-click-maintenance'); ?></button>
                        <button type="button" class="ocm-remove-button" id="ocm-remove-logo" style="<?php echo empty($logo) ? 'display:none;' : ''; ?>"><?php _e('Remove', 'one-click-maintenance'); ?></button>
                        <?php if (!empty($logo)): ?>
                            <img src="<?php echo esc_url($logo); ?>" class="ocm-image-preview" id="ocm-logo-preview">
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Countdown Settings -->
                <div class="ocm-section">
                    <h2 class="ocm-section-title"><?php _e('Countdown Timer', 'one-click-maintenance'); ?></h2>
                    
                    <div class="ocm-field-group">
                        <label for="ocm_countdown_enabled">
                            <?php _e('Enable Countdown Timer', 'one-click-maintenance'); ?>
                        </label>
                        <label class="ocm-toggle-switch">
                            <input type="checkbox" id="ocm_countdown_enabled" name="ocm_countdown_enabled" value="1" <?php checked($countdown_enabled); ?>>
                            <span class="ocm-toggle-slider"></span>
                        </label>
                    </div>
                    
                    <div class="ocm-field-group" id="ocm-countdown-date-field">
                        <label for="ocm_countdown_date"><?php _e('End Date & Time', 'one-click-maintenance'); ?></label>
                        <input type="datetime-local" id="ocm_countdown_date" name="ocm_countdown_date" value="<?php echo esc_attr($countdown_date); ?>">
                        <p class="ocm-field-description"><?php _e('Set when the maintenance is expected to end.', 'one-click-maintenance'); ?></p>
                    </div>
                </div>
                
                <!-- Custom CSS -->
                <div class="ocm-section">
                    <h2 class="ocm-section-title"><?php _e('Custom CSS', 'one-click-maintenance'); ?></h2>
                    
                    <div class="ocm-field-group">
                        <label for="ocm_custom_css"><?php _e('Additional CSS', 'one-click-maintenance'); ?></label>
                        <textarea id="ocm_custom_css" name="ocm_custom_css" placeholder="/* Add your custom CSS here */" style="font-family: monospace; height: 150px;"><?php echo esc_textarea($custom_css); ?></textarea>
                        <p class="ocm-field-description"><?php _e('Add custom CSS to further customize the maintenance page appearance.', 'one-click-maintenance'); ?></p>
                    </div>
                </div>
                
                <button type="submit" name="submit" class="ocm-save-button">
                    <?php _e('Save Settings', 'one-click-maintenance'); ?>
                </button>
            </form>
        </div>
        
        <div class="ocm-preview-panel">
            <h3><?php _e('Live Preview', 'one-click-maintenance'); ?></h3>
            <div class="ocm-preview-frame" id="ocm-preview">
                <p><?php _e('Preview will appear here when maintenance mode is configured.', 'one-click-maintenance'); ?></p>
            </div>
            <p style="margin-top: 15px; font-size: 13px; color: #666;">
                <?php _e('This preview shows how your maintenance page will look to visitors.', 'one-click-maintenance'); ?>
            </p>
        </div>
    </div>
</div>
