<?php
require '../koneksi.php';
//parameter
$fields = array(
		"nomedis"=>true
	);
require "../parameter.php";
$nomedis = $data->nomedis;
$r_inap = array();
$obat_alkes = array();
$penunjang = array();
$stmt = $conn->prepare("SELECT a.*, b.nama, b.keterangan, b.biaya FROM transaksi_inap as a INNER JOIN jenis_inap as b ON a.id_jenis=b.id WHERE a.user_id = (SELECT user_id FROM pasien WHERE nomedis = ?) AND a.active = 1");
$stmt->bind_param("i", $nomedis);
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
		array_push($r_inap, $row);
	}
};
$stmt = $conn->prepare("SELECT a.*, b.nama, b.nama, b.keterangan, b.harga FROM trans_obat_alkes as a INNER JOIN barang_medis as b ON a.id_barang = b.id WHERE a.user_id = (SELECT user_id FROM pasien WHERE nomedis = ?) AND a.active = 1");
$stmt->bind_param("i", $nomedis);
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
		array_push($obat_alkes, $row);
	};
};
$stmt = $conn->prepare("SELECT a.*, b.nama, b.biaya, b.keterangan FROM trans_penunjang as a INNER JOIN penunjang_medis as b ON a.id_penunjang = b.id WHERE a.nomedis = ? AND a.active = 1");
$stmt->bind_param("i", $nomedis);
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
		array_push($penunjang, $row);
	}
};
$pesan = array(
		"status"=>"berhasil",
		"pesan"=>null,
		"r_inap"=> $r_inap,
		"obat_alkes"=> $obat_alkes,
		"penunjang"=>$penunjang
	);
echo json_encode($pesan);
exit();
?>