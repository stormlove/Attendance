<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 24-06-2011 10:35
 */

$mod = $nv_Request->get_string('mod', 'post', '');
$list = $nv_Request->get_string('list', 'post', '');
if ( !empty($mod) AND !empty($list) ) {
	if( !is_array($list) ){die(">>>".$n);
		list($code, $date) = explode("_",$list);
		$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $mod . ' WHERE code="'.$code.'" AND date='.$date);
		$contents = 'OK_' . $lang_module['del_ok'];
	}else{
		$n=sizeof($list);
		for($i=0; $i<$n; $n){
			list($code, $date) = explode("_",$list[$n]);
			$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $mod . ' WHERE code="'.$code.'" AND date='.$date);
		}
		$contents = 'OK_' . $lang_module['del_ok'];
	}
	if($mod=='catalogue' OR $mod=='department'){
		fix_weight($mod);
	}
}
#######3
if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}
$page_title = $lang_module['report_list'];
$error = '';
$code_cat_array = get_cat("code");
if ( $nv_Request->get_int('report_submit', 'post', 0)==1 ) {
	if ( $attendance_user_edit==1 ) {
		$add = $nv_Request->get_string('add', 'post', '');
		$cat = $nv_Request->get_string('cat', 'post', '');
		$_score = $nv_Request->get_int('score', 'post', 0);
		$_meal = $nv_Request->get_int('meal', 'post', 0);
		$score = in_array($cat, $cat_array) ? $_score : 0;
		$meal = in_array($cat, $cat_array) ? $_meal : 0;
		$code = strtoupper($nv_Request->get_string('prefix', 'post', '')) . strtoupper($nv_Request->get_string('code', 'post', ''));
		$ecode = strtoupper($nv_Request->get_string('add', 'post', ''));
		$_date = $nv_Request->get_string('date', 'post', '');
		$wtime=$admin_info['username'] . "|" . nv_date("H:i d/m/Y", NV_CURRENTTIME);
		if(!empty($code) AND !empty($_date) AND !empty($cat) ){
				if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_date, $d)) {
					$date = mktime(0, 0, 0, $d[2], $d[1], $d[3]);
				}
		}else{
				$error = $lang_module['error_empty'];
		}		
		if (empty($add)) {
# Kiem tra cong nhan co khong?
			if( !check_employee_exsit($code) ){
				$error = $code . $lang_module['not_existed'];
# Kiem tra cong nhan da cham cong chua?
				}elseif ( check_row_exsit($code, $date) ) {
					$error = $code . $lang_module['is_dated'] . nv_date('d/m/Y',$date);
				}elseif ( !in_array($cat, array_column($code_cat_array, 'code')) ) {
					$error = $cat . $lang_module['invalid'];
				}else{
					$sql = "SELECT pos, level, groups, dep, luongcb, phucap FROM " . NV_PREFIXLANG . "_" . $module_data . "_employee WHERE status=0 AND code='" . $code . "'";
					$emp=$db->query($sql)->fetch();
					if($emp){
						$sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_row (code, date, cat, score, meal, pos, level, groups, dep, luongcb, phucap, addtime) VALUES ( :code, :date, :cat, :score, :meal, :pos, :level, :groups, :dep, :luongcb, :phucap, :addtime)');
						$sth->bindParam(':code', $code, PDO::PARAM_STR);
						$sth->bindParam(':date', $date, PDO::PARAM_INT);
						$sth->bindParam(':cat', $cat, PDO::PARAM_STR);
						$sth->bindParam(':score', $score, PDO::PARAM_INT);
						$sth->bindParam(':meal', $meal, PDO::PARAM_INT);
						$sth->bindParam(':pos', $emp['pos'], PDO::PARAM_STR);
						$sth->bindParam(':level', $emp['level'], PDO::PARAM_STR);
						$sth->bindParam(':groups', $emp['groups'], PDO::PARAM_STR);
						$sth->bindParam(':dep', $emp['dep'], PDO::PARAM_STR);
						$sth->bindParam(':luongcb', $emp['luongcb'], PDO::PARAM_INT);
						$sth->bindParam(':phucap', $emp['phucap'], PDO::PARAM_INT);
						$sth->bindParam(':addtime', $wtime, PDO::PARAM_STR);
						try{
							$sth->execute();
							$error = "Thêm thành công!";
						}catch(PDOException $e) {
							$error= $lang_module['error_save'] . "</br>" . $e->getMessage(); 
						}
					}
				}
		}else{
			if( in_array($cat, array_column($code_cat_array, 'code')) ) {
				$stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_row SET cat = :cat, score = :score, meal = :meal, edittime = :edittime WHERE code="'.$ecode.'" AND date=' . $date);
				$stmt->bindParam(':cat', $cat, PDO::PARAM_STR);
				$stmt->bindParam(':score', $score, PDO::PARAM_INT);
				$stmt->bindParam(':meal', $meal, PDO::PARAM_INT);
				$stmt->bindParam(':edittime', $wtime, PDO::PARAM_INT);
				try{
					$stmt->execute();
					$error = "Sửa thành công!";
				} catch(PDOException $e) {
					$error= $lang_module['error_save'] . "</br>" . $e->getMessage(); 
				}
			}else{
				$error = $lang_module['error_empty'];
			}
		}
	}else{
		$error = $lang_module['no_permission'];
	}
}
$xtpl = new XTemplate('add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('SUBMIT_ACT', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('BACK_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('CAT_ARRAY', implode("','",$cat_array));
$scode = $nv_Request->get_string('scode', 'get,post', '');
$sdep = $nv_Request->get_string('sdep', 'get,post', '');
$scat = $nv_Request->get_string('scat', 'get,post', '');
$from = $nv_Request->get_string('from', 'get,post', '');
$to = $nv_Request->get_string('to', 'get,post', '');
$xtpl->assign('ROW_LIST', nv_show_row_list( $scode, $sdep, $from, $to, $scat));

$r_cat=$r_meal='';
$code = $nv_Request->get_string('code', 'get', '');
$date = $nv_Request->get_int('date', 'get', 0);
if ( !empty($code) AND $date>0 ) {
    list($r_code, $r_name, $r_date, $r_cat, $r_score, $r_meal) = $db->query('SELECT r.code, e.name, r.date, r.cat, r.score, r.meal FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row r JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_employee e ON r.code=e.code WHERE r.code="'.$code.'" AND r.date='.$date)->fetch(3);
    $lang_module['add_report'] = $lang_module['edit_report'];
	$lang_module['btn_add'] = $lang_module['btn_save'];
	$xtpl->assign('ecode', $code);
	$_code=explode("-",$code);
	$xtpl->assign('code', $_code[1]);
	$xtpl->assign('name', $r_name);
	$xtpl->assign('cat', $r_cat);
	$xtpl->assign('date', nv_date('d/m/Y',$r_date));
	$xtpl->assign('score', $r_score);
	$xtpl->assign('meal', $r_meal);
	$xtpl->assign('D_DISABLE', ':disable');
	$xtpl->assign('C_DISABLE', 'selected="true" disabled');
	$xtpl->assign('C_READONLY', 'readonly="readonly"');
	$xtpl->assign('M_DISABLE', in_array($r_cat,$cat_array)?'':'disabled');
	$xtpl->assign('S_READONLY', in_array($r_cat,$cat_array)?'':'readonly="readonly"');
}
######### Load Department combobox #######
$cb_dep = get_dep();
foreach($cb_dep as $d)
{
	$xtpl->assign('DEP', array(
		'code' => $d['code'],
		'name' => $d['name']
	));
	$xtpl->parse('main.dep');
}
######### Load combobox Catalogue #######
$cb_cat = get_cat();
foreach($cb_cat as $c)
{
	$xtpl->assign('CAT', array(
		'code' => $c['code'],
		'name' => $c['code'] . ": " . $c['name'],
		'selected' => ($r_cat==$c['code'])?'selected="selected"':''
	));
	$xtpl->assign('SCAT', array(
		'code' => $c['code'],
		'name' => $c['code'] . ": " . $c['name'],
		'selected' => ($r_cat==$c['code'])?'selected="selected"':''
	));
	$xtpl->parse('main.cat');
	$xtpl->parse('main.scat');
}
######### Load combobox Meal #######
$cb_meal = get_meal();
foreach($cb_meal as $m)
{
	$xtpl->assign('MEAL', array(
		'money' => $m['money'],
		'selected' => ($r_meal==$m['money'])?'selected="selected"':''
	));
	$xtpl->parse('main.meal');
}
##########
if( !empty($error) ){
	$xtpl->assign('ERROR', $error);
	$xtpl->parse('main.error');
}
$xtpl->assign('LANG', $lang_module);
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';