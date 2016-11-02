<?php
require '../koneksi.php';
$fields = array(
		"kode_reservasi"=>true,		
	);
require "../parameter.php";
$output = array();
$id = $data->kode_reservasi;
$stmt = $conn->prepare("UPDATE reservasi SET status = 1 WHERE id = ?");
$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"reservasi berhasil di konfirmasi"
		);
	echo json_encode($pesan);
	exit();
};
?>