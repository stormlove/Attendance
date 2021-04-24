<?php

/**
 * @Module Attendance
 * @Author CHUNGNT (it.me6969@gmail.com)
 * @Createdate 25/02/2016
 */

if (! defined('NV_MAINFILE') ) {
    die('Stop!!!');
}
global $cat_array;
$cat_array=array('K', '2K', '3K', 'LK', 'L2K', 'L3K', 'HK', 'H2K', 'H3K', 'PK', 'PtK');

function get_name($code='', $dep=''){
	global $db_slave, $module_data;
	$name='';
	if ( !empty($code) ) {
		$sql = "SELECT name FROM " . NV_PREFIXLANG . "_" . $module_data . "_employee WHERE status=0 AND ucase(code)='" . strtoupper($code) . "'";
		$sql .= empty($dep) ? "" : " AND ucase(dep)='" . strtoupper($dep) . "'";
		$name = $db_slave->query($sql)->fetchColumn();
	}
	return $name;
}

function get_depname($code=''){
	global $db_slave, $module_data;
	if ( !empty($code) ) {
		$sql = "SELECT name FROM " . NV_PREFIXLANG . "_" . $module_data . "_department WHERE ucase(code)='" . strtoupper($code) . "'";
		$name = $db_slave->query($sql)->fetchColumn();
	}
	return $name;
}

function get_dep(){
   global $module_data, $db;
   $_dep=array();
   $result = $db->query('SELECT code, name FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department ORDER BY weight ASC');
   while ( $row = $result->fetch() )
   {
      $_dep[] = array (
         "code" => $row['code'],
         "name" => $row['name']
      );
   }
   return $_dep ;
}

function get_cat(){
   global $module_data, $db;
   $_cat=array();
   $result = $db->query('SELECT code, name FROM ' . NV_PREFIXLANG . '_' . $module_data . '_catalogue ORDER BY weight ASC');
   while ( $row = $result->fetch() )
   {
      $_cat[] = array (
         "code" => $row['code'],
         "name" => $row['name']
      );
   }
   return $_cat ;
}

function get_pos(){
   global $module_data, $db;
   $_pos=array();
   $result = $db->query('SELECT code, name FROM ' . NV_PREFIXLANG . '_' . $module_data . '_position ORDER BY weight ASC');
   while ( $row = $result->fetch() )
   {
      $_pos[] = array (
         "code" => $row['code'],
         "name" => $row['name']
      );
   }
   return $_pos;
}

function get_group(){
   global $module_data, $db;
   $_group=array();
   $result = $db->query('SELECT code, name FROM ' . NV_PREFIXLANG . '_' . $module_data . '_group ORDER BY weight ASC');
   while ( $row = $result->fetch() )
   {
      $_group[] = array (
         "code" => $row['code'],
         "name" => $row['name']
      );
   }
   return $_group;
}

function get_level(){
   global $module_data, $db;
   $_level=array();
   $result = $db->query('SELECT id, name FROM ' . NV_PREFIXLANG . '_' . $module_data . '_level ORDER BY weight ASC');
   while ( $row = $result->fetch() )
   {
      $_level[] = array (
         "id" => $row['id'],
         "name" => $row['name']
      );
   }
   return $_level;
}

function get_rate(){
   global $module_data, $db;
   $_rate=array();
   $result = $db->query('SELECT id, name FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rate ORDER BY weight ASC');
   while ( $row = $result->fetch() )
   {
      $_rate[] = array (
         "id" => $row['id'],
         "name" => $row['name']
      );
   }
   return $_rate;
}

function get_meal(){
   global $module_data, $db;
   $_meal=array();
   $result = $db->query('SELECT id, money FROM ' . NV_PREFIXLANG . '_' . $module_data . '_meal ORDER BY weight ASC');
   while ( $row = $result->fetch() )
   {
      $_meal[] = array (
         "id" => $row['id'],
         "money" => $row['money']
      );
   }
   return $_meal;
}

function check_row_exsit($code='',$date=''){
	global $db_slave, $module_data;
	$b=0;
	if(!empty($code) AND !empty($date)){
		$b = $db_slave->query( "SELECT COUNT(id) FROM " . NV_PREFIXLANG . "_" . $module_data . "_row WHERE ucase(code)='" . strtoupper($code) . "' AND date='" . $date . "'")->fetchColumn();
	}
	return ($b > 0 ? true:false);
}

function check_row_rate_exsit($code='',$date='', $dep=''){
	global $db_slave, $module_data;
	$b=0;
	if(!empty($code) AND !empty($date)){
		$b = $db_slave->query( "SELECT COUNT(id) FROM " . NV_PREFIXLANG . "_" . $module_data . "_rate_row WHERE ucase(code)='" . strtoupper($code) . "' AND date=" . $date . " AND dep='". $dep ."'")->fetchColumn();
	}
	return ($b > 0 ? true:false);
}

function check_employee_exsit($code='',$dep=''){
	global $db_slave, $module_data;
	$b=0;
	$and = empty($dep) ? "" : "AND ucase(dep)='" . strtoupper($dep) . "'";
	if(!empty($code)){
		$b = $db_slave->query( "SELECT COUNT(id) FROM " . NV_PREFIXLANG . "_" . $module_data . "_employee WHERE status=0 AND ucase(code)='" . strtoupper($code) . "' " . $and)->fetchColumn();
	}
	return ($b > 0 ? true:false);
}

function check_cat_exsit($code=''){
	global $db_slave, $module_data;
	$b=0;
	if(!empty($code)){
		$b = $db_slave->query( "SELECT COUNT(id) FROM " . NV_PREFIXLANG . "_" . $module_data . "_catalogue WHERE code='" . $code . "' ")->fetchColumn();
	}
	return ($b > 0 ? true:false);
}

function check_dep_exsit($code=''){
	global $db_slave, $module_data;
	$b=0;
	if(!empty($code)){
		$b = $db_slave->query( "SELECT COUNT(id) FROM " . NV_PREFIXLANG . "_" . $module_data . "_department WHERE ucase(code)='" . strtoupper($code) . "' ")->fetchColumn();
	}
	return ($b > 0 ? true:false);
}

function check_group_exsit($code=''){
	global $db_slave, $module_data;
	$b=0;
	if(!empty($code)){
		$b = $db_slave->query( "SELECT COUNT(id) FROM " . NV_PREFIXLANG . "_" . $module_data . "_group WHERE ucase(code)='" . strtoupper($code) . "' ")->fetchColumn();
	}
	return ($b > 0 ? true:false);
}

function nv_replate_null($a)
{
    return $a != NULL ? $a : '';
}