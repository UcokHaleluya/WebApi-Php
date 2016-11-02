<?php
require '../koneksi.php';
$fields = array(
		"nomedis"=>true,
		"dokter"=>false,
		"ruangan"=>false,
		"no_TT"=>false,
		"penjamin_bayar"=>true,
		"instansi"=>false
	);
require "../parameter.php";
if (!$data->dokter || $data->dokter == null) {
	$dokter = null;
} else {
	$dokter = $data->dokter;
};
if (!$data->ruangan || $data->ruangan == null) {
	$ruangan = null;
} else {
	$ruangan = $data->ruangan;
};
if (!$data->no_TT || $data->no_TT == null) {
	$no_TT = null;
} else {
	$no_TT = $data->no_TT;
};
if (!$data->instansi || $data->instansi == null) {
	$instansi = null;
} else {
	$instansi = $data->instansi;
};
$nomedis = $data->nomedis;
$tanggal_masuk = date('Y-m-d H:i:s');
$penjamin_bayar = $data->penjamin_bayar;

$stmt = $conn->prepare("INSERT INTO data_perawatan (nomedis, dokter, tanggal_masuk, ruangan, no_TT, penjamin_bayar, instansi) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iisiiss", $nomedis, $dokter, $tanggal_masuk, $ruangan, $no_TT, $penjamin_bayar, $instansi);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"Pasien rawat inap berhasil ditambahkan"
		);
	echo json_encode($pesan);
	exit();
};	

?>