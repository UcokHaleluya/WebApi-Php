<?php
require 'koneksi.php';
$fields = array(
		"id"=> true
	);
require "parameter.php";
$id = $data->id;
$header = array();
$list = array();
$stmt = $conn->prepare("SELECT id,tanggal, 
(SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama FROM user WHERE id = (SELECT user_id FROM pasien WHERE nomedis = header_resep.nomedis)) as pro,
(SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama FROM user WHERE id = (SELECT user_id FROM dokter WHERE nopegawai = header_resep.dokter)) as dokter
FROM header_resep where id=?");
$stmt->bind_param("i",$id);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	$header = $res->fetch_array(MYSQLI_ASSOC);
	$stmt = $conn->prepare("SELECT * FROM detail_resep where id_header=?");
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
			"termin"=>$list
		);
	echo json_encode($pesan);
	exit();	
};

?>