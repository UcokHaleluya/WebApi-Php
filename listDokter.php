<?php
//koneksi ke server
require "koneksi.php";
//validasi parameter
$fields = array("dokter"=>true);
require "parameter.php";
$output = array();
$dokter = $data->dokter;
$stmt = $conn->prepare("SELECT b.nama_depan, b.nama_belakang, c.spesialis, a.nopegawai, b.foto FROM dokter as a INNER JOIN user as b ON a.user_id = b.id INNER JOIN spesialis as c ON a.spesialis = c.id");
if (!$stmt->execute()) {
	$pesan = array("status"=>"gagal","pesan"=>"terjadi kesalah server");
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($output, $row);
	}
};
if (count($output) < 1) {
	$pesan = array("status"=>"berhasil","pesan"=>"tidak ada data");
	echo json_encode($pesan);
	exit();
} else {
	$pesan = array("status"=>"berhasil",
				   "pesan"=>null,
				   "data"=>$output
		);
	echo json_encode($pesan);
	exit();
};
?>