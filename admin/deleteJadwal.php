<?php
require '../koneksi.php';
$fields = array(
		"id"=>true,
		"nopegawai"=>true
	);
require "../parameter.php";
$id = $data->id;
$nopegawai = $data->nopegawai;
$new_list = array();
$stmt = $conn->prepare("DELETE FROM jadwal_dokter WHERE id = ?");
$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$stmt = $conn->prepare("SELECT * FROM jadwal_dokter WHERE nopegawai= ? ORDER BY id DESC LIMIT 1 ");
	$stmt->bind_param("i", $nopegawai);
	if ($stmt->execute()) {
		$res = $stmt->get_result();
		while ($output = $res->fetch_array(MYSQLI_ASSOC)) {
			array_push($new_list, $output);	
		};		
	}
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"jadwal_dokter berhasil hapus.",
			"data"=>$new_list
		);
	echo json_encode($pesan);
	exit();
};
?>