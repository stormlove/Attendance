<?php

/**
 * @Module Attendance
 * @Author CHUNGNT (it.me6969@gmail.com)
 * @Createdate 25/02/2016
 */

if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE') or ! defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}
define('NV_IS_FILE_ADMIN', true);

$allow_func = array( 'main', 'add', 'employee', 'department', 'catalogue', 'group', 'position', 'report', 'level', 'rate', 'rating', 'meal', 'import', 'getname', 'change_weight', 'change_status', 'delete', 'detail');

require_once ( NV_ROOTDIR . "/modules/" . $module_file . "/global.functions.php" );

function nv_show_row_list($code='',$dep='',$from='',$to='',$cat=''){
    global $db, $lang_module, $lang_global, $module_name, $module_data, $nv_Request, $module_file, $global_config;

	$where = "WHERE 1";
	$opt = "";
	if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $from, $f) AND preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $to, $t)) {
		$where .= " AND r.date >= " . mktime(0, 0, 0, $f[2], $f[1], $f[3]) . " AND r.date <= " . mktime(0, 0, 0, $t[2], $t[1], $t[3]);
		$opt .= "&from=" . $from . "&to=" . $to;
	}else{
		$where .= " AND r.date >= " . mktime(0, 0, 0, date("m")  , date("d"), date("Y")) . " AND r.date <= " . mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
		$opt .= "&from=" . mktime(0, 0, 0, date("m")  , date("d"), date("Y")) . "&to=" . mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
	}	
	if(!empty($code)){
		$where .= " AND r.code LIKE '%" . addcslashes($code, "_%") . "%'";
		$opt .= "&scode=" . $code;
	}
	if(!empty($cat)){
		$where .= " AND r.cat = '" . $cat . "'";
		$opt .= "&scat=" . $cat;
	}
	if(!empty($dep)){
		$where .= " AND r.dep = '" . $dep . "'";
		$opt .= "&sdep=" . $dep;
	}
	
	$sql = "SELECT COUNT(id) as rows, SUM(score) as tscore FROM " . NV_PREFIXLANG . "_" . $module_data . "_row r " .$where;
	//var_dump($sql);die();
    $num = $db->query($sql)->fetch();
    $num_items = ($num['rows'] > 1) ? $num['rows'] : 1;
    $per_page = 100;
    $page = $nv_Request->get_int('page', 'get', 1);
    $xtpl = new XTemplate('add_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('TOTAL', $num['rows']);
    $xtpl->assign('TSCORE', $num['tscore']);
	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_data . '&' . NV_OP_VARIABLE . '=add' . $opt;
    
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
                'url_edit' => 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add&id=' . $row['id'] . '#edit'
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

function nv_show_rate_row_list($code='',$dep='',$from=''){
    global $db, $lang_module, $lang_global, $module_name, $module_data, $nv_Request, $module_file, $global_config;

	$where = "WHERE 1";
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
	if(!empty($dep)){
		$where .= " AND r.dep = '" . $dep . "'";
		$opt .= "&sdep=" . $dep;
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
	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_data . '&' . NV_OP_VARIABLE . '=rating' . $opt;
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
                'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=rating&id=' . $row['id'] . '#edit'
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

function nv_show_employee_list($code='',$name='',$dep='')
{
    global $db, $lang_module, $lang_global, $module_name, $module_data, $nv_Request, $module_file, $global_config;

	$where = "WHERE 1 ";
	$opt = "";
	
	if(!empty($code)){
		$where .= " AND e.code LIKE '%" . addcslashes($code, "_%") . "%'";
		$opt .= "&scode=" . $code;
	}
	if(!empty($name)){
		$where .= " AND e.name LIKE '%" . addcslashes($name, "_%") . "%'";
		$opt .= "&sname=" . $name;
	}
	if(!empty($dep)){
		$where .= " AND e.dep = '" . $dep . "'";
		$opt .= "&sdep=" . $dep;
	}

	$sql = "SELECT COUNT(id) FROM " . NV_PREFIXLANG . "_" . $module_data . "_employee e " .$where;
	//var_dump($sql);die();
    $num = $db->query($sql)->fetchColumn();
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_data . '&' . NV_OP_VARIABLE . '=employee' . $opt;
    $num_items = ($num > 1) ? $num : 1;
    $per_page = 100;
    $page = $nv_Request->get_int('page', 'get', 1);

    $xtpl = new XTemplate('employee_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
	
    if ($num > 0) {
		$sql = "SELECT e.id, e.code, e.name, d.name, p.name, l.name, g.name, e.luongcb, e.phucap, e.status FROM " . NV_PREFIXLANG . "_" . $module_data . "_employee e LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_position p ON e.pos=p.code LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_level l ON e.level=l.id LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_group g ON e.groups=g.code LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_department d ON e.dep=d.code " . $where . " ORDER BY code ASC LIMIT " . $per_page . " OFFSET " . ($page - 1) * $per_page;
		//var_dump($sql);die();
		$result = $db->query($sql);
        while ($row = $result->fetch(3)) {
			//var_dump($row);die();
            $xtpl->assign('ROW', array(
                'id' => $row['0'],
                'code' => $row['1'],
                'name' => $row['2'],
				'dep' => $row['3'],
				'pos' => $row['4'],
				'level' => $row['5'],
				'groups' => $row['6'],
				'luongcb' => number_format($row['7'], 0, ',', '.'),
				'phucap' => number_format($row['8'], 0, ',', '.'),
				'status' => ($row['9']==1)?'checked':'',
                'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=employee&id=' . $row['0'] . '#edit'
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

function nv_show_department_list($code='',$name='')
{
    global $db, $lang_module, $lang_global, $module_name, $module_data, $nv_Request, $module_file, $global_config;

	$where = "WHERE 1 ";
	$opt = "";
	if(!empty($code)){
		$where .= " AND code LIKE '%" . addcslashes($code, "_%") . "%'";
		$opt .= "&code=" . $code;
	}
	if(!empty($name)){
		$where .= " AND name LIKE '%" . addcslashes($name, "_%") . "%'";
		$opt .= "&name=" . $name;
	}
	
    $sql='SELECT COUNT(id) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department ' . $where;
	$num = $db->query($sql)->fetchColumn();

    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_data . '&amp;' . NV_OP_VARIABLE . '=department' . $opt;
    $num_items = ($num > 1) ? $num : 1;
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'get', 1);

    $xtpl = new XTemplate('department_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    if ($num > 0) {
		$sql = 'SELECT id, code, name, weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department ' . $where . " ORDER BY weight ASC LIMIT " . $per_page . " OFFSET " . ($page - 1) * $per_page;
		$num_weight = $db->query('SELECT COUNT(id) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department')->fetchColumn();
		//var_dump($sql);die();
		$result = $db->query($sql);
        while ($row = $result->fetch()) {
			//var_dump($row);die();
            $xtpl->assign('ROW', array(
                'id' => $row['id'],
                'code' => $row['code'],
                'name' => $row['name'],
                'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=department&id=' . $row['id'] . '#edit'
            ));

            for ($i = 1; $i <= $num_weight; ++$i) {
                $xtpl->assign('WEIGHT', array(
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
                ));
                $xtpl->parse('main.loop.weight');
            }

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

function nv_show_catalogue_list($pid=0)
{
    global $db, $lang_module, $lang_global, $module_name, $module_data, $nv_Request, $module_file, $global_config;

	$where = "WHERE 1 ";
	$opt = "";
	if(!empty($code)){
		$where .= " AND c.code LIKE '%" . addcslashes($code, "_%") . "%'";
		$opt .= "&code=" . $code;
	}
	if(!empty($name)){
		$where .= " AND c.name LIKE '%" . addcslashes($name, "_%") . "%'";
		$opt .= "&name=" . $name;
	}
	
    $sql='SELECT COUNT(id) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_catalogue ' . $where;
	$num = $db->query($sql)->fetchColumn();

    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_data . '&amp;' . NV_OP_VARIABLE . '=catalogue' . $opt;
    $num_items = ($num > 1) ? $num : 1;
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'get', 1);

    $xtpl = new XTemplate('catalogue_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    if ($num > 0) {
		$sql = 'SELECT c.id, c.code, c.name, c.weight, c.pid, t.id, t.name  FROM ' . NV_PREFIXLANG . '_' . $module_data . '_catalogue c LEFT JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_topic t ON c.pid=t.id ' . $where . " ORDER BY c.weight ASC LIMIT " . $per_page . " OFFSET " . ($page - 1) * $per_page;
		$num_weight = $db->query('SELECT COUNT(id) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_catalogue')->fetchColumn();
		//var_dump($sql);die();
		$result = $db->query($sql);
        while ($row = $result->fetch(3)) {
			//var_dump($row);die();
            $xtpl->assign('ROW', array(
                'id' => $row[0],
                'code' => $row[1],
                'name' => $row[2],
				'tname' => $row[6],
                'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=catalogue&id=' . $row[0] . '#edit'
            ));

            for ($i = 1; $i <= $num_weight; ++$i) {
                $xtpl->assign('WEIGHT', array(
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row[3] ? ' selected="selected"' : ''
                ));
                $xtpl->parse('main.loop.weight');
            }

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

function nv_show_topic_list()
{
    global $db_slave, $lang_module, $lang_global, $module_name, $module_data, $nv_Request, $module_file, $global_config;

    $num = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topic')->fetchColumn();
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_data . '&amp;' . NV_OP_VARIABLE . '=catalogue';
    $num_items = ($num > 1) ? $num : 1;
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'get', 1);

    $xtpl = new XTemplate('topic_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    if ($num > 0) {
        $db_slave->sqlreset()
            ->select('*')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_topic')
            ->order('weight')
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $result = $db_slave->query($db_slave->sql());
        while ($row = $result->fetch()) {
            $xtpl->assign('ROW', array(
                'id' => $row['id'],
                'name' => $row['name'],
                'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=catalogue&amp;topid=' . $row['id'] . '#edit'
            ));

            for ($i = 1; $i <= $num; ++$i) {
                $xtpl->assign('WEIGHT', array(
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
                ));
                $xtpl->parse('main.loop.weight');
            }

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

function nv_show_group_list($code='',$name='')
{
    global $db, $lang_module, $lang_global, $module_name, $module_data, $nv_Request, $module_file, $global_config;

	$where = "WHERE 1 ";
	$opt = "";
	if(!empty($code)){
		$where .= " AND code LIKE '%" . addcslashes($code, "_%") . "%'";
		$opt .= "&code=" . $code;
	}
	if(!empty($name)){
		$where .= " AND name LIKE '%" . addcslashes($name, "_%") . "%'";
		$opt .= "&name=" . $name;
	}
	
    $sql='SELECT COUNT(id) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_group ' . $where;
	$num = $db->query($sql)->fetchColumn();

    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_data . '&amp;' . NV_OP_VARIABLE . '=group' . $opt;
    $num_items = ($num > 1) ? $num : 1;
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'get', 1);

    $xtpl = new XTemplate('group_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    if ($num > 0) {
		$sql = 'SELECT id, code, name, weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_group ' . $where . " ORDER BY weight ASC LIMIT " . $per_page . " OFFSET " . ($page - 1) * $per_page;
		$num_weight = $db->query('SELECT COUNT(id) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_group')->fetchColumn();
		//var_dump($sql);die();
		$result = $db->query($sql);
        while ($row = $result->fetch()) {
			//var_dump($row);die();
            $xtpl->assign('ROW', array(
                'id' => $row['id'],
                'code' => $row['code'],
                'name' => $row['name'],
                'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=group&id=' . $row['id'] . '#edit'
            ));

            for ($i = 1; $i <= $num_weight; ++$i) {
                $xtpl->assign('WEIGHT', array(
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
                ));
                $xtpl->parse('main.loop.weight');
            }

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

function nv_show_level_list()
{
    global $db_slave, $lang_module, $lang_global, $module_name, $module_data, $nv_Request, $module_file, $global_config;

    $num = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_level')->fetchColumn();
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_data . '&amp;' . NV_OP_VARIABLE . '=level';
    $num_items = ($num > 1) ? $num : 1;
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'get', 1);

    $xtpl = new XTemplate('level_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    if ($num > 0) {
        $db_slave->sqlreset()
            ->select('*')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_level')
            ->order('weight')
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $result = $db_slave->query($db_slave->sql());
        while ($row = $result->fetch()) {
            $xtpl->assign('ROW', array(
                'id' => $row['id'],
                'name' => $row['name'],
                'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=level&amp;id=' . $row['id'] . '#edit'
            ));

            for ($i = 1; $i <= $num; ++$i) {
                $xtpl->assign('WEIGHT', array(
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
                ));
                $xtpl->parse('main.loop.weight');
            }

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

function nv_show_rate_list()
{
    global $db_slave, $lang_module, $lang_global, $module_name, $module_data, $nv_Request, $module_file, $global_config;

    $num = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rate')->fetchColumn();
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_data . '&amp;' . NV_OP_VARIABLE . '=rate';
    $num_items = ($num > 1) ? $num : 1;
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'get', 1);

    $xtpl = new XTemplate('rate_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    if ($num > 0) {
        $db_slave->sqlreset()
            ->select('*')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_rate')
            ->order('weight')
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $result = $db_slave->query($db_slave->sql());
        while ($row = $result->fetch()) {
            $xtpl->assign('ROW', array(
                'id' => $row['id'],
                'name' => $row['name'],
                'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=rate&amp;id=' . $row['id'] . '#edit'
            ));

            for ($i = 1; $i <= $num; ++$i) {
                $xtpl->assign('WEIGHT', array(
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
                ));
                $xtpl->parse('main.loop.weight');
            }

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

function nv_show_meal_list()
{
    global $db_slave, $lang_module, $lang_global, $module_name, $module_data, $nv_Request, $module_file, $global_config;

    $num = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_meal')->fetchColumn();
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_data . '&amp;' . NV_OP_VARIABLE . '=meal';
    $num_items = ($num > 1) ? $num : 1;
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'get', 1);

    $xtpl = new XTemplate('meal_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    if ($num > 0) {
        $db_slave->sqlreset()
            ->select('*')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_meal')
            ->order('weight')
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $result = $db_slave->query($db_slave->sql());
        while ($row = $result->fetch()) {
            $xtpl->assign('ROW', array(
                'id' => $row['id'],
                'money' => $row['money'],
                'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=meal&amp;id=' . $row['id'] . '#edit'
            ));

            for ($i = 1; $i <= $num; ++$i) {
                $xtpl->assign('WEIGHT', array(
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
                ));
                $xtpl->parse('main.loop.weight');
            }

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

function nv_show_position_list($code='',$name='')
{
    global $db, $lang_module, $lang_global, $module_name, $module_data, $nv_Request, $module_file, $global_config;

	$where = "WHERE 1 ";
	$opt = "";
	if(!empty($code)){
		$where .= " AND code LIKE '%" . addcslashes($code, "_%") . "%'";
		$opt .= "&code=" . $code;
	}
	if(!empty($name)){
		$where .= " AND name LIKE '%" . addcslashes($name, "_%") . "%'";
		$opt .= "&name=".$name;
	}
	
    $sql='SELECT COUNT(id) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_position ' . $where;
	$num = $db->query($sql)->fetchColumn();

    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_data . '&amp;' . NV_OP_VARIABLE . '=position' . $opt;
    $num_items = ($num > 1) ? $num : 1;
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'get', 1);

    $xtpl = new XTemplate('position_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    if ($num > 0) {
		$sql = 'SELECT id, code, name, weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_position ' . $where . " ORDER BY weight ASC LIMIT " . $per_page . " OFFSET " . ($page - 1) * $per_page;
		$num_weight = $db->query('SELECT COUNT(id) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_position')->fetchColumn();
		//var_dump($sql);die();
		$result = $db->query($sql);
        while ($row = $result->fetch()) {
			//var_dump($row);die();
            $xtpl->assign('ROW', array(
                'id' => $row['id'],
                'code' => $row['code'],
                'name' => $row['name'],
                'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=position&id=' . $row['id'] . '#edit'
            ));

            for ($i = 1; $i <= $num_weight; ++$i) {
                $xtpl->assign('WEIGHT', array(
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
                ));
                $xtpl->parse('main.loop.weight');
            }

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

function fix_weight($mod)
{
    global $db, $module_data;
	if(!empty($mod)){
		$result = $db->query('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $mod . ' ORDER BY id ASC');
		$weight = 0;
		while ($row = $result->fetch()) {
			++$weight;
			//var_dump($weight . ">" .$row['id']);
			$db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $mod . ' SET weight=' . $weight . ' WHERE id=' . intval($row['id']));
		}
		$result->closeCursor();
	}
}