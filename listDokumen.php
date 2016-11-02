<?php
//koneksi ke server
require "koneksi.php";
//validasi parameter
$fields = array("nomedis"=>true,
				"user_id"=>true
	);
require "parameter.php";
$surat_keterangan = array();
$resume_medis = array();
$resep = array();
$hasil_lab = array();
$nomedis = $data->nomedis;
$user_id = $data->user_id;
$stmt = $conn->prepare("SELECT id FROM surat_keterangan WHERE nomedis = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("i", $nomedis);
if (!$stmt->execute()) {
	$pesan = array("status"=>"gagal","pesan"=>"terjadi kesalah server");
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	$surat_keterangan = $res->fetch_array(MYSQLI_ASSOC);
};
$stmt = $conn->prepare("SELECT id FROM resume_medis WHERE nomedis = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("i", $nomedis);
if (!$stmt->execute()) {
	$pesan = array("status"=>"gagal","pesan"=>"terjadi kesalah server");
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	$resume_medis = $res->fetch_array(MYSQLI_ASSOC);
};
$stmt = $conn->prepare("SELECT tanggal,id, (SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama FROM user WHERE id = (SELECT user_id FROM dokter WHERE nopegawai = header_resep.dokter)) as dokter FROM header_resep WHERE nomedis = ?");
$stmt->bind_param("i", $nomedis);
if (!$stmt->execute()) {
	$pesan = array("status"=>"gagal","pesan"=>"terjadi kesalah server");
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($resep, $row);
	};
};
$stmt = $conn->prepare("SELECT id,jenis FROM header_hasil_cek WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
	$pesan = array("status"=>"gagal","pesan"=>"terjadi kesalah server");
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($hasil_lab, $row);
	};
};
$pesan = array(
		"status"=>"berhasil",
		"pesan"=>null,
		"surat_keterangan"=>$surat_keterangan,
		"resume_medis"=>$resume_medis,
		"resep"=>$resep,
		"hasil_lab"=>$hasil_lab
	);
echo json_encode($pesan);
exit();
?>