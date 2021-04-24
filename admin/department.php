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

    if ( empty($code) OR empty($name) ) {
        $error = $lang_module['error_empty'];
	} elseif ($id == 0) {
        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_department (code, name) VALUES ( :code, :name)';
        $data_insert = array();
        $data_insert['code'] = strtoupper($code);
        $data_insert['name'] = $name;

        if ($id = $db->insert_id($sql, 'id', $data_insert)) {
			fix_weight('department');
            $error = "Thêm thành công!";
        } else {
            $error = $lang_module['error_save'];
        }
    } else {
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_department SET code= :code, name = :name WHERE id =' . $id);
        $stmt->bindParam(':code', strtoupper($code), PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $error = "Sửa thành công!";
        } else {
            $error = $lang_module['error_save'];
        }
    }
}

$xtpl = new XTemplate('department.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('BACK_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('OP', $op);

$code = $nv_Request->get_title('code', 'get', '');
$name = $nv_Request->get_title('name', 'get', '');
$xtpl->assign('DEPARTMENT_LIST', nv_show_department_list($code,$name));
if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$id = $nv_Request->get_int('id', 'get', 0);
if ($id > 0) {
    list($_id, $_code, $_name) = $db->query('SELECT id, code, name FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department where id=' . $id)->fetch(3);
    $lang_module['add_dep'] = $lang_module['edit_dep'];
	$lang_module['btn_add'] = $lang_module['btn_save'];
	$xtpl->assign('id', $_id);
	$xtpl->assign('code', $_code);
	$xtpl->assign('name', $_name);
}

$xtpl->assign('LANG', $lang_module);
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
