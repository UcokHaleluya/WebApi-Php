<?php
require '../koneksi.php';
$fields = array(
		"nomedis"=>true,
		"bayar"=>true,
		"jumlah"=>true
	);
require "../parameter.php";
$nomedis = $data->nomedis;
$bayar = $data->bayar;
$jumlah = $data->jumlah;
$stmt = $conn->prepare("INSERT INTO temp_bayar (nomedis,bayar,jumlah) VALUES (?,?,?)");
$stmt->bind_param("iii", $nomedis,$bayar,$jumlah);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$no_faktur = $stmt->insert_id;
	$stmt = $conn->prepare("UPDATE data_perawatan set status = 1 WHERE nomedis = ?");
	$stmt->bind_param("i", $nomedis);
	if (!$stmt->execute()) {
		$pesan = array(
				"status"=>"gagal",
				"pesan"=>"terjadi kesalahan server"
			);
		echo json_encode($pesan);
		exit();
	}
	$stmt = $conn->prepare("UPDATE transaksi_inap set active = 0 WHERE user_id = (SELECT user_id FROM pasien WHERE nomedis = ?)");
	$stmt->bind_param("i", $nomedis);
	if (!$stmt->execute()) {
		$pesan = array(
				"status"=>"gagal",
				"pesan"=>"terjadi kesalahan server"
			);
		echo json_encode($pesan);
		exit();
	}
	$stmt = $conn->prepare("UPDATE trans_obat_alkes set active = 0 WHERE user_id = (SELECT user_id FROM pasien WHERE nomedis = ?)");
	$stmt->bind_param("i", $nomedis);
	if (!$stmt->execute()) {
		$pesan = array(
				"status"=>"gagal",
				"pesan"=>"terjadi kesalahan server"
			);
		echo json_encode($pesan);
		exit();
	}
	$stmt = $conn->prepare("UPDATE trans_penunjang set active = 0 WHERE nomedis = ?");
	$stmt->bind_param("i",$nomedis);
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
			"pesan"=>"Pembayaran berhasil dilakukan",
			"data"=>$no_faktur
		);
	echo json_encode($pesan);
	exit();
	}
};
?>