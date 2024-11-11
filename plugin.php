<?php
/*
Plugin Name: Amazon Affiliate Extended
Plugin URI: https://github.com/bnfone/yourls-amazon-affiliate
Description: Adds Amazon affiliate tags to all Amazon URLs, including short links, and allows management of affiliate IDs via the GUI. Forked from https://github.com/floschliep/YOURLS-Amazon-Affiliate-Extended
Version: 1.0
Author: Blake
Author URI: https://github.com/bnfone/
*/

// Security check to prevent direct access
if( !defined( 'YOURLS_ABSPATH' ) ) die();

// Hook into the 'pre_redirect' action
yourls_add_action('pre_redirect', 'flo_amazonAffiliate');

// Hook into 'plugins_loaded' to register the admin page
yourls_add_action('plugins_loaded', 'flo_amazonAffiliate_init');

function flo_amazonAffiliate_init() {
    // Register the admin page
    yourls_register_plugin_page('amazon_affiliate_extended', 'Amazon Affiliate Extended', 'flo_amazonAffiliate_display_page');
}

// Function to display the admin page
function flo_amazonAffiliate_display_page() {
    // Check if the form has been submitted
    if( isset($_POST['submit']) ) {
        // Check security nonce (optional but recommended)
        // Save the affiliate IDs
        $tags = array(
            'in' => yourls_sanitize_string($_POST['tagIN']),
            'it' => yourls_sanitize_string($_POST['tagIT']),
            'us' => yourls_sanitize_string($_POST['tagUS']),
            'de' => yourls_sanitize_string($_POST['tagDE']),
            'uk' => yourls_sanitize_string($_POST['tagUK']),
            'fr' => yourls_sanitize_string($_POST['tagFR']),
            'es' => yourls_sanitize_string($_POST['tagES']),
            'jp' => yourls_sanitize_string($_POST['tagJP']),
            'au' => yourls_sanitize_string($_POST['tagAU'])
        );
        $campaign = yourls_sanitize_string($_POST['campaign']);
        
        // Save the options
        yourls_update_option('flo_amazonAffiliate_tags', $tags);
        yourls_update_option('flo_amazonAffiliate_campaign', $campaign);
        
        echo yourls_safe_redirect(yourls_admin_url('plugins.php'), 0, 'redirect');
    }
    
    // Load saved affiliate IDs
    $tags = yourls_get_option('flo_amazonAffiliate_tags');
    $campaign = yourls_get_option('flo_amazonAffiliate_campaign');
    ?>
    <h2>Amazon Affiliate Extended Settings</h2>
    <form method="post" action="">
        <table>
            <tr>
                <th>Region</th>
                <th>Affiliate ID</th>
            </tr>
            <tr>
                <td>India (IN)</td>
                <td><input type="text" name="tagIN" value="<?php echo htmlspecialchars($tags['in']); ?>" /></td>
            </tr>
            <tr>
                <td>Italy (IT)</td>
                <td><input type="text" name="tagIT" value="<?php echo htmlspecialchars($tags['it']); ?>" /></td>
            </tr>
            <tr>
                <td>USA (US)</td>
                <td><input type="text" name="tagUS" value="<?php echo htmlspecialchars($tags['us']); ?>" /></td>
            </tr>
            <tr>
                <td>Germany (DE)</td>
                <td><input type="text" name="tagDE" value="<?php echo htmlspecialchars($tags['de']); ?>" /></td>
            </tr>
            <tr>
                <td>UK</td>
                <td><input type="text" name="tagUK" value="<?php echo htmlspecialchars($tags['uk']); ?>" /></td>
            </tr>
            <tr>
                <td>France (FR)</td>
                <td><input type="text" name="tagFR" value="<?php echo htmlspecialchars($tags['fr']); ?>" /></td>
            </tr>
            <tr>
                <td>Spain (ES)</td>
                <td><input type="text" name="tagES" value="<?php echo htmlspecialchars($tags['es']); ?>" /></td>
            </tr>
            <tr>
                <td>Japan (JP)</td>
                <td><input type="text" name="tagJP" value="<?php echo htmlspecialchars($tags['jp']); ?>" /></td>
            </tr>
            <tr>
                <td>Australia (AU)</td>
                <td><input type="text" name="tagAU" value="<?php echo htmlspecialchars($tags['au']); ?>" /></td>
            </tr>
            <tr>
                <td>Campaign</td>
                <td><input type="text" name="campaign" value="<?php echo htmlspecialchars($campaign); ?>" /></td>
            </tr>
        </table>
        <p><input type="submit" name="submit" value="Save Settings" /></p>
    </form>
    <?php
}

function flo_amazonAffiliate($args) {
    // Load saved affiliate IDs
    $settings = yourls_get_option('flo_amazonAffiliate_tags');
    $campaign = yourls_get_option('flo_amazonAffiliate_campaign');
    
    // Use default values if settings are not set
    $tagIN = isset($settings['in']) ? $settings['in'] : null;
    $tagIT = isset($settings['it']) ? $settings['it'] : null;
    $tagUS = isset($settings['us']) ? $settings['us'] : null;
    $tagDE = isset($settings['de']) ? $settings['de'] : null;
    $tagUK = isset($settings['uk']) ? $settings['uk'] : null;
    $tagFR = isset($settings['fr']) ? $settings['fr'] : null;
    $tagES = isset($settings['es']) ? $settings['es'] : null;
    $tagJP = isset($settings['jp']) ? $settings['jp'] : null;
    $tagAU = isset($settings['au']) ? $settings['au'] : null;
    
    // Get the URL from the arguments
    $url = $args[0];
    
    // Create an array with regex patterns and corresponding affiliate tags
    $patternTagPairs = array(
        '/^http(s)?:\/\/(www\.)?amazon\.in\//ui' => $tagIN,
        '/^http(s)?:\/\/(www\.)?amazon\.it\//ui' => $tagIT,
        '/^http(s)?:\/\/(www\.)?amazon\.com\.au\//ui' => $tagAU,
        '/^http(s)?:\/\/(www\.)?amazon\.com\//ui' => $tagUS,
        '/^http(s)?:\/\/(www\.)?amazon\.de\//ui' => $tagDE,
        '/^http(s)?:\/\/(www\.)?amazon\.co\.uk\//ui' => $tagUK,
        '/^http(s)?:\/\/(www\.)?amazon\.fr\//ui' => $tagFR,
        '/^http(s)?:\/\/(www\.)?amazon\.es\//ui' => $tagES,
        '/^http(s)?:\/\/(www\.)?amazon\.co\.jp\//ui' => $tagJP,
        // Support for Amazon short links
        '/^http(s)?:\/\/(www\.)?amzn\.(eu|to|de|co\.jp|com\.au|ca|fr|es|it|in|co\.uk)\/d\//ui' => $tagDE // Defaults to US tag, can be customized
    );
    
    // Check if the URL is a supported Amazon URL
    foreach ($patternTagPairs as $pattern => $tag) {
        if (preg_match($pattern, $url)) {
            if (empty($tag)) {
                // No affiliate tag set for this region
                return;
            }
            
            // Modify the URL
            $url = cleanUpURL($url);
            $url = addTagToURL($url, $tag);
            $url = addCampaignToURL($url, $campaign);
            
            // Perform the redirect
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: $url");
            
            // Exit script to interrupt normal flow
            die();
        }
    }
}

function cleanUpURL($url) {
    // Remove trailing slash
    if (substr($url, -1) == "/") {
        $url = substr($url, 0, -1);
    }
    
    // Remove existing affiliate tags
    if (preg_match('/([&?]tag=)[^&]+/i', $url, $matches)) {
        $url = preg_replace('/([&?]tag=)[^&]+/i', '$1', $url);
    }
    
    // Remove existing campaign parameters
    if (preg_match('/([&?]camp=)[^&]+/i', $url, $matches)) {
        $url = preg_replace('/([&?]camp=)[^&]+/i', '$1', $url);
    }
    
    // Clean up duplicate & or ? characters
    $url = preg_replace('/\?&/', '?', $url);
    $url = preg_replace('/&&/', '&', $url);
    
    return $url;
}

function addTagToURL($url, $tag) {
    // Check if the URL already has a query string
    if (strpos($url, '?') !== false) {
        // Append the tag with &
        if (substr($url, -1) == "&") {
            $url .= 'tag=' . $tag;
        } else {
            $url .= '&tag=' . $tag;
        }
    } else {
        // Start a new query string
        $url .= '?tag=' . $tag;
    }
    
    return $url;
}

function addCampaignToURL($url, $campaign) {
    if (empty($campaign)) {
        return $url;
    }
    
    // Add the campaign parameter
    if (strpos($url, '?') !== false) {
        if (substr($url, -1) == "&") {
            $url .= 'camp=' . $campaign;
        } else {
            $url .= '&camp=' . $campaign;
        }
    } else {
        $url .= '?camp=' . $campaign;
    }
    
    return $url;
}

?>
