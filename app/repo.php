<?php

function y() {
	return '<span class="label label-success">YES</span>';
}
function n() {
	return '<span class="label label-danger">NO</span>';
}

$attrs     = array(
PDO::ATTR_PERSISTENT => true,
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
);
$pdo = new PDO('mysql:host=127.0.0.1;dbname=vim_db', 'root', '', $attrs);
if (!isset($_GET['id'])) {
	$statement = $pdo->query( 'SELECT * from vim_options');
	$arr = $statement->fetchAll(PDO::FETCH_ASSOC);
	$json = array();
	foreach ( $arr as $row) {
		$json[] = array('name' => $row['option'], 'description' => $row['short_desc'], 'language' => $row['data_type'], 'value' => $row['option'], 'tokens' => split(',', $row['option']));
	}
	header("Content-Type: application/json");
	echo json_encode($json);
} else {
	$statement = $pdo->query( "SELECT * from vim_options where option = '".$_REQUEST['id']."'");
	$arr = $statement->fetchAll(PDO::FETCH_ASSOC);
	$json = array();
	foreach ( $arr as $row) {
		$json = $row;
	}
	$json['long_desc'] = nl2br($json['long_desc']);
	$json['data_type'] = ucfirst($json['data_type']);
	$json['is_global'] = $json['is_global'] == 1 ? y() : n();
	$json['local_to_window'] = $json['local_to_window'] == 1 ? y() : n();
	$json['local_to_buffer'] = $json['local_to_buffer'] == 1 ? y() : n();
	$json['not_in_vi'] = $json['not_in_vi'] > 0 ? y() : n();
	$json['req_feature'] = $json['req_feature'];
	header("Content-Type: application/json");
	echo json_encode($json);
}
exit;

