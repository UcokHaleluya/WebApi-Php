<?php
require '../koneksi.php';

$fields = array(
		"id"=>true
	);
require "../parameter.php";
$id = $data->id;
$new_list = array();
$stmt = $conn->prepare("UPDATE galeri SET active = 0 WHERE id = ?");
$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$stmt = $conn->prepare("SELECT * FROM galeri");
	if ($stmt->execute()) {
		$res = $stmt->get_result();
		while ($output = $res->fetch_array(MYSQLI_ASSOC)) {
			array_push($new_list, $output);	
		};		
	}
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"galeri berhasil diubah.",
			"data"=>$new_list
		);
	echo json_encode($pesan);
	exit();
};
?>