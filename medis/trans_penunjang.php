<?php
date_default_timezone_set('Asia/Jakarta');
require '../koneksi.php';
$fields = array(
		"nomedis"=>true,
		"id_penunjang"=>true
	);
require '../parameter.php';
$nomedis = $data->nomedis;
$id_penunjang = $data->id_penunjang;
$waktu = date('Y-m-d H:i:s');
$stmt = $conn->prepare("INSERT INTO trans_penunjang (nomedis, id_penunjang, tanggal) VALUES (?,?,?)");							
$stmt->bind_param("iis",$nomedis, $id_penunjang, $waktu); 
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server."
		);
	echo json_encode($pesan);
	exit();
} else {
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"Data transaksi berhasil ditambahkan."
		);
	echo json_encode($pesan);
	exit();
};
?>