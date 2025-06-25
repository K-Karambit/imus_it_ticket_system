#!/bin/bash

echo "Starting custom Nginx configuration setup for PHP app..."

# --- Configuration Paths ---
# The location where your custom Nginx config file is deployed within your app.
# Assuming you place it in a sub-folder named 'nginx-config' in your app's root.
CUSTOM_NGINX_CONF_SOURCE="/home/site/wwwroot/nginx-config/default.conf"

# The standard location where Nginx expects its main site configuration.
# For Azure App Service PHP containers, this is typically where the 'default' config resides.
NGINX_DEFAULT_CONF_DESTINATION="/etc/nginx/sites-enabled/default"

# --- Main Logic ---

# Check if the custom Nginx configuration file actually exists in the deployed path.
# This prevents errors if you accidentally forget to include it in your deployment package.
if [ -f "$CUSTOM_NGINX_CONF_SOURCE" ]; then
    echo "Custom Nginx config found at: $CUSTOM_NGINX_CONF_SOURCE"
    
    # Copy the custom configuration file to the Nginx system directory.
    # We use 'sudo' because /etc/nginx/ is a system directory and requires root privileges.
    # Azure App Service usually allows 'sudo' for these specific operations in startup commands.
    sudo cp "$CUSTOM_NGINX_CONF_SOURCE" "$NGINX_DEFAULT_CONF_DESTINATION"
    echo "Copied custom Nginx config to: $NGINX_DEFAULT_CONF_DESTINATION"

    # Test Nginx configuration for syntax errors before reloading.
    # This is a crucial step to prevent Nginx from failing to start.
    echo "Testing Nginx configuration for syntax errors..."
    sudo nginx -t
    if [ $? -eq 0 ]; then # Check the exit code of the previous command (0 means success)
        echo "Nginx configuration test successful. Reloading Nginx service..."
        # Reload the Nginx service to apply the new configuration.
        sudo service nginx reload
        echo "Nginx service reloaded successfully."
    else
        echo "ERROR: Nginx configuration test failed. Nginx service NOT reloaded."
        # You might want to add additional error handling here, or just let the container
        # potentially fail to start so you can see the error in the logs.
    fi
else
    echo "WARNING: Custom Nginx config file NOT found at $CUSTOM_NGINX_CONF_SOURCE. Skipping Nginx configuration."
    echo "The default Azure App Service Nginx configuration will be used."
fi

echo "Nginx configuration setup script finished."

# --- Important for Azure App Service ---
# Do NOT put an 'exit' command at the end of this script if you want your
# web application to continue running normally. The Azure App Service container
# has its own entry point that starts the main web server process (PHP-FPM, Nginx).
# This script is meant to run *before* or *as part of* that process, not to replace it.
# If you exit, the container might stop or not serve your app.