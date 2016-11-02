<?php
date_default_timezone_set('Asia/Jakarta');
require 'koneksi.php';
$fields = array(
		"judul" => true,
		"pengirim" => true,
		"pesan" => true,
		"spesialis" => true,
		"file" => false
	);
require "parameter.php";
$judul = $data->judul;
$waktu = date('Y-m-d H:i:s');
$pengirim = $data->pengirim;
$pesan = $data->pesan;
$spesialis = $data->spesialis;
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

$stmt = $conn->prepare("INSERT INTO kiriman_konsul (judul, waktu, pengirim, pesan, spesialis, file) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssisis", $judul, $waktu, $pengirim, $pesan, $spesialis, $file);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$id = $stmt->insert_id;
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