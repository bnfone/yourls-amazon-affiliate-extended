# Amazon Affiliate Extended YOURLS Plugin

This YOURLS plugin automatically adds Amazon affiliate tags to all Amazon URLs, including short links. It extends and improves upon [YOURLS-Amazon-Affiliate by floschliep](https://github.com/floschliep/YOURLS-Amazon-Affiliate), providing enhanced control over affiliate IDs for different Amazon regions and an easy-to-use graphical interface for managing these IDs.

## Features

- Adds Amazon affiliate tags to Amazon URLs (long and short) across various regions.
- Allows you to manage affiliate IDs via an admin interface within YOURLS.
- Supports multiple Amazon regions (IN, IT, US, DE, UK, FR, ES, JP, AU).
- Optionally appends a campaign parameter to Amazon URLs.
- Redirects with 301 status to ensure SEO compliance.

## Installation

1. Download or clone this repository into the YOURLS plugins directory:
   ```bash
   git clone https://github.com/bnfone/YOURLS-Amazon-Affiliate-Extended yourls-user/plugins/amazon-affiliate-extended
   ```
   
2. In your YOURLS admin panel, activate the plugin in the Plugins section.

## Usage

1. Go to the **Amazon Affiliate Extended** settings page in your YOURLS admin dashboard.
2. Enter your Amazon affiliate IDs for each supported region.
3. Optionally, add a campaign parameter to track the performance of your links.
4. Save the settings, and the plugin will automatically apply affiliate tags to all redirected Amazon URLs.

## How It Works

The plugin intercepts Amazon URLs when a short link is used and applies the relevant affiliate tag based on the URL's domain. It supports both standard Amazon links (e.g., `amazon.de`) and short Amazon links (e.g., `amzn.to`). If no affiliate ID is set for a specific region, the plugin leaves the URL unmodified.

## Affiliate Regions Supported

| Region    | Amazon Domain           |
|-----------|--------------------------|
| India     | `amazon.in`              |
| Italy     | `amazon.it`              |
| USA       | `amazon.com`             |
| Germany   | `amazon.de`              |
| UK        | `amazon.co.uk`           |
| France    | `amazon.fr`              |
| Spain     | `amazon.es`              |
| Japan     | `amazon.co.jp`           |
| Australia | `amazon.com.au`          |

## Changelog

### Version 1.0
- Initial release based on [YOURLS-Amazon-Affiliate by floschliep](https://github.com/floschliep/YOURLS-Amazon-Affiliate) with added support for managing affiliate IDs via a graphical interface.

## License

This plugin is open-source software licensed under the MIT license.