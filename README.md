# Amazon Affiliate Extended YOURLS Plugin

This YOURLS plugin automatically adds Amazon affiliate tags to all Amazon URLs, including short links. It extends and improves upon [YOURLS-Amazon-Affiliate by floschliep](https://github.com/floschliep/YOURLS-Amazon-Affiliate), providing enhanced control over affiliate IDs for different Amazon regions and an easy-to-use graphical interface for managing these IDs.

## Features

- Adds Amazon affiliate tags to Amazon URLs (long and short) across various regions.
- Allows you to manage affiliate IDs via an admin interface within YOURLS.
- Supports multiple Amazon regions (US, CA, MX, BR, UK, DE, FR, ES, IT, NL, SE, PL, AE, SA, IN, JP, SG, CN, AU).
- Automatically resolves Amazon short links (a.co, amzn.to, amzn.eu, amzn.asia) before processing.
- Redirects with 301 status to ensure SEO compliance.
- Clean URL handling - removes existing affiliate tags before adding new ones.
- User-friendly admin interface with success confirmation messages.

## Installation

1. Download or clone this repository into the YOURLS plugins directory:
   ```bash
   git clone https://github.com/bnfone/yourls-amazon-affiliate-extended yourls/user/plugins/amazon-affiliate-extended
   ```
   
2. In your YOURLS admin panel, activate the plugin in the Plugins section.

## Usage

1. Go to the **Amazon Affiliate Extended** settings page in your YOURLS admin dashboard.
2. Enter your Amazon affiliate IDs for each supported region.
3. Save the settings - you'll see a green confirmation message upon successful save.
4. The plugin will automatically apply affiliate tags to all redirected Amazon URLs.

## How It Works

The plugin intercepts Amazon URLs when a short link is used and:
1. Resolves Amazon short links (a.co, amzn.to, etc.) to their full URLs
2. Identifies the Amazon region based on the domain
3. Applies the relevant affiliate tag based on your configured settings
4. Performs a 301 redirect to the final URL with the affiliate tag

If no affiliate ID is set for a specific region, the plugin leaves the URL unmodified.

## Affiliate Regions Supported

| Region        | Amazon Domain           |
|---------------|--------------------------|
| United States | `amazon.com`             |
| Canada        | `amazon.ca`              |
| Mexico        | `amazon.com.mx`          |
| Brazil        | `amazon.com.br`          |
| United Kingdom| `amazon.co.uk`           |
| Germany       | `amazon.de`              |
| France        | `amazon.fr`              |
| Spain         | `amazon.es`              |
| Italy         | `amazon.it`              |
| Netherlands   | `amazon.nl`              |
| Sweden        | `amazon.se`              |
| Poland        | `amazon.pl`              |
| UAE           | `amazon.ae`              |
| Saudi Arabia  | `amazon.sa`              |
| India         | `amazon.in`              |
| Japan         | `amazon.co.jp`           |
| Singapore     | `amazon.sg`              |
| China         | `amazon.cn`              |
| Australia     | `amazon.com.au`          |

## Bug Fixes in Version 2.0

- **Added more Amazon countries / affiliate programs**: Now all amazon affiliate programs are available
- **Added success confirmation**: Users now see a green success message after saving settings
- **Improved user experience**: Removed automatic redirect after saving to show confirmation

## Support the Developer

If you find this plugin useful, you're welcome to use my affiliate IDs to help support development:

- **Amazon US**: `yourlsplugin-20`

Alternatively, donations are greatly appreciated:
- **PayPal**: [paypal.me/bnfone](https://paypal.me/bnfone)

## Changelog

### Version 2.0
- Fixed critical PHP syntax errors that prevented the plugin from working
- Added user-friendly success confirmation messages
- Improved admin interface experience
- Enhanced URL cleaning and processing
- Added support for additional Amazon regions

### Version 1.0
- Initial release based on [YOURLS-Amazon-Affiliate by floschliep](https://github.com/floschliep/YOURLS-Amazon-Affiliate) with added support for managing affiliate IDs via a graphical interface.

## License

This plugin is open-source software licensed under the MIT license.