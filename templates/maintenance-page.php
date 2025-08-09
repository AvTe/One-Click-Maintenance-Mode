<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo esc_html(get_option('ocm_title', __('We\'re Under Maintenance', 'one-click-maintenance'))); ?> - <?php bloginfo('name'); ?></title>
    
    <?php
    // Get settings
    $title = get_option('ocm_title', __('We\'re Under Maintenance', 'one-click-maintenance'));
    $description = get_option('ocm_description', __('We are currently performing scheduled maintenance. We will be back online shortly.', 'one-click-maintenance'));
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
    
    // Enqueue styles
    wp_enqueue_style('ocm-frontend-css', OCM_PLUGIN_URL . 'css/style.css', array(), OCM_PLUGIN_VERSION);
    
    // Enqueue Google Fonts if needed
    if ($font_family !== 'inherit') {
        $font_url = 'https://fonts.googleapis.com/css2?family=' . urlencode($font_family) . ':wght@300;400;500;600;700&display=swap';
        wp_enqueue_style('ocm-google-fonts', $font_url, array(), null);
    }

    // Enqueue jQuery for AJAX functionality
    wp_enqueue_script('jquery');

    // Print styles and scripts
    wp_print_styles();
    wp_print_scripts();
    ?>
    
    <style>
        <?php if ($font_family !== 'inherit'): ?>
        html, body {
            font-family: '<?php echo esc_attr($font_family); ?>', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
        <?php endif; ?>
        
        .ocm-maintenance-container {
            <?php if ($background_type === 'color'): ?>
                background-color: <?php echo esc_attr($background_color); ?>;
            <?php elseif ($background_type === 'image' && !empty($background_image)): ?>
                background-image: url('<?php echo esc_url($background_image); ?>');
            <?php endif; ?>
        }
        
        <?php if (!empty($custom_css)): ?>
        /* Custom CSS */
        <?php echo wp_strip_all_tags($custom_css); ?>
        <?php endif; ?>
    </style>
    
    <?php if ($countdown_enabled && !empty($countdown_date)): ?>
    <script>
        // Countdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const countdownDate = new Date('<?php echo esc_js($countdown_date); ?>').getTime();
            
            function updateCountdown() {
                const now = new Date().getTime();
                const distance = countdownDate - now;
                
                if (distance < 0) {
                    document.getElementById('ocm-countdown').innerHTML = '<div class="ocm-countdown-expired"><?php echo esc_js(__('Maintenance completed!', 'one-click-maintenance')); ?></div>';
                    return;
                }
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                document.getElementById('ocm-days').textContent = days.toString().padStart(2, '0');
                document.getElementById('ocm-hours').textContent = hours.toString().padStart(2, '0');
                document.getElementById('ocm-minutes').textContent = minutes.toString().padStart(2, '0');
                document.getElementById('ocm-seconds').textContent = seconds.toString().padStart(2, '0');
            }
            
            updateCountdown();
            setInterval(updateCountdown, 1000);
        });
    </script>
    <?php endif; ?>
</head>
<body class="ocm-font-<?php echo esc_attr($font_size); ?>">
    <div class="ocm-maintenance-container">
        <div class="ocm-content">
            <?php if (!empty($logo)): ?>
            <div class="ocm-logo">
                <img src="<?php echo esc_url($logo); ?>" alt="<?php bloginfo('name'); ?>">
            </div>
            <?php endif; ?>
            
            <?php if (!empty($title)): ?>
            <h1 class="ocm-title"><?php echo esc_html($title); ?></h1>
            <?php endif; ?>
            
            <?php if (!empty($description)): ?>
            <div class="ocm-description">
                <?php echo wp_kses_post(wpautop($description)); ?>
            </div>
            <?php endif; ?>
            
            <?php if ($countdown_enabled && !empty($countdown_date)): ?>
            <div class="ocm-countdown" id="ocm-countdown">
                <div class="ocm-countdown-title">
                    <?php _e('We\'ll be back in:', 'one-click-maintenance'); ?>
                </div>
                <div class="ocm-countdown-timer">
                    <div class="ocm-countdown-item">
                        <span class="ocm-countdown-number" id="ocm-days">00</span>
                        <span class="ocm-countdown-label"><?php _e('Days', 'one-click-maintenance'); ?></span>
                    </div>
                    <div class="ocm-countdown-item">
                        <span class="ocm-countdown-number" id="ocm-hours">00</span>
                        <span class="ocm-countdown-label"><?php _e('Hours', 'one-click-maintenance'); ?></span>
                    </div>
                    <div class="ocm-countdown-item">
                        <span class="ocm-countdown-number" id="ocm-minutes">00</span>
                        <span class="ocm-countdown-label"><?php _e('Minutes', 'one-click-maintenance'); ?></span>
                    </div>
                    <div class="ocm-countdown-item">
                        <span class="ocm-countdown-number" id="ocm-seconds">00</span>
                        <span class="ocm-countdown-label"><?php _e('Seconds', 'one-click-maintenance'); ?></span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($footer_message)): ?>
            <div class="ocm-footer">
                <?php echo wp_kses_post(wpautop($footer_message)); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
