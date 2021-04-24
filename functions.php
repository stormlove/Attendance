<?php

/**
 * @Module Attendance
 * @Author CHUNGNT (it.me6969@gmail.com)
 * @Createdate 25/02/2016
 */

if (! defined('NV_SYSTEM')) {
    die('Stop!!!');
}
define('NV_IS_MOD_ATTENDANCE', true);

require_once ( NV_ROOTDIR . "/modules/" . $module_file . "/global.functions.php" );

global $attendance_user;
try {
	 $attendance_user = $db->query("SELECT attendance FROM nv4_users_info WHERE userid=".$user_info['userid'])->fetchColumn();
}
catch (PDOException $e) {
    trigger_error($e->getMessage());
}

function nv_show_row_list(){
    global $db, $lang_module, $lang_global, $module_name, $module_data, $nv_Request, $module_file, $global_config,$attendance_user;
	$code = $nv_Request->get_title('s_code', 'post', '');
	$cat = $nv_Request->get_title('s_cat', 'post', '');
	$group = $nv_Request->get_title('s_group', 'post', '');
	$from = $nv_Request->get_title('from', 'post', nv_date("d/m/Y", NV_CURRENTTIME));
	$to = $nv_Request->get_title('to', 'post', nv_date("d/m/Y", NV_CURRENTTIME));
	$where = "WHERE r.dep = '" . $attendance_user . "'";
	$opt = "";
	
	if(!empty($code)){
		$where .= " AND r.code LIKE '%" . addcslashes($code, "_%") . "%'";
		$opt .= "&scode=" . $code;
	}
	if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $from, $f) AND preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $to, $t)) {
		$where .= " AND r.date >= " . mktime(0, 0, 0, $f[2], $f[1], $f[3]) . " AND r.date <= " . mktime(0, 0, 0, $t[2], $t[1], $t[3]);
		$opt .= "&from=" . $from . "&to=" . $to;
	}else{
		$where .= " AND r.date >= " . mktime(0, 0, 0, date("m")  , date("d"), date("Y")) . " AND r.date <= " . mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
		$opt .= "&from=" . mktime(0, 0, 0, date("m")  , date("d"), date("Y")) . "&to=" . mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
	}
	if(!empty($cat)){
		$where .= " AND r.cat = '" . $cat . "'";
		$opt .= "&sdep=" . $cat;
	}
	if(!empty($group)){
		$where .= " AND r.groups = '" . $group . "'";
		$opt .= "&sgroup=" . $group;
	}
	
	$sql = "SELECT COUNT(id) as rows, SUM(score) as tscore FROM " . NV_PREFIXLANG . "_" . $module_data . "_row r " .$where;
//var_dump();
//die($sql);
    $num = $db->query($sql)->fetch();
    $num_items = ($num['rows'] > 1) ? $num['rows'] : 1;
    $per_page = 100;
    $page = $nv_Request->get_int('page', 'get', 1);
    $xtpl = new XTemplate('main_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
	$xtpl->assign('TOTAL', $num['rows']);
    $xtpl->assign('TSCORE', $num['tscore']);
	$base_url = NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_data . '&' . NV_OP_VARIABLE . '=main' . $opt;
    if ($num['rows'] > 0) {
		$sql = "SELECT r.id, r.code, e.name, r.date, r.cat, r.score, r.meal, r.dep, r.addtime, r.edittime FROM " . NV_PREFIXLANG . "_" . $module_data . "_row r LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_employee e ON r.code=e.code  " . $where . " ORDER BY r.date DESC LIMIT " . $per_page . " OFFSET " . ($page - 1) * $per_page;
//die($sql);
		$result = $db->query($sql);
        while ($row = $result->fetch()) {
			//var_dump($row);die();
            $xtpl->assign('ROW', array(
                'id' => $row['id'],
                'code' => $row['code'],
				'name' => $row['name'],
                'date' => nv_date('d/m/Y',$row['date']),
				'cat' => $row['cat'],
				'score' => $row['score'],
				'meal' => $row['meal'],
				'dep' => $row['dep'],
				'addtime' => $row['addtime'],
				'edittime' => $row['edittime'],
                'url_edit' => 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=main&id=' . $row['id'] . '#edit'
            ));

            $xtpl->parse('main.loop');
        }
        $result->closeCursor();

        $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
        if (! empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.generate_page');
        }

        $xtpl->parse('main');
        $contents = $xtpl->text('main');
    } else {
        $contents = 'Không có dữ liệu !';
    }

    return $contents;
}

function nv_show_rate_row_list(){
    global $db, $lang_module, $lang_global, $module_name, $module_data, $nv_Request, $module_file, $global_config, $attendance_user;
	$scode = $nv_Request->get_title('s_code', 'get,post', '');
	$from = $nv_Request->get_title('from', 'get,post', nv_date("m/Y", NV_CURRENTTIME));
	$where = "WHERE r.dep = '" . $attendance_user . "'";
	$opt = "";
	
	if(!empty($code)){
		$where .= " AND r.code LIKE '%" . addcslashes($code, "_%") . "%'";
		$opt .= "&scode=" . $code;
	}
	if( !empty($from) ){
		if ( preg_match('/([0-9]{1,2})\/([0-9]{4})$/', $from, $f) ) {
			$where .= " AND r.date = " . mktime(0, 0, 0, $f[1], 1, $f[2]);
			$opt .= "&from=" . $from;
		}
	}
	
	$sql = "SELECT COUNT(r.id) FROM " . NV_PREFIXLANG . "_" . $module_data . "_rate_row r " .$where;
	//var_dump($from);die($sql);
    $num = $db->query($sql)->fetchColumn();
    $num_items = ($num > 1) ? $num : 1;
    $per_page = 100;
    $page = $nv_Request->get_int('page', 'get', 1);
    $xtpl = new XTemplate('rating_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
	$base_url = NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_data . '&' . NV_OP_VARIABLE . '=rating' . $opt;
    if ($num > 0) {
		$sql = "SELECT r.id, r.code, e.name, r.dep, r.date, r.rate FROM " . NV_PREFIXLANG . "_" . $module_data . "_rate_row r LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_employee e ON r.code=e.code  " . $where . " ORDER BY r.date DESC LIMIT " . $per_page . " OFFSET " . ($page - 1) * $per_page;
		//die($sql);
		$result = $db->query($sql);
		//var_dump($result->fetchAll());die();
        while ($row = $result->fetch()) {
			//var_dump($row);die();
            $xtpl->assign('ROW', array(
                'id' => $row['id'],
                'code' => $row['code'],
				'name' => $row['name'],
				'dep' => $row['dep'],
                'date' => nv_date('m/Y',$row['date']),
				'rate' => $row['rate'],
                'url_edit' => 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=rating&id=' . $row['id'] . '#edit'
            ));

            $xtpl->parse('main.loop');
        }
        $result->closeCursor();

        $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
        if (! empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.generate_page');
        }

        $xtpl->parse('main');
        $contents = $xtpl->text('main');
    } else {
        $contents = '&nbsp;';
    }

    return $contents;
}

function nv_show_employee_list()
{
    global $db, $lang_module, $lang_global, $module_name, $module_data, $nv_Request, $module_file, $global_config, $attendance_user;
	$code = $nv_Request->get_title('scode', 'post', '');
	$name = $nv_Request->get_title('sname', 'post', '');
	$where = "WHERE e.status=0 AND e.dep = '" . $attendance_user . "'";
	$opt = "";
	//var_dump($name);die($code);
	if(!empty($code)){
		$where .= " AND e.code LIKE '%" . addcslashes($code, "_%") . "%'";
		$opt .= "&scode=" . $code;
	}
	if(!empty($name)){
		$where .= " AND e.name LIKE '%" . addcslashes($name, "_%") . "%'";
		$opt .= "&sname=" . $name;
	}

	$sql = "SELECT COUNT(id) FROM " . NV_PREFIXLANG . "_" . $module_data . "_employee e " .$where;
	//var_dump($sql);die();
    $num = $db->query($sql)->fetchColumn();
    $base_url = NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_data . '&' . NV_OP_VARIABLE . '=employee' . $opt;
    $num_items = ($num > 1) ? $num : 1;
    $per_page = 100;
    $page = $nv_Request->get_int('page', 'get', 1);

    $xtpl = new XTemplate('employee_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
	
    if ($num > 0) {
		$sql = "SELECT e.id, e.code, e.name, p.name, l.name, g.name, e.luongcb, e.phucap FROM " . NV_PREFIXLANG . "_" . $module_data . "_employee e LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_position p ON e.pos=p.code LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_level l ON e.level=l.id LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_group g ON e.groups=g.code LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_department d ON e.dep=d.code " . $where . " ORDER BY code ASC LIMIT " . $per_page . " OFFSET " . ($page - 1) * $per_page;
		//var_dump($sql);die();
		$result = $db->query($sql);
        while ($row = $result->fetch(3)) {
			//var_dump($row);die();
            $xtpl->assign('ROW', array(
                'id' => $row['0'],
                'code' => $row['1'],
                'name' => $row['2'],
				'pos' => $row['3'],
				'level' => $row['4'],
				'groups' => $row['5'],
				'luongcb' => number_format($row['6'], 0, ',', '.'),
				'phucap' => number_format($row['7'], 0, ',', '.'),
                'url_edit' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=employee&id=' . $row['0'] . '#edit'
            ));

            $xtpl->parse('main.loop');
        }
        $result->closeCursor();

        $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
        if (! empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.generate_page');
        }

        $xtpl->parse('main');
        $contents = $xtpl->text('main');
		//$contents .= $sql;
    } else {
        $contents = '&nbsp;';
    }

    return $contents;
}