<?php

if (! defined('NV_IS_MOD_ATTENDANCE') ) {
    die('Stop!!!');
}
$page_title = $lang_module['report_list'];
$rp_submit = $nv_Request->get_int('report_submit', 'post', 0);
$error=array();
$import = $nv_Request->get_int('import_submit', 'post', '');
$add_count=0;

if ( $rp_submit==1  ) {
$id = $nv_Request->get_int('id', 'post', 0);
$cat = $nv_Request->get_title('cat', 'post', '');
$_score = $nv_Request->get_int('score', 'post', 0);
$_meal = $nv_Request->get_int('meal', 'post', 0);
$score = in_array($cat, $cat_array) ? $_score : 0;
$meal = in_array($cat, $cat_array) ? $_meal : 0;

	if ($id == 0) {
		$prefix = strtoupper($nv_Request->get_title('prefix', 'post', ''));
		$_code = strtoupper($nv_Request->get_title('code', 'post', ''));
		$code = $prefix . $_code;
		$_date = $nv_Request->get_title('date', 'post', '');
		if(!empty($code) AND !empty($_date) AND !empty($cat) ){
			$date='';
			if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_date, $d)) {
				$date = mktime(0, 0, 0, $d[2], $d[1], $d[3]);
			}
	# Kiem tra cong nhan co thuoc don vi?
			if( !check_employee_exsit($code,$attendance_user) ){
				$error = $code . $lang_module['not_existed'];
	# Kiem tra cong nhan da cham cong chua?
			}elseif ( check_row_exsit($code, $date) ) {
				$error = $code . $lang_module['is_dated'] . nv_date('d/m/Y',$date);
			}else{
				$sql = "SELECT pos, level, groups, dep, luongcb, phucap FROM " . NV_PREFIXLANG . "_" . $module_data . "_employee WHERE status=0 AND code='" . $code . "'";
				$emp=$db->query($sql)->fetch();
				if($emp){
					$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_row (code, date, cat, score, meal, pos, level, groups, dep, luongcb, phucap, addtime) VALUES ( :code, :date, :cat, :score, :meal, :pos, :level, :groups, :dep, :luongcb, :phucap, :addtime)';
					$data_insert = array();
					$data_insert['code'] = $code;
					$data_insert['date'] = $date;
					$data_insert['cat'] = $cat;
					$data_insert['score'] = $score;
					$data_insert['meal'] = $meal;
					$data_insert['pos'] = $emp['pos'];
					$data_insert['level'] = $emp['level'];
					$data_insert['groups'] = $emp['groups'];
					$data_insert['dep'] = $emp['dep'];
					$data_insert['luongcb'] = $emp['luongcb'];
					$data_insert['phucap'] = $emp['phucap'];
					$data_insert['addtime'] = $user_info['username'] . "|" . nv_date("H:i d/m/Y", NV_CURRENTTIME);
					try{
						$db->insert_id($sql, 'id', $data_insert);
						$error = "Thêm thành công!";
					}catch(PDOException $e) {
						$error= $lang_module['error_save'] . "</br>" . $e->getMessage(); 
					}
				}
			}
		}else{
			$error = $lang_module['error_empty'];
		}
	}else{
		if(!empty($cat)){	
			$edittime= $user_info['username'] . "|" . nv_date("H:i d/m/Y", NV_CURRENTTIME);
			$stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_row SET cat = :cat, score = :score, meal = :meal, edittime = :edittime WHERE id =' . $id);
			$stmt->bindParam(':cat', $cat, PDO::PARAM_STR);
			$stmt->bindParam(':score', $score, PDO::PARAM_INT);
			$stmt->bindParam(':meal', $meal, PDO::PARAM_INT);
			$stmt->bindParam(':edittime', $edittime, PDO::PARAM_INT);
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
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('SUBMIT_ACT', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('BACK_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('S_READONLY', 'readonly="readonly"');
$xtpl->assign('DEPT', $attendance_user );
$xtpl->assign('CAT_ARRAY', implode("','",$cat_array));
$xtpl->assign('ROW_LIST', nv_show_row_list());
//	$xtpl->assign('SCODE', $scode );
//	$xtpl->assign('FROM', $from );
//	$xtpl->assign('TO', $to );

$r_cat=$r_meal='';
$cat_array=array('K','2K','3K','HK','LK');
$sid = $nv_Request->get_int('id', 'get', 0);
	
if ($sid > 0) {
	list($r_id, $r_code, $r_name, $r_date, $r_cat, $r_score, $r_meal) = $db->query('SELECT r.id, r.code, e.name, r.date, r.cat, r.score, r.meal FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row r JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_employee e ON r.code=e.code where r.id=' . $sid)->fetch(3);
	//var_dump($r_edittime);die();
	$lang_module['add_report'] = $lang_module['edit_report'];
	$lang_module['btn_add'] = $lang_module['btn_save'];
	$xtpl->assign('id', $r_id);
	$xtpl->assign('code', substr($r_code,2));
	$xtpl->assign('name', $r_name );
	$xtpl->assign('cat', $r_cat);
	$xtpl->assign('date', nv_date('d/m/Y',$r_date));
	$xtpl->assign('score', $r_score);
	$xtpl->assign('meal', $r_meal);
	$xtpl->assign('D_DISABLE', ':disable');
	$xtpl->assign('C_DISABLE', 'disabled');
	$xtpl->assign('C_READONLY', 'readonly="readonly"');
	$xtpl->assign('M_DISABLE', in_array($r_cat,$cat_array)?'':'disabled');
	$xtpl->assign('S_READONLY', in_array($r_cat,$cat_array)?'':'readonly="readonly"');
}
######### Load Group combobox #######
$cb_group = get_group();
foreach($cb_group as $g)
{
	$xtpl->assign('SGROUP', array(
		'code' => $g['code'],
		'name' => $g['name']
	));
	$xtpl->parse('main.sgroup');
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
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';