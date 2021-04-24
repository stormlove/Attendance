<?php

/**
 * @Project Module Attendance
 * @Author ChungNT (chung_vuitinh@yahoo.com)
 * @Createdate 25-2-2016
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

if ( $nv_Request->get_int('save', 'post', 0)==1 ) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $code = strtoupper($nv_Request->get_title('code', 'post', ''));
    $name = mb_convert_case($nv_Request->get_title('name', 'post', ''), MB_CASE_TITLE, "UTF-8");
	$dep = $nv_Request->get_title('dep', 'post', '');
	$pos = $nv_Request->get_title('pos', 'post', '');
	$level = $nv_Request->get_title('level', 'post', '');
	$group = $nv_Request->get_title('group', 'post', '');
	$phucap = $nv_Request->get_int('phucap', 'post', 0);
	$luongcb = $nv_Request->get_int('luongcb', 'post', 0);

    if ( !empty($code) AND !empty($name) AND !empty($dep) AND !empty($pos) AND !empty($group) ) {
		if ($id == 0) {
			$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_employee (code, name, dep, pos, level, groups, phucap, luongcb) VALUES ( :code, :name, :dep, :pos, :level, :group, :phucap, :luongcb)';
			$data_insert = array();
			$data_insert['code'] = $code;
			$data_insert['name'] = $name;
			$data_insert['dep'] = $dep;
			$data_insert['pos'] = $pos;
			$data_insert['level'] = $level;
			$data_insert['group'] = $group;
			$data_insert['phucap'] = $phucap;
			$data_insert['luongcb'] = $luongcb;
			try{
				$db->insert_id($sql, 'id', $data_insert);
				$error = "Thêm thành công!";
			} catch(PDOException $e) {
				$error= $lang_module['error_save'] . "</br>" . $e->getMessage(); 
			}
		} else {
			$stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_employee SET code=:code, name =:name, dep=:dep, pos=:pos, level=:level, groups=:group, phucap=:phucap, luongcb=:luongcb WHERE id =' . $id);
			$stmt->bindParam(':code', $code, PDO::PARAM_STR);
			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
			$stmt->bindParam(':dep', $dep, PDO::PARAM_STR);
			$stmt->bindParam(':pos', $pos, PDO::PARAM_STR);
			$stmt->bindParam(':level', $level, PDO::PARAM_STR);
			$stmt->bindParam(':group', $group, PDO::PARAM_STR);
			$stmt->bindParam(':phucap', $phucap, PDO::PARAM_INT);
			$stmt->bindParam(':luongcb', $luongcb, PDO::PARAM_INT);
			try{
				$stmt->execute();
				$error = "Sửa thành công!";
			} catch(PDOException $e) {
				$error= $lang_module['error_save'] . "</br>" . $e->getMessage(); 
			}
		}
    }else{
		$error = $lang_module['error_empty'];
	}
}

$xtpl = new XTemplate('employee.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('BACK_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('OP', $op);

$scode = $nv_Request->get_title('scode', 'get', '');
$sname = $nv_Request->get_title('sname', 'get', '');
$sdep = $nv_Request->get_title('sdep', 'get', '');
$xtpl->assign('EMPLOYEE_LIST', nv_show_employee_list( $scode, $sname, $sdep ));
//var_dump();die();
if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$sid = $nv_Request->get_int('id', 'get', 0);
$emp_dep = $emp_pos = $emp_group = $emp_level = '';
if ($sid > 0) {
    list($emp_id, $emp_code, $emp_name, $emp_dep, $emp_pos, $emp_group, $emp_level, $emp_phucap, $emp_luongcb, $emp_status) = $db->query('SELECT id, code, name, dep, pos, groups, level, phucap, luongcb, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_employee where id=' . $sid)->fetch(3);
    $lang_module['add_employee'] = $lang_module['edit_employee'];
	$lang_module['btn_add'] = $lang_module['btn_save'];
	$xtpl->assign('id', $emp_id);
	$xtpl->assign('code', $emp_code);
	$xtpl->assign('name', $emp_name);
	$xtpl->assign('dep', $emp_dep);
	$xtpl->assign('pos', $emp_pos);
	$xtpl->assign('group', $emp_group);
	$xtpl->assign('level', $emp_level);
	$xtpl->assign('phucap', $emp_phucap);
	$xtpl->assign('luongcb', $emp_luongcb);
	$xtpl->assign('status', ($emp_status==1)?'checked':'');
	//$xtpl->assign('S_READONLY', 'readonly="readonly"'); #Khóa code
}

######### Load Department combobox #######
$cb_dep = get_dep();
foreach($cb_dep as $d)
{
	$xtpl->assign('DEP', array(
		'code' => $d['code'],
		'name' => $d['name'],
		'selected' => ($emp_dep==$d['code'])?'selected="selected"':''
	));
	$xtpl->parse('main.dep');
	$xtpl->parse('main.depcb');
}
######### Load Position combobox #######
$cb_pos = get_pos();
foreach($cb_pos as $p)
{
	$xtpl->assign('POS', array(
		'code' => $p['code'],
		'name' => $p['name'],
		'selected' => ($emp_pos==$p['code'])?'selected="selected"':''
	));
	$xtpl->parse('main.pos');
}
######### Load Group combobox #######
$cb_group = get_group();
foreach($cb_group as $g)
{
	$xtpl->assign('GROUP', array(
		'code' => $g['code'],
		'name' => $g['name'],
		'selected' => ($emp_group==$g['code'])?'selected="selected"':''
	));
	$xtpl->parse('main.group');
}
######### Load Level combobox #######
$cb_level = get_level();
foreach($cb_level as $l)
{
	$xtpl->assign('LEVEL', array(
		'id' => $l['id'],
		'name' => $l['name'],
		'selected' => ($emp_level==$l['id'])?'selected="selected"':''
	));
	$xtpl->parse('main.level');
}
#########

$xtpl->assign('LANG', $lang_module);
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
