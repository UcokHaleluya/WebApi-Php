<?php
require '../koneksi.php';
$fields = array(
		"user_id"=>true,
		"no_medis"=>true
	);
require '../parameter.php';
$user_id = $data->user_id;
$no_medis = $data->no_medis;
$output = array();
$stmt = $conn->prepare("SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama, foto, tgl_lahir FROM user INNER JOIN pasien ON pasien.user_id = user.id WHERE pasien.nomedis = ?");
$stmt->bind_param("i",$no_medis);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {

	$res = $stmt->get_result();
	$output = $res->fetch_array(MYSQLI_ASSOC);
	
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>null,
			"data"=> $output
		);
	echo json_encode($pesan);
	exit();
};	

?>