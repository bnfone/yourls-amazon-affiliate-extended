<?php
/*
Plugin Name: Amazon Affiliate Extended
Plugin URI: https://github.com/bnfone/yourls-amazon-affiliate
Description: Adds Amazon affiliate tags to all Amazon URLs, including short links, and allows management of affiliate IDs via the GUI.
Version: 2.0
Author: Blake
Author URI: https://github.com/bnfone/
License: MIT
*/

// Prevent direct access
if (!defined('YOURLS_ABSPATH')) die();

// Hooks
yourls_add_action('pre_redirect', 'aae_handle_amazon_links');
yourls_add_action('plugins_loaded', 'aae_init_admin');

/**
 * Register admin settings page
 */
function aae_init_admin() {
    yourls_register_plugin_page('amazon_affiliate_extended', 'Amazon Affiliate Extended', 'aae_display_admin_page');
}

/**
 * Admin page to manage affiliate tags per region
 */
function aae_display_admin_page() {
    $message = '';
    
    if (isset($_POST['submit'])) {
        // Save tags
        $regions = array('us','ca','mx','br','uk','de','fr','es','it','nl','se','pl','ae','sa','in','jp','sg','cn','au');
        $tags = array();
        foreach ($regions as $r) {
            $key = 'tag'.strtoupper($r);
            $tags[$r] = yourls_sanitize_string($_POST[$key]);
        }
        yourls_update_option('aae_tags', $tags);
        $message = '<div style="background-color: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 4px; margin: 10px 0;"><strong>Success:</strong> Your configuration was sucessfully saved!</div>';
    }

    // Load saved tags
    $tags = yourls_get_option('aae_tags');
    $defaults = array_fill_keys(array('us','ca','mx','br','uk','de','fr','es','it','nl','se','pl','ae','sa','in','jp','sg','cn','au'), '');
    $tags = is_array($tags) ? array_merge($defaults, $tags) : $defaults;
    ?>
    <h2>Amazon Affiliate Extended Settings</h2>
    <?php echo $message; ?>
    <form method="post" action="">
    <table>
        <tr><th>Region</th><th>Affiliate ID (Tag)</th></tr>
        <?php foreach ($tags as $region => $id): ?>
        <tr>
            <td><?php echo strtoupper($region); ?></td>
            <td><input type="text" name="tag<?php echo strtoupper($region); ?>" value="<?php echo htmlspecialchars($id); ?>" size="30" /></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p><input type="submit" name="submit" value="Save Settings" /></p>
    </form>
    <?php
}

/**
 * Main redirect handler: resolve short links, append affiliate tag
 */
function aae_handle_amazon_links($args) {
    $original_url = $args[0];
    $tags = yourls_get_option('aae_tags');

    // 1. Unshort Amazon shortlinks (a.co, amzn.to, amzn.eu, amzn.asia)
    if (preg_match('#^https?://(www\.)?(a\.co|amzn\.to|amzn\.eu|amzn\.asia)/(.*)#i', $original_url)) {
        $headers = @get_headers($original_url, 1);
        if (isset($headers['Location'])) {
            $original_url = is_array($headers['Location']) ? end($headers['Location']) : $headers['Location'];
        }
    }

    // 2. Domain-to-region mapping
    $map = array(
        'amazon\.com'     => 'us',
        'amazon\.ca'      => 'ca',
        'amazon\.com\.mx' => 'mx',
        'amazon\.com\.br' => 'br',
        'amazon\.co\.uk'  => 'uk',
        'amazon\.de'      => 'de',
        'amazon\.fr'      => 'fr',
        'amazon\.es'      => 'es',
        'amazon\.it'      => 'it',
        'amazon\.nl'      => 'nl',
        'amazon\.se'      => 'se',
        'amazon\.pl'      => 'pl',
        'amazon\.ae'      => 'ae',
        'amazon\.sa'      => 'sa',
        'amazon\.in'      => 'in',
        'amazon\.co\.jp'  => 'jp',
        'amazon\.sg'      => 'sg',
        'amazon\.cn'      => 'cn',
        'amazon\.com\.au' => 'au'
    );

    // Match domain
    foreach ($map as $pattern => $region) {
        if (preg_match("#^https?://(www\.)?". $pattern ."#i", $original_url)) {
            $tag = isset($tags[$region]) ? $tags[$region] : '';
            if (empty($tag)) {
                // No tag for this region
                return;
            }
            // Clean and append
            $url = aae_clean_url($original_url);
            $url = aae_add_query_param($url, 'tag', $tag);

            // Redirect
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: " . $url);
            exit;
        }
    }
}

/**
 * Remove existing tag param & tidy URL
 */
function aae_clean_url($url) {
    // Trim slash
    $url = rtrim($url, '/');
    // Remove existing tag
    $url = preg_replace('/([&?])tag=[^&]+/i', '\\1', $url);
    // Clean up leftover ?& or &&
    $url = preg_replace('/\?&/', '?', $url);
    $url = preg_replace('/&&/', '&', $url);
    // Trim trailing ? or &
    $url = rtrim($url, '?&');
    return $url;
}

/**
 * Generic add or append query parameter
 */
function aae_add_query_param($url, $key, $value) {
    $sep = (strpos($url, '?') !== false) ? '&' : '?';
    return $url . $sep . urlencode($key) . '=' . urlencode($value);
}
?>