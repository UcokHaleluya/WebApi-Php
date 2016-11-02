<?php
require '../koneksi.php';
$fields = array(
	'dokter'=>true,
	'nomedis'=>true,
	'tanggal'=>true,
	'masa_berlaku'=>true
);
require "../parameter.php";
$dokter = $data->dokter;
$nomedis = $data->nomedis;
$tanggal = $data->tanggal;
$masa_berlaku = $data->masa_berlaku;
$stmt = $conn->prepare("INSERT INTO surat_keterangan (dokter, nomedis, tanggal, masa_berlaku) VALUES (?,?,?,?)");
$stmt->bind_param("iisi", $dokter, $nomedis, $tanggal, $masa_berlaku);
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
			"pesan"=>"surat keterangan berhasil di berikan."
		);
	echo json_encode($pesan);
	exit();
};
?>