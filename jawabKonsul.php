<?php
date_default_timezone_set('Asia/Jakarta');
require 'koneksi.php';
$fields = array(
		"kiriman_id" => true,		
		"pengirim" => true,
		"isi" => false, 
		"file" => false
	);
require "parameter.php";
$kiriman_id = $data->kiriman_id;
$waktu = date('Y-m-d H:i:s');
$pengirim = $data->pengirim;
if ($data->isi) {
	$isi = $data->isi;
} else {
	$isi = null;
};

if ($data->file) {
	$file = $data->file;
} else {
	$file = null;
};
if ( $file != null ) {
	$tmp = explode(",", $file);
	$eks = explode('/', $tmp[0]);
	$eks = explode(';', end($eks));
	$eks = ($eks[0] == 'jpeg') ? 'jpg' : $eks[0];
	$namaFile = $pengirim . "_". uniqid() . 'konsultasi.' . $eks;
	$gambar = base64_decode(end($tmp));
	if (isset($gambar)) {
		if(file_put_contents(__DIR__ . "/kons_img/$namaFile", $gambar)) {
			$file = "http://localhost:8080/SKRIPSI/api-elsa/API/kons_img/" . $namaFile;
		};
	};
};//
$stmt = $conn->prepare("INSERT INTO jawaban_konsul (kiriman_id, pengirim, waktu, isi, file) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iisss", $kiriman_id, $pengirim, $waktu, $isi, $file);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {

	$id = $kiriman_id;
	$link = "konsultasiDetail({id : ". $id ."})";
	
	$stmt = $conn->prepare("INSERT INTO notif (keterangan, link, user_id, waktu, `to`) SELECT 'konsultasi baru' as keterangan, ? as link, ? as user_id , ? as waktu, b.user_id as `to` FROM dokter as b WHERE b.spesialis = ?");
	$stmt->bind_param("sisi", $link ,$pengirim, $waktu, $spesialis);
	if (!$stmt->execute()) {
		$pesan = array(
				"status"=>"gagal",
				"pesan"=>"terjadi kesalahan pada notifikasi."
			);
		echo json_encode($pesan);
		exit();
	};


	$pesan = array(
			"status" => "berhasil",
			"pesan" => "berhasil mengirim pesan"
		);
	echo json_encode($pesan);
	exit();
};
?>