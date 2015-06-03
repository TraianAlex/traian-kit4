<?php
require_once 'db_manager.php';

$db = DB_Manager::get_instance();
$db->database();

$db->table = 'users';

# all results.
$result = $db->select('*')->getAll();
echo '<pre>';
	var_export($result);
echo '</pre>';

# one result.
$result = $db->select('*')->where('username', 'cantuares')->get();
echo '<pre>';
	var_export($result);
echo '</pre>';

# insert record.
$result = $db->values(array('username' => 'fulano', 'password' => '***'))->insert();
echo '<pre>';
	var_export($result);
echo '</pre>';//true

# update record.
$result = $db->set(array('username' => 'sicrano'))->where('username', 'fulano')->update();
echo '<pre>';
	var_export($result);
echo '</pre>';//array(1) { [0]=> string(7) "sicrano" }  true

# delete record.
$result = $db->where('username', 'sicrano')->delete();
echo '<pre>';
	var_export($result);
echo '</pre>';//true