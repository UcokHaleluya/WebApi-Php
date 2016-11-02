<?php
require '../koneksi.php';
$fields = array(
		"user_id"=>true,
		"no_reservasi"=>true,
		"type"=>"true"
	);
require '../parameter.php';
$user_id = $data->user_id;
$no_reservasi = $data->no_reservasi;
$type = $data->type;
if (strtolower($type) == "batal" || strtolower($type) == "delete") {
	$st = "2";
} elseif (strtolower($type) == "konfirm" || strtolower($type) == "update") {
	$st = "1";
}
$output = array();
$stmt = $conn->prepare("UPDATE reservasi SET status = ? WHERE id = ?");
$stmt->bind_param("si",$st, $no_reservasi);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server."
		);
	echo json_encode($pesan);
	exit();
} else {
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"Data reservasi berhasil di update."
		);
	echo json_encode($pesan);
	exit();
};
?>