<?php

/**
 * @Project Module Attendance
 * @Author ChungNT (chung_vuitinh@yahoo.com)
 * @Createdate 25-2-2016
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}
/* if (! defined('NV_IS_AJAX')) {
    die('Wrong URL');
} */

$id = $nv_Request->get_string('id', 'post', '');
$mod = $nv_Request->get_string('mod', 'post', '');
$list = $nv_Request->get_string('list', 'post', '');

$contents = 'NO_' . $id;

if ( !empty($mod) ) {
	if ( !empty($id) ) {
		$db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $mod . ' WHERE id=' . $id);
		$contents = 'OK_' . $lang_module['del_ok'];
	}
	if( !empty($list) ){
		$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $mod . ' WHERE id IN (' . $list . ")");
		$contents = 'OK_' . $lang_module['del_ok'];
	}
	if($mod=='catalogue' OR $mod=='department'){
		fix_weight($mod);
	}
}

echo $contents;
exit();