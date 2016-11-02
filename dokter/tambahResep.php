<?php
date_default_timezone_set('Asia/Jakarta');
require '../koneksi.php';
$fields = array(
		"nomedis"=>true,
		"dokter"=>true
	);
require "../parameter.php";
$waktu = date('Y-m-d H:i:s');
$nomedis = $data->nomedis;
$dokter = $data->dokter;
$long = $data->long;
$termins = array();
for ($i=0; $i < $long; $i++) { 
	$termin = "termin" . $i;
	if ($data->{$termin} != null) {
		$termins[]=$data->{$termin};
	};	
};
// var_dump($termins);
// exit();
$tanggal = $data->tanggal;
$masa_berlaku = $data->masa_berlaku;
$stmt = $conn->prepare("INSERT INTO header_resep (dokter, nomedis, tanggal) VALUES (?,?,?)");
$stmt->bind_param("iis", $dokter, $nomedis, $waktu);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan server"
		);
	echo json_encode($pesan);
	exit();
} else {	
	$id = $stmt->insert_id;
	$values = "";
	for ($i=0; $i < count($termins); $i++) {
		if ($i == count($termins) - 1) {
			$values = $values . "(" . "'" . $termins[$i]. "'" . "," . $id . ")";
		} else {
			$values = $values . "(" . "'" . $termins[$i]. "'" . "," . $id . "),";
		};
	};
	// $stmt = $conn->prepare("INSERT INTO detail_resep (id_header, termin) VALUES " . $values);
	$stmt = $conn->prepare("INSERT INTO detail_resep (termin, id_header) VALUES " . $values);
	if (!$stmt->execute()) {
		$pesan = array(
				"status"=>"gagal",
				"pesan"=>"terjadi kesalahan server."
			);
		echo json_encode($pesan);
		exit();
	} else {
		$pesan = array(
				"status" => "berhasil",
				"pesan" => "resep berhasil di tambahkan."
			);
		echo json_encode($pesan);
		exit();
	}
}
?>