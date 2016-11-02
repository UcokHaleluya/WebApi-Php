<?php
date_default_timezone_set('Asia/Jakarta');
require '../koneksi.php';
$fields = array(
		"id"=>true
	);
require '../parameter.php';
$id = $data->id;
$header = array();
$list = array();
$stmt = $conn->prepare("SELECT id, tanggal,
(SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama FROM user WHERE id = header_hasil_cek.user_id) as nama_pasien,
(SELECT tgl_lahir AS nama FROM user WHERE id = header_hasil_cek.user_id) as tanggal_lahir,
(SELECT spesialis FROM spesialis WHERE id = (SELECT spesialis FROM dokter WHERE nopegawai = header_hasil_cek.dokter)) as spesialis,
(SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama FROM user WHERE id = (SELECT user_id FROM dokter WHERE nopegawai = header_hasil_cek.dokter)) as dokter
FROM header_hasil_cek WHERE id = ?");							
$stmt->bind_param("i",$id); 
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server."
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	$header = $res->fetch_array(MYSQLI_ASSOC);
	$stmt = $conn->prepare("SELECT * FROM detail_hasil_cek where id_header=?");
	$stmt->bind_param("i",$id);
	if (!$stmt->execute()) {
		$pesan = array(
				"status"=>"gagal",
				"pesan"=>"kesalahan pada server."
			);
		echo json_encode($pesan);
		exit();
	} else {
		$res = $stmt->get_result();
		while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
			array_push($list, $row);
		}
	};
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>null,
			"header"=>$header,
			"detail"=>$list
		);
	echo json_encode($pesan);
	exit();	
};
?>