<?php
/**
 * Plugin Name: Body Energy Integration
 * Description: Plugin base per le integrazioni tra il sito WordPress Body Energy ASD e BodyGate.
 * Version: 0.11.0
 * Author: Body Energy ASD
 * Text Domain: bodyenergy-wordpress
 */

if (!defined('ABSPATH')) {
    exit;
}

define('BODYENERGY_WORDPRESS_VERSION', '0.11.0');
define('BODYENERGY_WORDPRESS_FILE', __FILE__);
define('BODYENERGY_WORDPRESS_PATH', plugin_dir_path(__FILE__));

require_once BODYENERGY_WORDPRESS_PATH . 'includes/control-center.php';
require_once BODYENERGY_WORDPRESS_PATH . 'includes/site-audit.php';
require_once BODYENERGY_WORDPRESS_PATH . 'includes/content-map.php';
require_once BODYENERGY_WORDPRESS_PATH . 'includes/pilates-landing.php';
require_once BODYENERGY_WORDPRESS_PATH . 'includes/pilates-capacity-fix.php';
require_once BODYENERGY_WORDPRESS_PATH . 'includes/pilates-layout-fix.php';
require_once BODYENERGY_WORDPRESS_PATH . 'includes/theme-settings-migration.php';
require_once BODYENERGY_WORDPRESS_PATH . 'includes/home-platinum.php';
require_once BODYENERGY_WORDPRESS_PATH . 'includes/home-platinum-video-admin.php';
require_once BODYENERGY_WORDPRESS_PATH . 'includes/site-architecture.php';
require_once BODYENERGY_WORDPRESS_PATH . 'includes/gym-platinum.php';
require_once BODYENERGY_WORDPRESS_PATH . 'includes/site-navigation-polish.php';
require_once BODYENERGY_WORDPRESS_PATH . 'includes/contact-platinum.php';
require_once BODYENERGY_WORDPRESS_PATH . 'includes/contact-compact-polish.php';
require_once BODYENERGY_WORDPRESS_PATH . 'includes/contact-final-cleanup.php';
require_once BODYENERGY_WORDPRESS_PATH . 'includes/services-platinum.php';
require_once BODYENERGY_WORDPRESS_PATH . 'includes/pilates-request-flow.php';
