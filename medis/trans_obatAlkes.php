<?php
date_default_timezone_set('Asia/Jakarta');
require '../koneksi.php';
$fields = array(
		"nomedis"=>true,
		"qty"=>true,		
		"id_barang"=>true,
		"total"=>false
	);
require '../parameter.php';
$nomedis = $data->nomedis;
$qty = $data->qty;
$id_barang = $data->id_barang;
$waktu = date('Y-m-d H:i:s');
$total = $data->total;
$stmt = $conn->prepare("INSERT INTO trans_obat_alkes (user_id, id_barang, qty, tanggal, total) VALUES ((SELECT user_id FROM pasien where nomedis = ?), ?,?,?,?)");							
$stmt->bind_param("iiisi",$nomedis, $id_barang, $qty, $waktu, $total); 
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