/**
 * One-Click Maintenance Mode - Countdown Timer
 * Frontend countdown functionality
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Check if countdown data is available
        if (typeof ocm_countdown === 'undefined' || !ocm_countdown.date) {
            return;
        }
        
        const countdownDate = new Date(ocm_countdown.date).getTime();
        const labels = ocm_countdown.labels;
        
        // Elements
        const daysElement = document.getElementById('ocm-days');
        const hoursElement = document.getElementById('ocm-hours');
        const minutesElement = document.getElementById('ocm-minutes');
        const secondsElement = document.getElementById('ocm-seconds');
        const countdownContainer = document.getElementById('ocm-countdown');
        
        if (!daysElement || !hoursElement || !minutesElement || !secondsElement) {
            return;
        }
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = countdownDate - now;

            // If countdown is finished
            if (distance < 0) {
                countdownContainer.innerHTML = '<div class="ocm-countdown-expired" style="text-align: center; padding: 20px; font-size: 18px; font-weight: 500;">🎉 Maintenance completed! Redirecting...</div>';

                // Auto-disable maintenance mode and reload the page
                disableMaintenanceMode();
                return;
            }
            
            // Calculate time units
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            // Update display with animation
            animateNumber(daysElement, days);
            animateNumber(hoursElement, hours);
            animateNumber(minutesElement, minutes);
            animateNumber(secondsElement, seconds);
        }
        
        function animateNumber(element, newValue) {
            const currentValue = parseInt(element.textContent) || 0;
            const formattedValue = newValue.toString().padStart(2, '0');
            
            if (currentValue !== newValue) {
                element.style.transform = 'scale(1.1)';
                element.style.transition = 'transform 0.2s ease';
                
                setTimeout(function() {
                    element.textContent = formattedValue;
                    element.style.transform = 'scale(1)';
                }, 100);
            }
        }
        
        // Initialize countdown
        updateCountdown();
        
        // Function to disable maintenance mode via AJAX
        function disableMaintenanceMode() {
            // Show loading message
            countdownContainer.innerHTML = '<div class="ocm-countdown-expired" style="text-align: center; padding: 20px; font-size: 18px; font-weight: 500;">🎉 Maintenance completed! Disabling maintenance mode...</div>';

            // Make AJAX request to disable maintenance mode
            $.ajax({
                url: ocm_countdown.ajax_url || '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'ocm_disable_maintenance',
                    nonce: ocm_countdown.nonce || ''
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message and reload after 3 seconds
                        countdownContainer.innerHTML = '<div class="ocm-countdown-expired" style="text-align: center; padding: 20px; font-size: 18px; font-weight: 500;">🎉 Maintenance mode disabled! Reloading website...</div>';

                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);
                    } else {
                        // Fallback: just reload the page
                        countdownContainer.innerHTML = '<div class="ocm-countdown-expired" style="text-align: center; padding: 20px; font-size: 18px; font-weight: 500;">🎉 Maintenance completed! Please refresh the page.</div>';
                    }
                },
                error: function() {
                    // Fallback: just reload the page after 5 seconds
                    countdownContainer.innerHTML = '<div class="ocm-countdown-expired" style="text-align: center; padding: 20px; font-size: 18px; font-weight: 500;">🎉 Maintenance completed! Reloading...</div>';

                    setTimeout(function() {
                        window.location.reload();
                    }, 5000);
                }
            });
        }

        // Update every second
        const countdownInterval = setInterval(updateCountdown, 1000);
        
        // Add some visual enhancements
        addCountdownEnhancements();
        
        function addCountdownEnhancements() {
            // Add pulse animation to seconds
            if (secondsElement) {
                setInterval(function() {
                    secondsElement.style.animation = 'pulse 0.5s ease-in-out';
                    setTimeout(function() {
                        secondsElement.style.animation = '';
                    }, 500);
                }, 1000);
            }
            
            // Add CSS for pulse animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes pulse {
                    0% { transform: scale(1); }
                    50% { transform: scale(1.05); }
                    100% { transform: scale(1); }
                }
                
                .ocm-countdown-expired {
                    animation: fadeInBounce 0.8s ease-out;
                }
                
                @keyframes fadeInBounce {
                    0% {
                        opacity: 0;
                        transform: translateY(-20px) scale(0.8);
                    }
                    50% {
                        opacity: 0.8;
                        transform: translateY(5px) scale(1.05);
                    }
                    100% {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                }
                
                .ocm-countdown-number {
                    transition: all 0.3s ease;
                }
                
                .ocm-countdown-item:hover .ocm-countdown-number {
                    transform: scale(1.1);
                    color: rgba(255, 255, 255, 0.9);
                }
            `;
            document.head.appendChild(style);
        }
        
        // Handle visibility change (pause when tab is not active)
        let isVisible = true;
        
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                isVisible = false;
                clearInterval(countdownInterval);
            } else {
                isVisible = true;
                updateCountdown();
                setInterval(updateCountdown, 1000);
            }
        });
        
        // Handle window focus/blur
        window.addEventListener('focus', function() {
            if (!isVisible) {
                updateCountdown();
            }
        });
        
        // Add keyboard accessibility
        countdownContainer.setAttribute('role', 'timer');
        countdownContainer.setAttribute('aria-live', 'polite');
        countdownContainer.setAttribute('aria-label', 'Countdown timer showing time remaining until maintenance completion');
        
        // Update aria-label periodically for screen readers
        setInterval(function() {
            if (daysElement && hoursElement && minutesElement && secondsElement) {
                const days = parseInt(daysElement.textContent) || 0;
                const hours = parseInt(hoursElement.textContent) || 0;
                const minutes = parseInt(minutesElement.textContent) || 0;
                const seconds = parseInt(secondsElement.textContent) || 0;
                
                let ariaLabel = 'Time remaining: ';
                if (days > 0) ariaLabel += days + ' days, ';
                if (hours > 0) ariaLabel += hours + ' hours, ';
                if (minutes > 0) ariaLabel += minutes + ' minutes, ';
                ariaLabel += seconds + ' seconds';
                
                countdownContainer.setAttribute('aria-label', ariaLabel);
            }
        }, 5000); // Update every 5 seconds for screen readers
        
    });
    
})(jQuery);
