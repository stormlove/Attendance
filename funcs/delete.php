<?php

/**
 * @Project Module Attendance
 * @Author ChungNT (chung_vuitinh@yahoo.com)
 * @Createdate 25-2-2016
 */

if (! defined('NV_MAINFILE') ) {
    die('Stop!!!');
}
if (! defined('NV_IS_AJAX')) {
    die('Wrong URL');
}

$id = $nv_Request->get_int('id', 'post', 0);
$list = $nv_Request->get_string('list', 'post', '');

$contents = 'NO_' . $id;

if ( $id > 0 ) {
	$db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row WHERE id=' . $id);
	$contents = 'OK_' . $lang_module['del_ok'];
}
if( !empty($list) ){
	$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row WHERE id IN (' . $list . ")");
	$contents = 'OK_' . $lang_module['del_ok'];
}
echo $contents;
exit();