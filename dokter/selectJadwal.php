<?php
require '../koneksi.php';
$fields = array(
		"nopegawai"=>true
	);
require '../parameter.php';
$output = array();
$nopegawai = $data->nopegawai;
$stmt = $conn->prepare("SELECT * FROM jadwal_dokter WHERE nopegawai=?");
$stmt->bind_param("i", $nopegawai);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan saat memuat data."
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