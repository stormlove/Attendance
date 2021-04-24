<?php

/**
 * @Project Module Attendance
 * @Author ChungNT (chung_vuitinh@yahoo.com)
 * @Createdate 25-2-2016
 */
 
if (! defined('NV_IS_MOD_ATTENDANCE') ) {
    die('Stop!!!');
}

$error=array();

	//$page_title = $lang_module['report_list'];
	$rp_submit = $nv_Request->get_int('report_submit', 'post', 0);
	$import = $nv_Request->get_int('import_submit', 'post', '');
	$add_count=0;
	
	if ( $rp_submit==1  ) {
	$id = $nv_Request->get_int('id', 'post', 0);
	$rate = $nv_Request->get_title('rate', 'post', '');
		if ($id == 0) {
			$prefix = strtoupper($nv_Request->get_title('prefix', 'post', ''));
			$_code = strtoupper($nv_Request->get_title('code', 'post', ''));
			$code = $prefix . $_code;
			$_date = $nv_Request->get_title('date', 'post', '');

			if(!empty($code) AND !empty($_date) AND !empty($rate) ){
				$date='';
				if (preg_match('/([0-9]{1,2})\/([0-9]{4})$/', $_date, $d)) {
					$date = mktime(0, 0, 0, $d[1], 1, $d[2]);
				}
				//die('date:'.$date);
				# Kiem tra cong nhan co thuoc don vi?
				if( !check_employee_exsit($code,$attendance_user) ){
					$error = $code . $lang_module['not_existed'];
				# Kiem tra cong nhan da cham cong chua?
				}elseif ( check_row_rate_exsit($code, $date,$attendance_user) ) {
					$error = $code . $lang_module['is_dated'] . nv_date('m/Y',$date);
				}else{
						$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_rate_row (code, dep, date, rate) VALUES ( :code, :dep, :date, :rate)';
						$data_insert = array();
						$data_insert['code'] = $code;
						$data_insert['date'] = $date;
						$data_insert['rate'] = $rate;
						$data_insert['dep'] = $attendance_user;
						try{
							$db->insert_id($sql, 'id', $data_insert);
							$error = "Thêm thành công!";
						}catch(PDOException $e) {
							$error= $lang_module['error_save'] . "</br>" . $e->getMessage(); 
						}
				}
			}else{
				$error = $lang_module['error_empty'];
			}
		}else{
			if(!empty($rate)){	
				$stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rate_row SET rate = :rate WHERE id =' . $id);
				$stmt->bindParam(':rate', $rate, PDO::PARAM_STR);
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

	$xtpl = new XTemplate('rating.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
	$xtpl->assign('GLANG', $lang_global);
	$xtpl->assign('URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
	$xtpl->assign('S_READONLY', 'readonly="readonly"');
	$xtpl->assign('DEPT', $attendance_user );

	$scode = $nv_Request->get_title('s_code', 'get,post', '');
	$from = $nv_Request->get_title('from', 'get,post', nv_date("m/Y", NV_CURRENTTIME));
	$xtpl->assign('ROW_LIST', nv_show_rate_row_list($scode,$attendance_user,$from) );
 
	$sid = $nv_Request->get_int('id', 'get', 0);
	if ($sid > 0) {
		list($r_id, $r_code, $r_name, $r_dep, $r_date, $r_rate) = $db->query('SELECT r.id, r.code, r.dep, e.name, r.date, r.rate FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rate_row r JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_employee e ON r.code=e.code where r.id=' . $sid)->fetch(3);
		//var_dump($r_edittime);die();
		$lang_module['add_report'] = $lang_module['edit_report'];
		$lang_module['btn_add'] = $lang_module['btn_save'];
		$xtpl->assign('id', $r_id);
		$xtpl->assign('code', substr($r_code,2));
		$xtpl->assign('name', $r_name );
		$xtpl->assign('dep', $r_dep );
		$xtpl->assign('date', nv_date('m/Y',$r_date));
		$xtpl->assign('rate', $r_rate);
		$xtpl->assign('D_DISABLE', ':disable');
		$xtpl->assign('C_DISABLE', 'disabled');
		$xtpl->assign('C_READONLY', 'readonly="readonly"');
	}

	######### Load Rate combobox #######
	$cb_rate = get_rate();
	foreach($cb_rate as $d)
	{
		$xtpl->assign('RATE', array(
			'name' => $d['name'],
			'selected' => ($r_rate==$d['name'])?'selected="selected"':''
		));
		$xtpl->parse('main.rate');
	}
	##############
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