<?php
require 'koneksi.php';
$fields = array(
		"nomedis" => true,
	);
require "parameter.php";
$nomedis = $data->nomedis;
$output =array();
$stmt = $conn->prepare("SELECT r.id,r.tanggal, CONCAT(u.nama_depan, ' ', u.nama_belakang) as nama, f.nama as ruangan FROM reservasi as r, pasien as p, fasilitas as f, user as u WHERE u.id = (SELECT user_id FROM pasien WHERE nomedis = ? ) AND r.kamar = f.id AND r.nomedis = ? AND r.status = 0 ORDER BY r.id DESC LIMIT 1");
$stmt->bind_param("ii", $nomedis, $nomedis);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	$output = $res->fetch_array(MYSQLI_ASSOC);
};
$pesan = array(
		"status"=>"berhasil",
		"pesan"=>null,
		"data"=>$output
	);
echo json_encode($pesan);
exit();
?>