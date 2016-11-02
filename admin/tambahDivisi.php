<?php
require '../koneksi.php';

$fields = array(
		"divisi"=>true
	);
require "../parameter.php";
$divisi = $data->divisi;
$new_list = array();
$stmt = $conn->prepare("INSERT INTO divis (nama_divisi) VALUES (?)");
$stmt->bind_param("s", $divisi);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$stmt = $conn->prepare("SELECT * FROM divis");
	if ($stmt->execute()) {
		$res = $stmt->get_result();
		while ($output = $res->fetch_array(MYSQLI_ASSOC)) {
			array_push($new_list, $output);	
		};		
	}
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"divisi '" . $divisi . "' berhasil ditambahkan",
			"data"=>$new_list
		);
	echo json_encode($pesan);
	exit();
};
?>