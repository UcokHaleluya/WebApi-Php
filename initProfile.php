<?php

require 'koneksi.php';
$fields = array(
		"auth_id" => true
	);
require 'parameter.php';
$auth_id = $data->auth_id;
$output = array();
$stmt = $conn->prepare("SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama, foto FROM user WHERE auth_id = ?");
$stmt->bind_param("s", $auth_id);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	$output = $res->fetch_array(MYSQLI_ASSOC);
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>null,
			"data"=>$output	
		);
	echo json_encode($pesan);
	exit();
};
?>