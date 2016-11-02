<?php
//koneksi ke server
require "koneksi.php";

//deklarasi parameter
$fields = array("user_id" => true);
require "parameter.php";
$user_id = $data->user_id;
$pasien = array();
$perawatan = array();
$dokter = array();
$ruangan = array();
//Query db
$stmt = $conn->prepare("SELECT a.*, b.nama_depan, b.nama_belakang, b.tgl_lahir, b.jenis_kelamin, b.auth_id, b.email, a.no_identitas ,b.foto FROM pasien as a INNER JOIN user as b ON a.user_id = b.id WHERE user_id=?");
$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
	$pesan = array("status" => "gagal", "pesan" => "gagal memuat data dari server");
	echo json_encode($pesan);
} else {
	$res = $stmt->get_result();
	$pasien = $res->fetch_array(MYSQLI_ASSOC);
};
if (!empty($pasien['nomedis'])) {
	$stmt = $conn->prepare("SELECT * FROM data_perawatan WHERE nomedis=? AND status = 0");
	$stmt->bind_param("i",$pasien["nomedis"]);
	if (!$stmt->execute()) {
		$pesan = array(
				"status"=>"gagal",
				"pesan"=>"kesalahan pada server"
			);
		echo json_encode($pesan);
		exit();
	} else {
		$res = $stmt->get_result();
		$perawatan = $res->fetch_array(MYSQLI_ASSOC);		
	};
};
if (!empty($perawatan['dokter'])) {
	$stmt = $conn->prepare("SELECT CONCAT(nama_depan,' ',nama_belakang) AS Dokter FROM user INNER JOIN dokter ON user.id = dokter.user_id where dokter.nopegawai=?");
	$stmt->bind_param("i", $perawatan['dokter']);
	if (!$stmt->execute()) {
		$pesan = array(
				'status'=>"gagal",
				"pesan"=> "kesalahan pada server"
			);
		echo json_encode($pesan);
		exit();
	} else {
		$res = $stmt->get_result();
		$dokter = $res->fetch_array(MYSQLI_ASSOC);
	};
};
if (!empty($perawatan["ruangan"])) {
	$stmt= $conn->prepare("SELECT nama, kelas FROM fasilitas WHERE id=?");
	$stmt->bind_param("i", $perawatan["ruangan"]);
	if (!$stmt->execute()) {
		$pesan = array(
				"status"=>"gagal",
				"pesan"=>"kesalahan pada server"
			);
		echo json_encode($pesan);
		exit();
	} else {
		$res = $stmt->get_result();
		$ruangan = $res->fetch_array(MYSQLI_ASSOC);
	};
};

$pasien["ruangan"] = $ruangan['nama'];
$pasien["kelas"] = $ruangan['kelas'];
$pasien["dokter"] = $dokter["Dokter"];
$pasien["perawatan"] = $perawatan;
$pesan = array(
		"status"=>"berhasil",
		"pesan"=>null,
		"data"=> $pasien
	);
echo json_encode($pesan);
exit();
?>