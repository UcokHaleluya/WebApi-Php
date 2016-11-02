<?php
// koneksi ke serve
require "koneksi.php";
//validasi parameter
$fields = array("nopegawai"=>true);
require "parameter.php";
$nopegawai = $data->nopegawai;
$output = array();
$pendidikan = array();
$pelatihan = array();
$organisasi = array();
$jadwal = array();
$stmt = $conn->prepare("SELECT a.nopegawai, b.*, c.spesialis as spesialis FROM dokter as a INNER JOIN user as b ON a.user_id = b.id INNER JOIN spesialis as c ON a.spesialis = c.id WHERE a.nopegawai = ?");
$stmt->bind_param("i",$nopegawai);
if (!$stmt->execute()) {
	$pesan = array("status" => "gagal", "pesan"=>"terjadi kesalahan pada server");
	echo json_decode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($output, $row);
	}
};
if (count($output) < 1) {
	$pesan = array("status" => "berhasil", "pesan" => "data kosong");
	echo json_encode($pesan);
	exit(); }
$stmt = $conn->prepare("SELECT * FROM pendidikan_dokter WHERE nopegawai=?");
$stmt->bind_param("i", $nopegawai);
if (!$stmt->execute()) {
	$pesan = array("status" => "gagal", "pesan"=>"terjadi kesalahan pada server");
	echo json_decode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($pendidikan, $row);
	}
};
$stmt = $conn->prepare("SELECT * FROM pelatihan_dokter WHERE nopegawai=?");
$stmt->bind_param("i", $nopegawai);
if (!$stmt->execute()) {
	$pesan = array("status" => "gagal", "pesan"=>"terjadi kesalahan pada server");
	echo json_decode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($pelatihan, $row);
	}
};
$stmt = $conn->prepare("SELECT * FROM organisasi_dokter WHERE nopegawai=?");
$stmt->bind_param("i", $nopegawai);
if (!$stmt->execute()) {
	$pesan = array("status" => "gagal", "pesan"=>"terjadi kesalahan pada server");
	echo json_decode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($organisasi, $row);
	}
};
$stmt = $conn->prepare("SELECT * FROM jadwal_dokter WHERE nopegawai= ? ORDER BY id DESC LIMIT 1 ");
$stmt->bind_param("i", $nopegawai);
if (!$stmt->execute()) {
	$pesan = array("status" => "gagal", "pesan"=>"terjadi kesalahan pada server");
	echo json_decode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($jadwal, $row);
	}
};
$pesan = array("status" => "berhasil", "pesan" => null, "data" => $output, "pendidikan" => $pendidikan, "pelatihan" => $pelatihan, "organisasi" => $organisasi, "jadwal" => $jadwal);
echo json_encode($pesan);
exit();
?>