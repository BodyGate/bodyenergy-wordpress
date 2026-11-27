<?php
/** Run only by WP-CLI in a disposable WordPress install. */
if (!defined('WP_CLI') || !WP_CLI || !defined('BE_DISPOSABLE_QA') || !BE_DISPOSABLE_QA) { exit(1); }
$GLOBALS['be_qa_passed'] = 0;
function be_qa($label, $condition) {
    if (!$condition) { WP_CLI::error($label); }
    ++$GLOBALS['be_qa_passed'];
    WP_CLI::log('PASS: ' . $label);
}
function be_qa_page($slug, $status = 'publish') {
    $id = wp_insert_post(array('post_title' => $slug, 'post_name' => $slug, 'post_type' => 'page', 'post_status' => $status, 'post_content' => 'Disposable QA fixture.'), true);
    if (is_wp_error($id) || !$id) { WP_CLI::error('Failed fixture: ' . $slug); }
    return (int) $id;
}
be_qa('candidate plugin loaded', defined('BODYENERGY_WORDPRESS_VERSION') && BODYENERGY_WORDPRESS_VERSION === '0.11.6');
$membership = be_qa_page('abbonamenti');
$formula = be_qa_page('formule', 'future');
be_qa('real insert prevents membership publication', get_post_status($membership) === 'draft');
be_qa('real insert prevents formula scheduling', get_post_status($formula) === 'draft');
wp_publish_post($membership);
be_qa('direct publish transition returns to draft', get_post_status($membership) === 'draft');
wp_set_current_user(0);
$request = new WP_REST_Request('GET', '/wp/v2/pages/' . $membership);
$response = rest_do_request($request);
be_qa('anonymous REST endpoint denied', $response->get_status() === 404);
$contacts = be_qa_page('contatti');
be_qa('unrelated page remains public', get_post_status($contacts) === 'publish');
be_qa('Pilates uses real public contact permalink', bodyenergy_pilates_request_page_url() === get_permalink($contacts));
be_qa('anonymous Pilates shortcode has no form', !str_contains(do_shortcode('[bodyenergy_pilates_request_form]'), '<form'));
be_qa('default video GUID remains available', bodyenergy_home_platinum_videopress_guid() === 'gUAnLMKw');
WP_CLI::success($GLOBALS['be_qa_passed'] . ' real WordPress core checks passed.');
