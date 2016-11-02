<?php
require '../koneksi.php';

$fields = array(
		"id"=>true,
		"nama_baru"=>true
	);
require "../parameter.php";
$id = $data->id;
$nama_baru = $data->nama_baru;
$new_list = array();
$stmt = $conn->prepare("UPDATE spesialis SET spesialis = ?  WHERE id = ?");
$stmt->bind_param("si", $nama_baru, $id);
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
			"pesan"=>"Spesialis berhasil diubah.",
			"data"=>$new_list
		);
	echo json_encode($pesan);
	exit();
};
?>