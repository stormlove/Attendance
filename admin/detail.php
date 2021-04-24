<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 24-06-2011 10:35
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}
$page_title = $lang_module['info'];
$from = $nv_Request->get_title('date', 'get', nv_date("d/m/Y",NV_CURRENTTIME) );
$dep = $nv_Request->get_title('dep', 'get', "" );
$sql= "";
$chenhlech=$total=$date=0;
if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $from, $f)) {
	$date = mktime(0, 0, 0, $f[2], $f[1], $f[3]);
}

$xtpl = new XTemplate('detail.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DAY', $from);
$xtpl->assign('DEPT', $dep);

$etotal = $db->query("SELECT count(id) as total, dep FROM ".NV_PREFIXLANG."_". $module_data."_employee WHERE status=0 AND dep='".$dep."'")->fetchColumn();
$xtpl->assign('TODAY', "ngày ".nv_date("d/m/Y", NV_CURRENTTIME));
$xtpl->assign('ETOTAL', $etotal);
$sql = "SELECT cat, count(cat) as value FROM ".NV_PREFIXLANG."_".$module_data."_row WHERE dep='".$dep."' AND date=".$date." GROUP BY cat";
$result = $db->query($sql)->fetchAll();
//$xtpl->assign('ROW', 0);
//var_dump($result);die();
if( $result ){
	$i=0;
	foreach ( $result as $row ){
		$total+=$row['value'];
		$xtpl->assign('STT', ++$i);
		$xtpl->assign('ROW', $row);
		$xtpl->parse('main.loop');
	}
	$chenhlech=$total-$etotal;
}
$xtpl->assign('TOTAL', $total);
$xtpl->assign('CL', $chenhlech);
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';