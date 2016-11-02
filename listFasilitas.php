<?php
require "koneksi.php";
$fields = array(
		"fasilitas" => true
	);
require "parameter.php";
$output = array();
$fasilitas = $data->fasilitas;
$stmt = $conn->prepare("SELECT t1.id, t1.nama, t1.kelas, t1.jenis,t2.url FROM fasilitas as t1, gambar_fasilitas AS t2 WHERE t1.id = t2.id_fasilitas GROUP BY t1.id");
if (!$stmt->execute()) {
	$pesan = array("status"=>"gagal",
				   "pesan" => "terjadi kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($output, $row);
	}
};

if (count($output) < 1) {
	$pesan = array(
			"status" => "berhasil",
			"pesan" => "tidak ada data",
			"data" => null
		);
	echo json_encode($pesan);
	exit();
} else {
	$pesan = array(
			"status" => "berhasil",
			"pesan" => null,
			"data" => $output
		);
	echo json_encode($pesan);
	exit();
};
?>