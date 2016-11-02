<?php
require '../koneksi.php';
$fields = array(		
		"no_pegawai"=>true
	);
require '../parameter.php';
$user_id = $data->user_id;
$no_pegawai = $data->no_pegawai;
$output = array();
$stmt = $conn->prepare("SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama, tgl_lahir, user.auth_id, (SELECT spesialis FROM spesialis WHERE id = dokter.spesialis) as spesialis FROM user INNER JOIN dokter ON dokter.user_id = user.id WHERE dokter.nopegawai = ?");
$stmt->bind_param("i",$no_pegawai);
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
	$output["nopegawai"] = $no_pegawai;
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>null,
			"data"=> $output
		);
	echo json_encode($pesan);
	exit();
};	

?>