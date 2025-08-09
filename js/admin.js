/**
 * One-Click Maintenance Mode - Admin JavaScript
 * Handles admin interface interactions and live preview
 */

jQuery(document).ready(function($) {
    
    // Initialize color picker
    $('.ocm-color-picker').wpColorPicker({
        change: function() {
            updatePreview();
        }
    });
    
    // Handle background type toggle
    $('input[name="ocm_background_type"]').on('change', function() {
        const backgroundType = $(this).val();
        
        if (backgroundType === 'color') {
            $('#ocm-background-color-field').show();
            $('#ocm-background-image-field').hide();
        } else {
            $('#ocm-background-color-field').hide();
            $('#ocm-background-image-field').show();
        }
        
        updatePreview();
    });
    
    // Initialize background type visibility
    const initialBackgroundType = $('input[name="ocm_background_type"]:checked').val();
    if (initialBackgroundType === 'color') {
        $('#ocm-background-color-field').show();
        $('#ocm-background-image-field').hide();
    } else {
        $('#ocm-background-color-field').hide();
        $('#ocm-background-image-field').show();
    }
    
    // Handle countdown toggle
    $('#ocm_countdown_enabled').on('change', function() {
        if ($(this).is(':checked')) {
            $('#ocm-countdown-date-field').show();
        } else {
            $('#ocm-countdown-date-field').hide();
        }
        updatePreview();
    });
    
    // Initialize countdown field visibility
    if ($('#ocm_countdown_enabled').is(':checked')) {
        $('#ocm-countdown-date-field').show();
    } else {
        $('#ocm-countdown-date-field').hide();
    }
    
    // Media uploader for background image
    $('#ocm-upload-bg').on('click', function(e) {
        e.preventDefault();
        
        const mediaUploader = wp.media({
            title: 'Choose Background Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#ocm_background_image').val(attachment.url);
            
            // Show preview and remove button
            if ($('#ocm-bg-preview').length) {
                $('#ocm-bg-preview').attr('src', attachment.url);
            } else {
                $('#ocm-remove-bg').after('<img src="' + attachment.url + '" class="ocm-image-preview" id="ocm-bg-preview">');
            }
            $('#ocm-remove-bg').show();
            
            updatePreview();
        });
        
        mediaUploader.open();
    });
    
    // Remove background image
    $('#ocm-remove-bg').on('click', function(e) {
        e.preventDefault();
        $('#ocm_background_image').val('');
        $('#ocm-bg-preview').remove();
        $(this).hide();
        updatePreview();
    });
    
    // Media uploader for logo
    $('#ocm-upload-logo').on('click', function(e) {
        e.preventDefault();
        
        const mediaUploader = wp.media({
            title: 'Choose Logo',
            button: {
                text: 'Use this logo'
            },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#ocm_logo').val(attachment.url);
            
            // Show preview and remove button
            if ($('#ocm-logo-preview').length) {
                $('#ocm-logo-preview').attr('src', attachment.url);
            } else {
                $('#ocm-remove-logo').after('<img src="' + attachment.url + '" class="ocm-image-preview" id="ocm-logo-preview">');
            }
            $('#ocm-remove-logo').show();
            
            updatePreview();
        });
        
        mediaUploader.open();
    });
    
    // Remove logo
    $('#ocm-remove-logo').on('click', function(e) {
        e.preventDefault();
        $('#ocm_logo').val('');
        $('#ocm-logo-preview').remove();
        $(this).hide();
        updatePreview();
    });
    
    // Update preview on input changes
    $('#ocm_title, #ocm_description, #ocm_footer_message, #ocm_custom_css').on('input', function() {
        updatePreview();
    });
    
    $('input[name="ocm_font_family"], input[name="ocm_font_size"]').on('change', function() {
        updatePreview();
    });
    
    // Live preview function
    function updatePreview() {
        const title = $('#ocm_title').val() || 'We\'re Under Maintenance';
        const description = $('#ocm_description').val() || 'We are currently performing scheduled maintenance. We will be back online shortly.';
        const fontFamily = $('#ocm_font_family').val();
        const fontSize = $('input[name="ocm_font_size"]:checked').val();
        const backgroundType = $('input[name="ocm_background_type"]:checked').val();
        const backgroundColor = $('#ocm_background_color').val();
        const backgroundImage = $('#ocm_background_image').val();
        const logo = $('#ocm_logo').val();
        const countdownEnabled = $('#ocm_countdown_enabled').is(':checked');
        const footerMessage = $('#ocm_footer_message').val();
        const customCSS = $('#ocm_custom_css').val();
        
        let previewHTML = '<div class="ocm-maintenance-container ocm-font-' + fontSize + '" style="';
        
        // Background styling
        if (backgroundType === 'color') {
            previewHTML += 'background-color: ' + backgroundColor + ';';
        } else if (backgroundType === 'image' && backgroundImage) {
            previewHTML += 'background-image: url(\'' + backgroundImage + '\');';
        }
        
        // Font family
        if (fontFamily !== 'inherit') {
            previewHTML += 'font-family: \'' + fontFamily + '\', sans-serif;';
        }
        
        previewHTML += 'min-height: 400px; padding: 20px;">';
        previewHTML += '<div class="ocm-content" style="max-width: 400px; padding: 40px 30px; background: rgba(255,255,255,0.95); border-radius: 15px; text-align: center;">';
        
        // Logo
        if (logo) {
            previewHTML += '<div class="ocm-logo" style="margin-bottom: 20px;"><img src="' + logo + '" style="max-width: 150px; max-height: 60px;"></div>';
        }
        
        // Title
        if (title) {
            let titleSize = '24px';
            if (fontSize === 'small') titleSize = '20px';
            if (fontSize === 'large') titleSize = '28px';
            previewHTML += '<h1 class="ocm-title" style="font-size: ' + titleSize + '; font-weight: 600; margin-bottom: 15px; color: #2c3e50;">' + title + '</h1>';
        }
        
        // Description
        if (description) {
            let descSize = '14px';
            if (fontSize === 'medium') descSize = '15px';
            if (fontSize === 'large') descSize = '16px';
            previewHTML += '<div class="ocm-description" style="font-size: ' + descSize + '; color: #5a6c7d; margin-bottom: 20px; line-height: 1.6;">' + description.replace(/\n/g, '<br>') + '</div>';
        }
        
        // Countdown (simplified for preview)
        if (countdownEnabled) {
            previewHTML += '<div class="ocm-countdown" style="margin: 20px 0; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; color: white;">';
            previewHTML += '<div style="font-size: 14px; margin-bottom: 15px;">We\'ll be back in:</div>';
            previewHTML += '<div style="display: flex; justify-content: center; gap: 15px;">';
            previewHTML += '<div style="text-align: center;"><span style="display: block; font-size: 20px; font-weight: 700;">02</span><span style="font-size: 10px; text-transform: uppercase;">Days</span></div>';
            previewHTML += '<div style="text-align: center;"><span style="display: block; font-size: 20px; font-weight: 700;">14</span><span style="font-size: 10px; text-transform: uppercase;">Hours</span></div>';
            previewHTML += '<div style="text-align: center;"><span style="display: block; font-size: 20px; font-weight: 700;">32</span><span style="font-size: 10px; text-transform: uppercase;">Min</span></div>';
            previewHTML += '<div style="text-align: center;"><span style="display: block; font-size: 20px; font-weight: 700;">18</span><span style="font-size: 10px; text-transform: uppercase;">Sec</span></div>';
            previewHTML += '</div></div>';
        }
        
        // Footer
        if (footerMessage) {
            previewHTML += '<div class="ocm-footer" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e1e8ed; color: #7f8c8d; font-size: 12px;">' + footerMessage.replace(/\n/g, '<br>') + '</div>';
        }
        
        previewHTML += '</div></div>';
        
        // Add custom CSS
        if (customCSS) {
            previewHTML += '<style>' + customCSS + '</style>';
        }
        
        $('#ocm-preview').html(previewHTML);
    }
    
    // Initial preview load
    updatePreview();
    
    // Form validation
    $('form').on('submit', function(e) {
        const maintenanceEnabled = $('#ocm_maintenance_enabled').is(':checked');
        const countdownEnabled = $('#ocm_countdown_enabled').is(':checked');
        const countdownDate = $('#ocm_countdown_date').val();
        
        if (countdownEnabled && !countdownDate) {
            e.preventDefault();
            alert('Please set a countdown date when countdown timer is enabled.');
            $('#ocm_countdown_date').focus();
            return false;
        }
        
        if (maintenanceEnabled) {
            const confirmed = confirm('Are you sure you want to enable maintenance mode? This will show the maintenance page to all non-logged-in visitors.');
            if (!confirmed) {
                e.preventDefault();
                return false;
            }
        }
    });
    
    // Auto-save functionality (optional)
    let autoSaveTimeout;
    $('input, textarea, select').on('change input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(function() {
            // Could implement auto-save here if needed
        }, 2000);
    });
    
});
