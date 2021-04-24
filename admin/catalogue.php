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
    $code = $nv_Request->get_title('code', 'post', '');
    $name = $nv_Request->get_title('name', 'post', '');
	$pid = $nv_Request->get_int('pid', 'post', 0);
	
    if ( empty($code) OR empty($name) ) {
        $error = $lang_module['error_empty'];
	} elseif ($id == 0) {
        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_catalogue (code, name, pid) VALUES ( :code, :name, :pid)';
        $data_insert = array();
        $data_insert['code'] = $code;
        $data_insert['name'] = $name;
		$data_insert['pid'] = $pid;
//var_dump($data_insert);die();
        if ($db->insert_id($sql, 'id', $data_insert)) {
			fix_weight('catalogue');
            $error = "Thêm thành công!";
        } else {
            $error = $lang_module['error_save'];
        }
    } else {
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_catalogue SET code= :code, name = :name, pid = :pid WHERE id =' . $id);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->bindParam(':pid', $pid,  PDO::PARAM_INT);

        if ($stmt->execute()) {
            $error = "Sửa thành công!";
        } else {
            $error = $lang_module['error_save'];
        }
    }
}
if ( $nv_Request->get_int('save_topic', 'post', 0)==1 ) {
    $topid = $nv_Request->get_int('topic_id', 'post', 0);
    $topname = $nv_Request->get_title('topic_name', 'post', '');

    if ( empty($topname) ) {
        $error = $lang_module['error_empty'];
	} elseif ($topid == 0) {
        $topic_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_topic (name) VALUES (:name)';
        $topic_insert = array();
        $topic_insert['name'] = $topname;

        if ($db->insert_id($topic_sql, 'topid', $topic_insert)) {
			fix_weight('topic');
            Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
            die();
        } else {
            $error = $lang_module['error_save'];
        }
    } else {
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_topic SET name = :name WHERE id =' . $topid);
        $stmt->bindParam(':name', $topname, PDO::PARAM_STR);

        if ($stmt->execute()) {
            Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
            die();
        } else {
            $error = $lang_module['error_save'];
        }
    }
}

$xtpl = new XTemplate('catalogue.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('BACK_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('OP', $op);

$code = $nv_Request->get_title('code', 'get', '');
$name = $nv_Request->get_title('name', 'get', '');
$xtpl->assign('CATALOGUE_LIST', nv_show_catalogue_list($code,$name));
$xtpl->assign('TOPIC_LIST', nv_show_topic_list());
if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}
$_id = $_code = $_name = $_pid ='';
$id = $nv_Request->get_int('id', 'get', 0);
if ($id > 0) {
    list($_id, $_code, $_name, $_pid) = $db->query('SELECT id, code, name, pid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_catalogue where id=' . $id)->fetch(3);

	$lang_module['btn_add'] = $lang_module['btn_save'];
	$xtpl->assign('id', $_id);
	$xtpl->assign('code', $_code);
	$xtpl->assign('name', $_name);
}
$topid = $nv_Request->get_int('topid', 'get', 0);
if ($topid > 0) {
    list($_topid, $_topname) = $db->query('SELECT id, name FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topic where id=' . $topid)->fetch(3);

	$lang_module['btn_addtop'] = $lang_module['btn_save'];
	$xtpl->assign('topic_id', $_topid);
	$xtpl->assign('topic_name', $_topname);
}
######### Load Topic combobox #######
$result = $db->query('SELECT id, name FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topic ORDER BY weight ASC');
while( list( $top_id, $top_name ) = $result->fetch(3) )
{
	$xtpl->assign('top_list', array(
		'topid' => $top_id,
		'topname' => $top_name,
		'selected' => ($top_id==$_pid)?'selected="selected"':''
	));
	$xtpl->parse('main.top_list');
}
$result->closeCursor();
#########
$xtpl->assign('LANG', $lang_module);
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
