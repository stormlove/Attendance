<?php

/**
 * @Project Module Attendance
 * @Author ChungNT (chung_vuitinh@yahoo.com)
 * @Createdate 25-2-2016
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}
if (! defined('NV_IS_AJAX')) {
    die('Wrong URL');
}

$id = $nv_Request->get_int('id', 'post', 0);
$new_vid = $nv_Request->get_int('new_vid', 'post', 0);
$mod = $nv_Request->get_title('mod', 'post', '');

if (empty($mod) OR empty($id)) {
    die('NO_' . $id);
}
$content = 'NO_' . $id;

if (!empty($mod) AND $id > 0 AND $new_vid > 0) {
    $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $mod . ' WHERE id=' . $id;
    $numrows = $db->query($sql)->fetchColumn();
    if ($numrows != 1) {
        die('NO_' . $id);
    }

    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $mod . ' WHERE id!=' . $id . ' ORDER BY weight ASC';
    $result = $db->query($sql);

    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_vid) {
            ++$weight;
        }
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $mod . ' SET weight=' . $weight . ' WHERE id=' . $row['id'];
        $db->query($sql);
    }

    $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $mod . ' SET weight=' . $new_vid . ' WHERE id=' . $id;
    $db->query($sql);
    $content = 'OK_' . $id;
    $nv_Cache->delMod($module_name);
}

include NV_ROOTDIR . '/includes/header.php';
echo $content;
include NV_ROOTDIR . '/includes/footer.php';