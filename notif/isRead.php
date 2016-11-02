<?php
require '../koneksi.php';
$fields = array(
		"user_id"=>true,
		"id"=>false
	);
require '../parameter.php';
$user_id = $data->user_id;
if ($data->id || $data->id != null) {
	$id = $data->id;
	$stmt = $conn->prepare("UPDATE notif set notif.terbaca = '1' WHERE notif.to = ? AND notif.id = ? AND notif.terbaca = '0'");
	$stmt->bind_param("ii", $user_id, $id);
} else {
	$stmt = $conn->prepare("UPDATE notif set notif.terbaca = '1' WHERE notif.to = ? AND notif.terbaca = '0'");
	$stmt->bind_param("i", $user_id);
};
$output = array();
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
			"pesan"=>null
		);
	echo json_encode($pesan);
	exit();
};
?>