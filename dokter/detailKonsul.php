<?php
require '../koneksi.php';
//validasi parameter

$fields = array(
		"kiriman_id"=>true,
		"user_id"=>true
	);
require "../parameter.php";
$kiriman_id = $data->kiriman_id;
$user_id = $data->user_id;
$reply = array();
$quest = array();
$stmt = $conn->prepare("SELECT a.judul, a.waktu, a.pesan, a.file, a.pengirim as user_id,CONCAT(b.nama_depan,' ',b.nama_belakang) as pengirim, b.foto FROM kiriman_konsul a INNER JOIN user b ON a.pengirim = b.id WHERE a.id = ?");
$stmt->bind_param("i", $kiriman_id);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"Terjadi kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$get = $stmt->get_result();
	$quest = $get->fetch_array(MYSQLI_ASSOC);
};
$stmt = $conn->prepare("SELECT jk.waktu, jk.isi, jk.file , jk.pengirim as user_id ,CONCAT(u.nama_depan,' ',u.nama_belakang) as pengirim, u.foto FROM jawaban_konsul as jk INNER JOIN kiriman_konsul as kk ON jk.kiriman_id = kk.id INNER JOIN user as u ON u.id = jk.pengirim WHERE kk.id = ? ORDER BY jk.waktu ASC");
$stmt->bind_param("i", $kiriman_id);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"Terjadi kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($reply, $row);
	};
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>null,
			"konsultasi"=>$quest,
			"jawaban"=>$reply
		);
	echo json_encode($pesan);
	exit();
};
?>