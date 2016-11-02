<?php
require 'koneksi.php';
$fields = array(
	"user_id"=>true
	);
require "parameter.php";
$user_id = $data->user_id;
$output = array();
$stmt = $conn->prepare("SELECT kk.id, kk.judul, kk.waktu, sp.spesialis, (SELECT COUNT(kiriman_id) FROM jawaban_konsul WHERE kiriman_id = kk.id) as jumlah_reply FROM kiriman_konsul as kk INNER JOIN spesialis as sp ON kk.spesialis = sp.id WHERE kk.pengirim = ? GROUP BY kk.id ORDER BY waktu DESC");
$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($output, $row);
	};
	$pesan = array(
			"status" => "berhasil",
			"pesan" => "berhasil mengirim pesan",
			"data" => $output
		);
	echo json_encode($pesan);
	exit();
};
?>