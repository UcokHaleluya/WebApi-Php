<?php
require '../koneksi.php';
$fields = array(
		"user_id"=>true
	);
require '../parameter.php';
$user_id = $data->user_id;
$output = array();
$user_data = array();
$dokter_data = array();
$pendidikan_dokter = array();
$pelatihan_dokter = array();
$organisasi_dokter = array();
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i",$user_id);
if (!$stmt->execute()) {
	$pesan = array(
		"status"=>"gagal",
		"pesan"=>"kesalahan saat memuat User_data."
	);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	$user_data = $res->fetch_array(MYSQLI_ASSOC);
};
$stmt = $conn->prepare("SELECT dokter.nopegawai, spesialis.spesialis FROM dokter INNER JOIN spesialis ON dokter.spesialis = spesialis.id WHERE dokter.user_id = ?");
$stmt->bind_param("i",$user_id);
if (!$stmt->execute()) {
	$pesan = array(
		"status"=>"gagal",
		"pesan"=>"kesalahan saat memuat dokter_data."
	);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	$dokter_data = $res->fetch_array(MYSQLI_ASSOC);	
};
$nopegawai = $dokter_data["nopegawai"];
$stmt = $conn->prepare("SELECT * FROM pendidikan_dokter WHERE nopegawai = ?");
$stmt->bind_param("i",$nopegawai);
if (!$stmt->execute()) {
	$pesan = array(
		"status"=>"gagal",
		"pesan"=>"kesalahan saat memuat pendidikan_data."
	);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
	array_push($pendidikan_dokter, $row);
	};
};
$stmt = $conn->prepare("SELECT * FROM pelatihan_dokter WHERE nopegawai = ?");
$stmt->bind_param("i",$nopegawai);
if (!$stmt->execute()) {
	$pesan = array(
		"status"=>"gagal",
		"pesan"=>"kesalahan saat memuat pelatihan_dokter."
	);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
	array_push($pelatihan_dokter, $row);
	};
};
$stmt = $conn->prepare("SELECT * FROM organisasi_dokter WHERE nopegawai = ?");
$stmt->bind_param("i",$nopegawai);
if (!$stmt->execute()) {
	$pesan = array(
		"status"=>"gagal",
		"pesan"=>"kesalahan saat memuat organisasi_dokter."
	);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
	array_push($organisasi_dokter, $row);
	};
};
$pesan = array(
		"status"=>"berhasil",
		"pesan"=>null,
		"user_data"=>$user_data,
		"dokter_data"=>$dokter_data,
		"pendidikan_dokter"=>$pendidikan_dokter,
		"pelatihan_dokter"=>$pelatihan_dokter,
		"organisasi_dokter"=>$organisasi_dokter
	);
echo json_encode($pesan);
exit();
?>