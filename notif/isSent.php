<?php
require '../koneksi.php';
$fields = array(
		"user_id"=>true
	);
require '../parameter.php';
$user_id = $data->user_id;
$output = array();
$stmt = $conn->prepare("UPDATE notif set notif.terkirim = '1' WHERE notif.to = ? AND notif.terkirim = '0'");
$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi keselahan pada server."
		);
	echo json_encode($pesan);
	exit();
} else {
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>null,
			"data"=>$output
		);
	echo json_encode($pesan);
	exit();
};
?>