<?php

/**
 * Installs a plugin into a Textpattern site the way Admin → Plugins does,
 * then puts the test page in place and switches the site to debug mode so
 * tag errors are printed into the page.
 *
 * Usage: php install-plugin.php <site dir> <plugin .txt> <page template>
 */

[, $site, $pluginFile, $pageFile] = $argv + [null, null, null, null];

if (!$site || !is_file($pluginFile) || !is_file($pageFile)) {
    fwrite(STDERR, "Usage: php install-plugin.php <site dir> <plugin .txt> <page template>\n");
    exit(2);
}

define('txpinterface', 'admin');
define('txpath', realpath($site) . '/textpattern');

error_reporting(E_ALL);
ini_set('display_errors', '1');

include txpath . '/config.php';
include txpath . '/lib/class.trace.php';
$trace = new Trace();
include txpath . '/lib/constants.php';
include txpath . '/lib/txplib_misc.php';
include txpath . '/lib/txplib_db.php';
include txpath . '/lib/txplib_admin.php';
include txpath . '/vendors/Textpattern/Loader.php';

(new \Textpattern\Loader(txpath . '/vendors'))->register();
(new \Textpattern\Loader(txpath . '/lib'))->register();

// The parts of textpattern/index.php's bootstrap the plugin installer needs
$prefs = get_prefs();
extract($prefs);
define('LANG', $language);
define('txp_version', $thisversion);
$textarray = [];

// Install and enable the plugin from its distributed .txt
$result = Txp::get('\Textpattern\Plugin\Plugin')->install(file_get_contents($pluginFile), 1);

if (!is_string($result) || !safe_field('name', 'txp_plugin', "status = 1")) {
    fwrite(STDERR, "Plugin install failed: " . print_r($result, true) . "\n");
    exit(1);
}

echo "Installed plugin: ", strip_tags($result), "\n";

safe_update('txp_prefs', "val = 'debug'", "name = 'production_status'");
safe_update('txp_page', "user_html = '" . doSlash(file_get_contents($pageFile)) . "'", "name = 'default'");
