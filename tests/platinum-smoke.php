<?php
/** CLI-only tests with isolated WordPress API stubs. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
define('ABSPATH', __DIR__);
define('OBJECT', 'OBJECT');
class WP_Post {
    public function __construct(public int $ID, public string $post_type = 'page', public string $post_status = 'publish', public string $post_name = '', public string $mime = '') {}
}
class WP_Error {
    public function __construct(public string $code, public string $message, public array $data) {}
}
class RequestStub {
    public function __construct(private string $route) {}
    public function get_route() { return $this->route; }
}
$GLOBALS['pages'] = array('abbonamenti' => new WP_Post(11, 'page', 'publish', 'abbonamenti'), 'formule' => new WP_Post(12, 'page', 'publish', 'formule'));
$GLOBALS['posts'] = array(11 => $GLOBALS['pages']['abbonamenti'], 21 => new WP_Post(21, 'attachment', 'inherit', '', 'video/mp4'), 22 => new WP_Post(22, 'attachment', 'inherit', '', 'image/png'));
$GLOBALS['options'] = array();
$GLOBALS['edit'] = false;
function add_action(...$args) {}
function add_filter(...$args) {}
function add_shortcode(...$args) {}
function get_page_by_path($path, ...$args) { return $GLOBALS['pages'][$path] ?? null; }
function get_post($id) { return $GLOBALS['posts'][$id] ?? null; }
function get_post_status($p) { return $p->post_status; }
function current_user_can(...$args) { return $GLOBALS['edit']; }
function wp_unslash($v) { return $v; }
function sanitize_title($s) { return strtolower(trim($s)); }
function wp_parse_url($url, $part = -1) { return parse_url($url, $part); }
function absint($v) { return abs((int)$v); }
function get_option($name, $default = false) { return $GLOBALS['options'][$name] ?? $default; }
function get_post_mime_type($p) { return $p->mime; }
function wp_get_attachment_url($id) { return 'https://videos.files.wordpress.com/ABcd1234/video.mp4'; }
function get_permalink($p) { return 'https://example.invalid/page/' . $p->ID; }
function get_queried_object_id() { return 0; }
function is_user_logged_in() { return false; }
function esc_url($url) { return htmlspecialchars($url, ENT_QUOTES); }

require __DIR__ . '/../includes/memberships-public-policy.php';
require __DIR__ . '/../includes/home-platinum.php';
require __DIR__ . '/../includes/pilates-request-flow.php';
$passed = 0;
function check($label, $value) { global $passed; if (!$value) { fwrite(STDERR, "FAIL: $label\n"); exit(1); } ++$passed; echo "PASS: $label\n"; }
function save_status($slug, $status, $type = 'page', $id = 0) {
    return bodyenergy_memberships_force_draft_on_save(array('post_type'=>$type, 'post_name'=>$slug, 'post_status'=>$status), array('ID'=>$id))['post_status'];
}
check('membership publication blocked', save_status('abbonamenti','publish') === 'draft');
check('formula scheduling blocked', save_status('formule','future') === 'draft');
check('unrelated page unchanged', save_status('contatti','publish') === 'publish');
check('article unaffected', save_status('formule','publish','post') === 'publish');
check('renamed protected page cannot publish', save_status('new-name','publish','page',11) === 'draft');
check('REST exclusion ids', bodyenergy_memberships_excluded_ids(true) === array(11,12));
check('include list filtered', bodyenergy_memberships_exclude_from_args(array('post__in'=>array(11,99)),array(11,12))['post__in'] === array(99));
check('all excluded include list never becomes unrestricted', bodyenergy_memberships_exclude_from_args(array('post__in'=>array(11)),array(11))['post__in'] === array(0));
check('existing exclude list preserved', bodyenergy_memberships_exclude_from_args(array('post__not_in'=>array(99)),array(11))['post__not_in'] === array(99,11));
check('REST direct page blocked', bodyenergy_memberships_block_rest_page(null,null,new RequestStub('/wp/v2/pages/11')) instanceof WP_Error);
check('unrelated REST route unaffected', bodyenergy_memberships_block_rest_page('ok',null,new RequestStub('/wp/v2/posts/11')) === 'ok');
$GLOBALS['edit'] = true;
check('authorized editor permitted', bodyenergy_memberships_block_rest_page('ok',null,new RequestStub('/wp/v2/pages/11')) === 'ok');
check('VideoPress GUID extracted', bodyenergy_home_platinum_guid_from_url('https://videopress.com/v/ABcd1234') === 'ABcd1234');
check('WordPress video GUID extracted', bodyenergy_home_platinum_guid_from_url('https://videos.files.wordpress.com/ABcd1234/clip.mov') === 'ABcd1234');
check('untrusted video host rejected', bodyenergy_home_platinum_guid_from_url('https://videopress.com.example.invalid/v/ABcd1234') === '');
check('http video rejected', bodyenergy_home_platinum_guid_from_url('http://videopress.com/v/ABcd1234') === '');
check('image not treated as video', bodyenergy_home_platinum_attachment_video_data(22) === null);
check('default video retained', bodyenergy_home_platinum_videopress_guid() === 'gUAnLMKw');
$GLOBALS['options']['bodyenergy_home_platinum_video_id'] = 21;
check('selected valid video used', bodyenergy_home_platinum_videopress_guid() === 'ABcd1234');
check('Pilates fallback when contacts absent', bodyenergy_pilates_request_page_url() === 'mailto:bodyenergy.asd@gmail.com');
$GLOBALS['pages']['contatti'] = new WP_Post(30, 'page', 'draft', 'contatti');
check('draft contacts not linked publicly', bodyenergy_pilates_request_page_url() === 'mailto:bodyenergy.asd@gmail.com');
$GLOBALS['pages']['contatti']->post_status = 'publish';
check('published contacts used', bodyenergy_pilates_request_page_url() === 'https://example.invalid/page/30');
check('anonymous form replaced with contact link', str_contains(bodyenergy_render_pilates_request_form(), 'https://example.invalid/page/30') && !str_contains(bodyenergy_render_pilates_request_form(), '<form'));
echo "$passed isolated checks passed. Not a WordPress integration test.\n";
