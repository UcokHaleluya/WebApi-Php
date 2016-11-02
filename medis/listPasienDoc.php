<?php
require '../koneksi.php';
$fields = array(
		"user_id" => true,
		"nama_pasien"=>false,
		"no_medis"=>false
	);
require '../parameter.php';
$user_id = $data->user_id;
if (!empty($data->nama_pasien)) {
	$nama_pasien = $data->nama_pasien;
};
if (!empty($data->no_medis)) {
	$no_medis = $data->no_medis;
};
$output = array();
if (isset($no_medis)) {
	$stmt = $conn->prepare("SELECT CONCAT(b.nama_depan,' ',b.nama_belakang) as nama, b.foto, a.nomedis FROM pasien a INNER JOIN user b ON a.user_id = b.id WHERE a.nomedis = ?");
	$stmt->bind_param("i",$no_medis);
} else if (isset($nama_pasien)) {
	$nama_belakang = "%". $nama_pasien . "%";
	$nama_depan = "%".$nama_pasien;
	$stmt = $conn->prepare("SELECT CONCAT(b.nama_depan,' ',b.nama_belakang) as nama, b.foto, a.nomedis FROM pasien a INNER JOIN user b ON a.user_id = b.id WHERE b.nama_depan LIKE ? OR b.nama_belakang LIKE ?");
	$stmt->bind_param("ss",$nama_belakang, $nama_depan);
} else {
	$stmt = $conn->prepare("SELECT CONCAT(b.nama_depan,' ',b.nama_belakang) as nama, b.foto, a.nomedis FROM pasien a INNER JOIN user b ON a.user_id = b.id");
};
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server."
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($output, $row);
	};
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>null,
			"data"=>$output
		);
	echo json_encode($pesan);
	exit();
};
?>