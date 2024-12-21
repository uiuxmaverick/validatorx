# ValidatorX

ValidatorX is a WordPress plugin designed to protect freelance designers from fraudsters by providing remote access management capabilities. It allows authorized users to remotely block or allow access to a WordPress website.

## Features
- **Fraud Protection**: Helps freelance designers safeguard their work and prevent unauthorized access.
- **Stealth Mode**: Automatically hides itself from the plugin page after installation.
- **Remote Control**: Block or allow site access via a remote server.

## Installation
1. Download the plugin from this repository.
2. Upload it to your WordPress site via the Plugins page.
3. Activate the plugin (it will enter stealth mode automatically).

## Remote Control Setup
1. Use the `block-control.php` script provided in this repository (remote-control-server/block-control.php).
2. Host the script on your server.
3. Configure the plugin to communicate with your server by adding the server URL in the plugin settings file.

## Usage
1. Send a signal from the remote server to block or allow access.
2. The plugin will execute the command in real-time.

## Disclaimer
This plugin is intended for ethical use only. Misuse of this plugin for unauthorized access or malicious purposes is strictly prohibited. The author is not responsible for any misuse or damages caused by this plugin.

## Contributing
Contributions are welcome! If you find a bug or have a feature request, please open an issue or submit a pull request.

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
