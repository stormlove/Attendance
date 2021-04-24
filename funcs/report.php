<?php

/**
 * @Project Module Attendance
 * @Author ChungNT (chung_vuitinh@yahoo.com)
 * @Createdate 25-2-2016
 */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
if (PHP_SAPI == 'cli') die('Only be run from a Web Browser');
##
if (! defined('NV_IS_MOD_ATTENDANCE') ) {
    die('Stop!!!');
}
$error = array();
$cat_array=array('K', '2K', '3K', 'LK', 'L2K', 'L3K', 'HK', 'H2K', 'H3K', 'PK', 'PtK');
if(defined('NV_IS_USER')){
	if($nv_Request->isset_request('report', 'post')){
		$mod = $nv_Request->get_title('report', 'post', '');
		$_from = $nv_Request->get_title('from', 'post', '');
		$_to = $nv_Request->get_title('to', 'post', '');
		$dep = $attendance_user;
		$where = "WHERE r.dep='".$dep."'";
		if (preg_match('/^([0-9]{1,2})\/([0-9]{4})$/', $_from, $d)) {
			$from=mktime(0, 0, 0, $d[1], 1, $d[2]);
			$to=mktime(0, 0, 0, $d[1]+1, 0, $d[2]);
			$where .= " AND (r.date BETWEEN " . $from . " AND " . $to . ")";
		}else{
			$error[] = "Hãy nhập ngày tháng!";
		}
		
		if( empty($error) ){
			if ($mod == 'mau01'){
				$total=$group=array();
				$sql = 'SELECT g.name as gname, r.code, r.date, r.cat, r.score, r.meal, e.name, p.name as pos, r.level, r.luongcb, r.phucap, t.rate FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row r INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_employee e ON r.code=e.code LEFT JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_group g ON r.groups=g.code LEFT JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_position p ON r.pos=p.code LEFT JOIN (SELECT rr.code, rr.rate FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rate_row rr WHERE rr.dep="'.$dep.'" AND rr.date='.$from.') t ON r.code=t.code '.$where.' ORDER BY g.weight,p.weight ASC';
//die($sql);
				$result = $db->query($sql)->fetchAll();
				if( $result ){
					foreach ( $result as $row ){
						$group[$row['gname']][$row['code']][date('j', $row['date'])] = array(
							'cat'=>$row['cat'],
							'score'=>$row['score'],
							'meal'=>$row['meal'],
							'name'=>$row['name'],
							'pos'=>$row['pos'],
							'level'=>$row['level'],
							'luongcb'=>$row['luongcb'],
							'phucap'=>$row['phucap'],
							'rate'=>$row['rate']
						);
					}
//var_dump($group);die();
					include NV_ROOTDIR . '/includes/PHPExcel.php';
					$objPHPExcel = new PHPExcel();
					$objPHPExcel->getProperties()->setCreator("portal.halongcoal.com.vn");
					$objPHPExcel->getProperties()->setTitle("Quản lý công điểm");
					$objReader = PHPExcel_IOFactory::createReader('Excel5');
					$tmp_file = NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/' . $module_upload . "/Mau01.xls";
					if(file_exists($tmp_file)){
						$objPHPExcel = $objReader->load($tmp_file);
						$objPHPExcel->setActiveSheetIndex(0) -> setCellValue('A2', mb_convert_case(get_depname($dep), MB_CASE_UPPER, "UTF-8") )
							->setCellValue('L2', "THÁNG " . $_from );
					}
					$i=8;
					$dayOfMonth=date('t',$from);
					foreach( $group as $gname=>$emp){
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $i, mb_convert_case($gname, MB_CASE_UPPER, "UTF-8") )
							->getStyle('C' . $i)->getFont()->setBold(true);
						++$i;
						$j=$i;
						$totalRow[]=$rowSum=count($emp)+$j+1;
						$stt=0;
						foreach($emp as $code=>$day){
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, ++$stt) #STT
								->setCellValue('B' . $i, $code) #Code
								->setCellValue('H' . $i, "=SUM(F" . $i . ":G" . $i . ")");
							for ($n=1; $n<=$dayOfMonth; $n++) {
								if( isset($day[$n]['cat']) ){
									$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, $day[$n]['name'] )
										->setCellValue('D'.$i, $day[$n]['pos'] )
										->setCellValue('E'.$i, $day[$n]['level'] )
										->setCellValue('F'.$i, $day[$n]['luongcb'] )
										->setCellValue('G'.$i, $day[$n]['phucap'] )
										->setCellValue('BS' .$i, '=COUNTIF($I'.$i.':$BR'.$i.',"K")+COUNTIF($I'.$i.':$BR'.$i.',"2K")*2+COUNTIF($I'.$i.':$BR'.$i.',"3K")*3+COUNTIF($I'.$i.':$BR'.$i.',"HK")+COUNTIF($I'.$i.':$BR'.$i.',"LK")+COUNTIF($I'.$i.':$BR'.$i.',"L2K")*2+COUNTIF($I'.$i.':$BR'.$i.',"L3K")*3+COUNTIF($I'.$i.':$BR'.$i.',"PtK")+COUNTIF($I'.$i.':$BR'.$i.',"PK")+COUNTIF($I'.$i.':$BR'.$i.',"H2K")*2+COUNTIF($I'.$i.':$BR'.$i.',"H3K")*3')
										->setCellValue('BT' . $i, '=SUM(J'.$i.':BR'.$i.')')
										->setCellValue('BU' . $i, $day[$n]['rate'] )
										->setCellValue('BV' . $i, '=IF($BU'.$i.'="A",1.1,IF($BU'.$i.'="B",1.05,1))*$BT'.$i)
										->setCellValue('BW' . $i, '=COUNTIF($I'.$i.':$BR'.$i.',"P")+COUNTIF($I'.$i.':$BR'.$i.',"Pt")+COUNTIF($I'.$i.':$BR'.$i.',"H")+COUNTIF($I'.$i.':$BR'.$i.',"R")+COUNTIF($I'.$i.':$BR'.$i.',"T")+COUNTIF($I'.$i.':$BR'.$i.',"L")+COUNTIF($I'.$i.':$BR'.$i.',"ĐD")+COUNTIF($I'.$i.':$BR'.$i.',"VN")+COUNTIF($I'.$i.':$BR'.$i.',"TT")+COUNTIF($I'.$i.':$BR'.$i.',"QS")+COUNTIF($I'.$i.':$BR'.$i.',"N")+COUNTIF($I'.$i.':$BR'.$i.',"TQ")+COUNTIF($I'.$i.':$BR'.$i.',"HK")+COUNTIF($I'.$i.':$BR'.$i.',"LK")+COUNTIF($I'.$i.':$BR'.$i.',"L2K")+COUNTIF($I'.$i.':$BR'.$i.',"L3K")+COUNTIF($I'.$i.':$BR'.$i.',"PtK")+COUNTIF($I'.$i.':$BR'.$i.',"PK")+COUNTIF($I'.$i.':$BR'.$i.',"H2K")+COUNTIF($I'.$i.':$BR'.$i.',"H3K")')
										->setCellValue('BX' . $i, '=COUNTIF($I'.$i.':$BR'.$i.',"Ro")+COUNTIF($I'.$i.':$BR'.$i.',"NB")+COUNTIF($I'.$i.':$BR'.$i.',"Ntt")')
										->setCellValue('BY' . $i, '=COUNTIF($I'.$i.':$BR'.$i.',"VLD")+COUNTIF($I'.$i.':$BR'.$i.',"BĐN")')
										->setCellValue('BZ' . $i, '=COUNTIF($I'.$i.':$BR'.$i.',"Ô")+COUNTIF($I'.$i.':$BR'.$i.',"Cô")+COUNTIF($I'.$i.':$BR'.$i.',"TS")')
										->setCellValueByColumnAndRow($n*2+6, $i, $day[$n]['cat'] )
										->setCellValueByColumnAndRow($n*2+7, $i, $day[$n]['score'] );
									## Total
									$colLetterCat = PHPExcel_Cell::stringFromColumnIndex($n*2+6);
									$colLetterScore = PHPExcel_Cell::stringFromColumnIndex($n*2+7);
									$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($n*2+6, $rowSum, '=COUNTIF('.$colLetterCat.$j . ':'.$colLetterCat.$i.',"K")+COUNTIF('.$colLetterCat.$j . ':'.$colLetterCat.$i.',"2K")*2+COUNTIF('.$colLetterCat.$j . ':'.$colLetterCat.$i.',"3K")*3+COUNTIF('.$colLetterCat.$j . ':'.$colLetterCat.$i.',"HK")+COUNTIF('.$colLetterCat.$j . ':'.$colLetterCat.$i.',"LK")')
										->setCellValueByColumnAndRow($n*2+7, $rowSum, "=SUM(" .$colLetterScore.$j . ":" .$colLetterScore.$i.")")
										->setCellValue('BS'.$rowSum,'=SUM(BS'.$j.':BS'.$i.')')
										->setCellValue('BT'.$rowSum,'=SUM(BT'.$j.':BT'.$i.')')
										->setCellValue('BV'.$rowSum,'=SUM(BV'.$j.':BV'.$i.')')
										->setCellValue('BW'.$rowSum,'=SUM(BW'.$j.':BW'.$i.')')
										->setCellValue('BX'.$rowSum,'=SUM(BX'.$j.':BX'.$i.')')
										->setCellValue('BY'.$rowSum,'=SUM(BY'.$j.':BY'.$i.')')
										->setCellValue('BZ'.$rowSum,'=SUM(BZ'.$j.':BZ'.$i.')');
								}
							}
							++$i;
						}
						++$i;
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, 'TỔNG' )
							->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.$i.':BZ'.$i)->getFont()->setBold(true);
						++$i;
					}
					for ($n=1; $n<=70; $n++) {
						$total='=0';
						## Dept Total cat
						$colLetter = PHPExcel_Cell::stringFromColumnIndex($n+7);
						foreach($totalRow as $t){	
							$total.= '+'.$colLetter.$t;
						}
						$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($n+7, $i, $total);
					}
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, 'CỘNG TỔNG' )
						->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.$i.':BZ'.$i)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('A8:CB'.$i)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$i=$i+2;
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$i, 'NGƯỜI CHẤM CÔNG' )
						->getStyle('I' . $i)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$i, 'PHỤ TRÁCH ĐƠN VỊ' )
						->getStyle('AE' . $i)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AY'.$i, 'PHÒNG TCLĐ' )
						->getStyle('AY' . $i)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('BS'.$i, 'GIÁM ĐỐC DUYỆT' )
						->getStyle('BS' . $i)->getFont()->setBold(true);
					++$i;
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$i, '(Ký, họ tên)' )
						->setCellValue('AE'.$i, '(Ký, họ tên)' )
						->setCellValue('AY'.$i, '(Ký, họ tên)' );
					header('Content-Disposition: attachment;filename="BangCong_'.$dep.'_'.nv_date("d-m-Y", NV_CURRENTTIME).'.xls"');
					// Set active sheet index to the first sheet, so Excel opens this as the first sheet
					$objPHPExcel->setActiveSheetIndex(0);
					// Redirect output to a client’s web browser (Excel5)
					header('Content-Type: application/vnd.ms-excel');
					header('Cache-Control: max-age=0');
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
					$objWriter->setPreCalculateFormulas(false);
					$objWriter->save('php://output');
					exit;
				}else{
					$error[] = "Không có dữ liệu tháng này!";
				}
			}
		}
	}
}
$xtpl = new XTemplate('report.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('PATH', NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/images/' . $module_file);
//$xtpl->assign('SUBMIT_ACT', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
######### EXPORT ##########
foreach($error as $e){
	$xtpl->assign('ERROR', $e);
	$xtpl->parse('main.error');
}
$xtpl->parse('main');
$contents = $xtpl->text('main');
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';