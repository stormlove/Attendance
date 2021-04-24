<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if (! defined('NV_MAINFILE') ) {
    die('Stop!!!');
}
if (! defined('NV_IS_AJAX')) {
    die('Wrong URL');
}

$code = $nv_Request->get_title('code', 'post', '');
$dep = $nv_Request->get_title('dep', 'post', '');
if ( !empty($code) ) {
	echo get_name($code, $dep);
}