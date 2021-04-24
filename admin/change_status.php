<?php

/**
 * @Project Module Attendance
 * @Author ChungNT (chung_vuitinh@yahoo.com)
 * @Createdate 25-2-2016
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}
/* if (! defined('NV_IS_AJAX')) {
    die('Wrong URL');
} */

$id = $nv_Request->get_int('id', 'get/post', 0);

if ($id==0) {
    die('NO_' . $id);
}

$sth = $db->prepare('SELECT status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_employee WHERE id= :id');
$sth->bindParam(':id', $id, PDO::PARAM_INT);
$sth->execute();
$row = $sth->fetch();

if (empty($row)) {
    die('NO_' . $id);
}

$status = intval($row['status']);
$status = ($status != 1) ? 1 : 0;

$sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_employee SET status=' . $status . ' WHERE id= :id');
$sth->bindParam(':id', $id, PDO::PARAM_INT);
$sth->execute();

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $id;
include NV_ROOTDIR . '/includes/footer.php';