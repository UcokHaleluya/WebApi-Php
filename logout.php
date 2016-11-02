<?php
date_default_timezone_set('Asia/Jakarta');
require 'koneksi.php';
$fields = array(
		"user_id"=>true
	);
require 'parameter.php';
$user_id = $data->user_id;
$waktu = date('Y-m-d H:i:s');

$stmt = $conn->prepare("INSERT INTO user_log (user_id, status, date) VALUES (?, 'Logout', ?)");
$stmt->bind_param("is", $user_id, $waktu);
if (!$stmt->execute()) {
	$pesan = array("status"=>"gagal",
					"pesan"=>"terjadi kesalahan pada server."
		);
	echo json_encode($pesan);
	exit();
};
?>