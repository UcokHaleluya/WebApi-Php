<?php
require "koneksi.php";
$fields = array(
		"fasilitas" => true,
		"id"=>true
	);
require "parameter.php";
$output = array();
$gambar = array();
$fasilitas = $data->fasilitas;
$id = $data->id;
$stmt = $conn->prepare("SELECT * FROM fasilitas WHERE id =?");
$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
	$pesan = array("status"=>"gagal",
				   "pesan" => "terjadi kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	$output = $res->fetch_array(MYSQLI_ASSOC);
};
$stmt = $conn->prepare("SELECT * FROM gambar_fasilitas WHERE id_fasilitas =?");
$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
	$pesan = array("status"=>"gagal",
				   "pesan" => "terjadi kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($gambar, $row);
	};
};

$pesan = array(
		"status" => "berhasil",
		"pesan" => null,
		"data" => $output,
		"gambar"=> $gambar
	);
echo json_encode($pesan);
exit();
?>