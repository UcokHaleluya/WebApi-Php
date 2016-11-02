<?php
require 'koneksi.php';
$fields = array(
		"kode_berita"=>true,		
	);
require "parameter.php";
$output = array();
$id = $data->kode_berita;
$stmt = $conn->prepare("DELETE FROM berita_terkini WHERE id = ?");
$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
};
$stmt = $conn->prepare("SELECT id,judul,kategori,tanggal,gambar1,isi FROM berita_terkini ORDER BY tanggal DESC");
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan saat load data."
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($output, $row);
	}
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"Berita berhasil di hapus.",
			"data"=>$output
		);
	echo json_encode($pesan);
	exit();
};

?>