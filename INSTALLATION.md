# One-Click Maintenance Mode - Installation Guide

## Quick Installation

### Method 1: WordPress Admin (Recommended)
1. Download the plugin zip file
2. Log in to your WordPress admin dashboard
3. Navigate to **Plugins > Add New**
4. Click **Upload Plugin**
5. Choose the `one-click-maintenance.zip` file
6. Click **Install Now**
7. Click **Activate Plugin**

### Method 2: FTP Upload
1. Extract the plugin zip file
2. Upload the `one-click-maintenance` folder to `/wp-content/plugins/`
3. Log in to your WordPress admin dashboard
4. Navigate to **Plugins > Installed Plugins**
5. Find "One-Click Maintenance Mode" and click **Activate**

## Initial Setup

### 1. Access Settings
After activation, go to **Settings > Maintenance Mode** in your WordPress admin.

### 2. Basic Configuration
- **Enable Maintenance Mode**: Toggle this on when you're ready to show the maintenance page
- **Title**: Set your maintenance page title (default: "We're Under Maintenance")
- **Description**: Add a description explaining the maintenance

### 3. Design Customization
- **Font Family**: Choose from DM Sans, Inter, Roboto, Open Sans, Lato, Poppins, or use your theme's font
- **Font Size**: Select Small, Medium, or Large
- **Background**: Choose between a solid color or upload a background image
- **Logo**: Upload your site logo (optional)

### 4. Countdown Timer (Optional)
- **Enable Countdown**: Toggle on to show a countdown timer
- **End Date & Time**: Set when maintenance is expected to complete
- **Auto-Disable**: When countdown reaches zero, maintenance mode automatically disables and website goes live

### 5. Footer Message
Add contact information or any additional message for visitors.

### 6. Custom CSS (Advanced)
Add custom CSS to further customize the maintenance page appearance.

## Usage

### Enabling Maintenance Mode
1. Go to **Settings > Maintenance Mode**
2. Toggle **Enable Maintenance Mode** to ON
3. Click **Save Settings**
4. Your maintenance page is now live for all non-logged-in visitors

### Disabling Maintenance Mode
1. Go to **Settings > Maintenance Mode**
2. Toggle **Enable Maintenance Mode** to OFF
3. Click **Save Settings**
4. Your site is now back to normal

## Important Notes

### Admin Access
- Logged-in administrators can always access the site, even when maintenance mode is enabled
- This allows you to continue working on your site while maintenance mode is active

### Auto-Disable Feature
- When countdown timer is enabled and reaches zero, maintenance mode automatically disables
- The website automatically becomes live again without manual intervention
- Users will see a brief "Maintenance completed! Reloading..." message before the site loads normally

### SEO Considerations
- The plugin automatically sends 503 Service Unavailable headers
- Search engines understand this is temporary maintenance
- No negative SEO impact when used properly

### Testing
- Always test your maintenance page before enabling it for visitors
- Use the live preview in the admin settings to see how it will look
- Consider testing on different devices and screen sizes

## Troubleshooting

### Plugin Not Working
1. Ensure the plugin is activated
2. Check that you have administrator privileges
3. Verify WordPress version compatibility (5.0+)
4. Check for plugin conflicts by deactivating other plugins temporarily

### Maintenance Page Not Showing
1. Clear any caching plugins
2. Check if you're logged in as an administrator (admins bypass maintenance mode)
3. Try viewing the site in an incognito/private browser window
4. Verify maintenance mode is enabled in settings

### Styling Issues
1. Check if your theme has conflicting CSS
2. Use the Custom CSS field to override any styling issues
3. Ensure uploaded images are accessible and properly sized

### Countdown Timer Not Working
1. Verify JavaScript is enabled in your browser
2. Check that the countdown date is set in the future
3. Clear browser cache and reload the page

## File Structure

```
one-click-maintenance/
├── css/
│   ├── style.css          # Frontend maintenance page styles
│   └── admin.css          # Admin interface styles
├── js/
│   ├── admin.js           # Admin interface functionality
│   └── countdown.js       # Countdown timer functionality
├── templates/
│   ├── admin-page.php     # Admin settings page template
│   └── maintenance-page.php # Frontend maintenance page template
├── one-click-maintenance.php # Main plugin file
├── readme.txt             # WordPress plugin readme
└── INSTALLATION.md        # This installation guide
```

## Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **Browser**: Modern browsers with JavaScript enabled
- **Permissions**: Administrator access to WordPress

## Support

If you encounter any issues during installation or setup:

1. Check the troubleshooting section above
2. Verify all requirements are met
3. Test with default WordPress theme and no other plugins
4. Contact support with specific error messages and WordPress/PHP versions

## Next Steps

After successful installation:
1. Customize your maintenance page design
2. Test the maintenance mode functionality
3. Set up a countdown timer if needed
4. Add your contact information to the footer
5. Save the settings and enable when ready for maintenance

Enjoy using One-Click Maintenance Mode!
