<?php
require '../koneksi.php';
$fields = array(
		'user_id'=>true
	);
require '../parameter.php';
$user_id = $data->user_id;
$output = array();
$stmt = $conn->prepare("SELECT * FROM petugas INNER JOIN user ON petugas.user_id = user.id INNER JOIN divis ON petugas.divisi = divis.id WHERE petugas.user_id = ?");
$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan saat memuat data."
		);
	echo json_encode($pesan);
	exit();
} else {
	$row = $stmt->get_result();
	$output = $row->fetch_array(MYSQLI_ASSOC);
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>null,
			"data"=>$output
		);
	echo json_encode($pesan);
	exit();
};
?>