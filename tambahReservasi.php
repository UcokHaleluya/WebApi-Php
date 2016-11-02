<?php
//koneksi ke server
require "koneksi.php";

//validasi parameter
$fields = array("no_medis" => true,  
				"tanggal"=>true, 
				"jenis_kamar" => true, 
				"keterangan"=> false,
				"nohp" => true);
require "parameter.php";

$no_medis = $data->no_medis;
$nama_pasien = $data->nama_pasien;
$tanggal = $data->tanggal;
$jenis_kamar = $data->jenis_kamar;
$waktu = date('Y-m-d H:i:s');

if (!empty($data->keterangan)) {
	$keterangan = $data->keterangan;	
} else {
	$keterangan = null;
};
$nohp = $data->nohp;
$status = "0";
$output = array();

$stmt = $conn->prepare("INSERT INTO reservasi (kamar,keterangan, nohp, nomedis, status, tanggal) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param('sssiss',$jenis_kamar,$keterangan,$nohp,$no_medis,$status,$tanggal);
if (!$stmt->execute()) {
	$pesan = array("status" => "gagal", "pesan" => "terjadi Kesalahan pada server");
	echo json_encode($pesan);
	exit();
	} else {
		//$input = "SELECT 'konsultasi baru' as keterangan, 'konsultasiDetail' as link, a.user_id, " .$waktu. " as waktu, b.id as `to` FROM pasien as a, user as b WHERE b.role = 'M' AND a.nomedis =".$no_medis;
		$stmt = $conn->prepare("INSERT INTO notif (keterangan, link, user_id, waktu, `to`) SELECT 'Reservasi Baru' as keterangan, 'detailReservasi' as link, a.user_id , ? as waktu, b.id as `to` FROM pasien as a, user as b WHERE b.role = 'M' AND a.nomedis = ?");
		$stmt->bind_param("si", $waktu, $no_medis);
		if (!$stmt->execute()) {
			$pesan = array(
					"status"=>"gagal",
					"pesan"=>"terjadi kesalahan pada notifikasi."
				);
			echo json_encode($pesan);
			exit();
		};
		$pesan = array("status" => "berhasil", "pesan" => "Reservasi telah di tambahkan");
		echo json_encode($pesan);
		exit();
	}; 
?>