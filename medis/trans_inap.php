<?php
date_default_timezone_set('Asia/Jakarta');
require '../koneksi.php';
$fields = array(
		"nomedis"=>true,
		"id_jenis"=>true,
		"total"=>true
	);
require '../parameter.php';
$nomedis = $data->nomedis;
$id_jenis = $data->id_jenis;
$total = $data->total;
$waktu = date('Y-m-d H:i:s');
$stmt = $conn->prepare("INSERT INTO transaksi_inap (user_id, id_jenis, tanggal, total) VALUES ((SELECT user_id FROM pasien where nomedis = ?),?,?,?)");							
$stmt->bind_param("iisi",$nomedis, $id_jenis, $waktu,$total); 
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