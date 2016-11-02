<?php
require '../koneksi.php';
require '../parameter.php';
$output = array();
$stmt = $conn->prepare("SELECT CONCAT(b.nama_depan,' ',b.nama_belakang) as nama, b.foto, a.nopegawai FROM dokter a INNER JOIN user b ON a.user_id = b.id");
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