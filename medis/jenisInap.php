<?php
require '../koneksi.php';
$fields = array(
		"user_id"=>true
	);
require '../parameter.php';
$user_id = $data->user_id;
$output = array();
$stmt = $conn->prepare("SELECT id, nama FROM jenis_inap");
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server."
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($output, $row);
	};
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>null,
			"data"=>$output
		);
	echo json_encode($pesan);
	exit();
};
?>