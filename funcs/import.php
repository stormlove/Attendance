<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 24-06-2011 10:35
 */

if (! defined('NV_IS_MOD_ATTENDANCE') ) {
    die('Stop!!!');
}

$page_code = $lang_module['import'];
$error=array();
$note='';
$import = $nv_Request->get_int('import', 'post', '');
$add_count=0;
$cat_array=array('K','2K','3K','HK','LK');
if ($import==1) {
	$data = $nv_Request->get_title( 'data', 'post', '' );
	if( !empty($data) AND is_uploaded_file($_FILES['fileupload']['tmp_name']) ){
		$upload = new NukeViet\Files\Upload($global_config['file_allowed_ext'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
		if ( ! file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_upload ) ){
			nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $module_upload );
		}
		$upload_info = $upload->save_file($_FILES['fileupload'], NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload, false);
		//var_dump($upload_info);die();
		@unlink($_FILES['fileupload']['tmp_name']);
		
		if ( empty($upload_info['error']) AND $upload_info['ext']=="xls" AND $upload_info['mime']=='application/vnd.ms-excel' ){
			require_once NV_ROOTDIR . '/includes/PHPExcel.php';
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$objPHPExcel = $objReader->load($upload_info['name']);
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,false,true,true);
			$n=count($sheetData);
			$add_count=0;
			//var_dump($sheetData);die();
			if($data=='attendance'){
				$sql = "";
				for($i=2; $i<=$n;$i++){
					$err=false;
					$code=trim($sheetData[$i]['B']);
					$date = $_date=trim($sheetData[$i]['C']);
					$cat=trim($sheetData[$i]['D']);
					$score = in_array($cat, $cat_array ) ? trim($sheetData[$i]['E']) : 0;
					$meal = in_array($cat, $cat_array ) ? trim($sheetData[$i]['F']) : 0;

					if( !empty($code) AND !empty($date) AND !empty($cat) ) {
						if( !check_employee_exsit($code,$attendance_user) ) {
							$error[] = $code . $lang_module['not_existed'] . "<br/>";
							$err=true;
						}
						//die($_date);
						if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_date, $d)) {
							$date = mktime(0, 0, 0, $d[2], $d[1], $d[3]);
							if ( check_row_exsit($code, $date) ) {
								$error[] = $code . $lang_module['is_dated'] . $_date . "<br/>";
								$err=true;
							}
						}else{
							$error[] = "Lỗi định dạng ngày tháng! dòng: " . $i . "<br/>";
							$err=true;
						}
						
						if(!$err){
							$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_row (code, date, cat, score, meal, addtime) 
									VALUES (:code, :date, :cat, :score, :meal, :addtime)';
							$data_insert = array();
							$data_insert['code'] = $code;
							$data_insert['date'] = $date;
							$data_insert['cat'] = $cat;
							$data_insert['score'] = $score;
							$data_insert['meal'] = $meal;
							$data_insert['addtime'] = $user_info['username'] . " | " . nv_date("H:i d/m/Y", NV_CURRENTTIME);
							//var_dump($data_insert);
							if ($id=$db->insert_id($sql, 'id', $data_insert)) {
								$db->query("UPDATE nv4_vi_attendance_row r
									JOIN nv4_vi_attendance_employee e ON r.code = e.code
									SET r.dep=e.dep, r.pos = e.pos, r.level = e.level, r.groups = e.groups, r.luongcb = e.luongcb, r.phucap = e.phucap
									WHERE r.id=" . $id);
								$add_count++;
							}
						}
					}
				}
			}
			if($data=='rating'){
				$sql = "";
				for($i=2; $i<=$n;$i++){
					$err=false;
					$code=trim($sheetData[$i]['B']);
					$date = $_date=trim($sheetData[$i]['C']);
					$rate=trim($sheetData[$i]['D']);
					
					if( !empty($code) AND !empty($date) AND !empty($rate) ) {
						if( !check_employee_exsit($code,$attendance_user) ) {
							$error[] = $code . $lang_module['not_existed'] . "<br/>";
							$err=true;
						}
						//die($_date);
						if (preg_match('/^([0-9]{1,2})\/([0-9]{4})$/', $_date, $d)) {
							$date = mktime(0, 0, 0, $d[1], 1, $d[2]);
							if ( check_row_rate_exsit($code, $date) ) {
								$error[] = $code . $lang_module['is_dated'] . $_date . "<br/>";
								$err=true;
							}
						}else{
							$error[] = "Lỗi định dạng ngày tháng! dòng: " . $i . "<br/>";
							$err=true;
						}
						
						if(!$err){
							$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_rate_row (code, date, rate) VALUES (:code, :date, :rate)';
							$data_insert = array();
							$data_insert['code'] = $code;
							$data_insert['date'] = $date;
							$data_insert['rate'] = $rate;
//var_dump($data_insert);
							if ($id=$db->insert_id($sql, 'id', $data_insert)) {
								$db->query("UPDATE nv4_vi_attendance_rate_row r
									JOIN nv4_vi_attendance_employee e ON r.code = e.code
									SET r.dep=e.dep	WHERE r.id=" . $id);
								$add_count++;
							}
						}
					}
				}
			}
			// Del file uploaded
			nv_deletefile($upload_info['name'], false);
		}else{
			$error[] = $upload_info['error'] . "</br>"  . $lang_module['is_xls'];
		}
	}else{
		$error[] = $lang_module['error_empty'];
	}
}

$xtpl = new XTemplate('import.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('PATH', NV_BASE_SITEURL . NV_ASSETS_DIR . '/' .  $module_upload );
//var_dump($module_upload);die();
foreach($error as $errors)
{
	if( !empty($errors) ){
		$xtpl->assign( 'ERROR', $errors );
		$xtpl->parse ( 'main.error' );
	}
}
if( $add_count>0 ){
	$xtpl->assign('NOTE', $lang_module['import_successful'] . $add_count);
	$xtpl->parse('main.note');
}
$xtpl->assign('OP', $op);

$xtpl->parse('main');
$contents .= $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';