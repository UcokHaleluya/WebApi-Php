<?php
require 'koneksi.php';
$fields = array(
		"kamar"=> true
	);

require "parameter.php";
$kamar = $data->kamar;
$list = array();
$stmt = $conn->prepare("SELECT id,nama,kelas FROM fasilitas where jenis='Kamar'");
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($list, $row);
	}
};
if (count($list) < 1) {
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"data tidak ada"
		);
	echo json_encode($pesan);
	exit();
} else {
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>null,
			"data"=>$list
		);
	echo json_encode($pesan);
	exit();
};
?>