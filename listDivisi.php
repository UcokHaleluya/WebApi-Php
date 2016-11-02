<?php
require 'koneksi.php';
$stmt = $conn->prepare("SELECT * FROM divis");
$output = array();
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {

	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($output, $row);
	}
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>null,
			"data"=> $output
		);
	echo json_encode($pesan);
	exit();
};
?>