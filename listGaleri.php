<?php
require "koneksi.php";
$fields = array(
		"galeri" => true
	);
require "parameter.php";
$output = array();
$galeri = $data->galeri;
$stmt = $conn->prepare("SELECT id, foto, caption, date FROM galeri WHERE active = '1' ORDER BY id DESC");
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