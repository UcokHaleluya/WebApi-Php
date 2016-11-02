<?php
require '../koneksi.php';

$fields = array(
		"spesialis"=>true
	);
require "../parameter.php";
$spesialis = $data->spesialis;
$new_list = array();
$stmt = $conn->prepare("INSERT INTO spesialis (spesialis) VALUES (?)");
$stmt->bind_param("s", $spesialis);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$stmt = $conn->prepare("SELECT * FROM spesialis");
	if ($stmt->execute()) {
		$res = $stmt->get_result();
		while ($output = $res->fetch_array(MYSQLI_ASSOC)) {
			array_push($new_list, $output);	
		};		
	}
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"spesialis '" . $spesialis . "' berhasil ditambahkan",
			"data"=>$new_list
		);
	echo json_encode($pesan);
	exit();
};
?>