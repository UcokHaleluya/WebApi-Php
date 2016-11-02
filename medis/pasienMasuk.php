<?php
require '../koneksi.php';
$fields = array(
		"nomedis"=>true,
		"dokter"=>false,
		"ruangan"=>false,
		"no_TT"=>false,
		"penjamin_bayar"=>true,
		"instansi"=>false,
		"rawat_inap"=>false,
		"perawatan"=>false
	);
require "../parameter.php";
if (!$data->dokter || $data->dokter == null) {
	$dokter = null;
} else {
	$dokter = $data->dokter;
};
if ($data->perawatan || $data->perawatan != null) {
	$perawatan = $data->perawatan;
} 
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
$rawat_inap = $data->rawat_inap;
$nomedis = $data->nomedis;
$tanggal_masuk = date('Y-m-d H:i:s');
$penjamin_bayar = $data->penjamin_bayar;

if (!$rawat_inap || $rawat_inap == null) {
	$stmt = $conn->prepare("INSERT INTO trans_penunjang (nomedis, id_penunjang, tanggal) VALUES (?,?,?)");
	$stmt->bind_param("iis",$nomedis, $perawatan, $tanggal_masuk);
	if (!$stmt->execute()) {
		$pesan = array(
				"status"=>"gagal",
				"pesan"=>"terjadi kesalahan pada server."
			);
		echo json_encode($pesan);
		exit();
	}
}

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
			"pesan"=>"Data pasien terlah di Update."
		);
	echo json_encode($pesan);
	exit();
};	

?>