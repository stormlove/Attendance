<?php

/**
 * @Project Module Attendance
 * @Author ChungNT (chung_vuitinh@yahoo.com)
 * @Createdate 25-2-2016
 */

if (! defined('NV_IS_MOD_ATTENDANCE') ) {
    die('Stop!!!');
}

if ( $nv_Request->get_int('save', 'post', 0)==1 ) {
    $id = $nv_Request->get_int('id', 'post', 0);
	$group = $nv_Request->get_title('group', 'post', '');

    if ( !empty($id) AND !empty($group) ) {
		$stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_employee SET groups=:group WHERE id =' . $id);
		$stmt->bindParam(':group', $group, PDO::PARAM_STR);
		try{
			$stmt->execute();
			$error = "Sửa thành công!";
			header( "refresh:1;url=".NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op ); 
		} catch(PDOException $e) {
			$error= $lang_module['error_save'] . "</br>" . $e->getMessage(); 
		}
	}
}

$xtpl = new XTemplate('employee.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('EMPLOYEE_LIST', nv_show_employee_list());
//var_dump();die();
if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$sid = $nv_Request->get_int('id', 'get', 0);
$emp_group = '';
if ($sid > 0) {
    list($emp_id, $emp_code, $emp_name, $emp_group) = $db->query('SELECT id, code, name, groups FROM ' . NV_PREFIXLANG . '_' . $module_data . '_employee where id=' . $sid)->fetch(3);
	$xtpl->assign('id', $emp_id);
	$xtpl->assign('code', $emp_code);
	$xtpl->assign('name', $emp_name);
	$xtpl->assign('group', $emp_group);
######### Load Group combobox #######
$cb_group = get_group();
foreach($cb_group as $g)
{
	$xtpl->assign('GROUP', array(
		'code' => $g['code'],
		'name' => $g['name'],
		'selected' => ($emp_group==$g['code'])?'selected="selected"':''
	));
	$xtpl->parse('main.edit.group');
}
#########
	$xtpl->parse('main.edit');
}


$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
