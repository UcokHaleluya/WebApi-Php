<?php
require "koneksi.php";
$fields = array(
		'layanan' => true
	);
require "parameter.php";
$output = array();
$layanan = $data->layanan;
$stmt = $conn->prepare("SELECT t1.id, t1.nama, t1.jumlah_dokter, t2.url FROM layanan as t1, gambar_layanan AS t2 WHERE t1.id = t2.id_layanan GROUP BY t1.id");
if (!$stmt->execute()) {
	$pesan = array(
			"status" => "gagal",
			"pesan" => "terjadi kesalahan server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQL_ASSOC)) {
		array_push($output, $row);
	}
};
if (count($output) < 1) {
	$pesan = array(
			"status" => "berhasil",
			"pesan" => "data kosong",
			"data" => $output
		);
	echo json_encode($pesan);
} else {
	$pesan = array(
			"status" => "berhasil",
			"pesan" => null,
			"data" => $output
		);
	echo json_encode($output);
	exit();
};
?>