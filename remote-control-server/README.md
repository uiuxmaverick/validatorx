# Remote Control Server Script

This folder contains the server-side script for ValidatorX, which enables remote control functionality for blocking or allowing access to WordPress sites.

## Files
- **block-control.php**: The main script that processes remote signals to block or allow site access.
- **config.php**: Configuration file for setting up API keys, server URLs, and other parameters.

## Installation
1. Download the repository from GitHub.
2. Locate the `remote-control-server` folder in the downloaded files.
3. Upload the entire `remote-control-server` folder to your web server.
4. Ensure your server meets the following requirements:
   - PHP 7.4 or higher
   - cURL enabled
   - Write permissions for logging (if applicable)
1. Upload the files in this folder to your web server.
2. Ensure your server meets the following requirements:
   - PHP 7.4 or higher
   - cURL enabled
   - Write permissions for logging (if applicable)

## Configuration
1. Open the `block-control.php` file in a text editor.
2. Update the following variables:
   ```php
  $correct_password = 'SET_YOUR_ADMIN_PASSWORD'; // Set your password here
   ```
3. Save the changes.

## Usage
1. Obtain the server URL where the script is hosted (e.g., `https://yourdomain.com/remote-control-server/block-control.php`).
2. Replace the placeholder `$remote_url` in the ValidatorX plugin with your server URL:
   ```php
   $remote_url = 'https://yourdomain.com/remote-control-server/block-control.php?api=true';
   ```
3. Use the ValidatorX plugin to send signals to the server.

## Security Notes
- Keep your API key confidential and secure.
- Restrict access to the script by allowing only specific domains or IPs in the `config.php` file.

## Troubleshooting
- **Script not responding**: Ensure the server meets the requirements and the script files have the correct permissions.


## Disclaimer
This script is intended for ethical use only. Misuse for unauthorized access or malicious purposes is strictly prohibited. The author is not responsible for any misuse or damages caused by this script.
