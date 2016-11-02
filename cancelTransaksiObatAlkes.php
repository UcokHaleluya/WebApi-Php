<?php
require 'koneksi.php';
$fields = array(
		"id"=>true
	);
require "parameter.php";
$id = $data->id;
$new_list = array();
$stmt = $conn->prepare("UPDATE trans_obat_alkes set active = 0 WHERE id = ?");
$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan server"
		);
	echo json_encode($pesan);
	exit();
} else {	
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"transaksi berhasil diubah.",			
		);
	echo json_encode($pesan);
	exit();
};
?>