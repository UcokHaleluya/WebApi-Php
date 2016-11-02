<?php
date_default_timezone_set('Asia/Jakarta');
require '../koneksi.php';
$fields = array(
		"nopegawai"=>true,
		'nomedis'=>true,
		'diagnosa_awal'=>true,
		'kode_diagnosa_awal'=>true,
		'diagnosa_akhir'=>true,
		'kode_diagnosa_akhir'=>true,
		'operasi'=>false,
		'penyakit'=>true,
		'terapi'=>false,
		'kondisi_pulang'=>true				
	);
require '../parameter.php';
$dokter = $data->dokter;
$nomedis = $data->nomedis;
$diagnosa_awal = $data->diagnosa_awal;
$kode_diagnosa_awal = $data->kode_diagnosa_awal;
$diagnosa_akhir = $data->diagnosa_akhir;
$kode_diagnosa_akhir = $data->kode_diagnosa_akhir;
if (empty($data->operasi)) {
	$operasi = $data->operasi;
} else {
	$operasi = null;
};
$penyakit = $data->penyakit;
if (empty($data->terapi)) {
	$terapi = $data->terapi;
} else {
	$terapi = null;
};
$kondisi_pulang = $data->kondisi_pulang;
$tanggal = date('Y-m-d H:i:s');
$output = array();
$nopegawai = $data->nopegawai;
$stmt = $conn->prepare("INSERT INTO resume_medis (dokter,nomedis,diagnosa_awal,kode_diagnosa_awal,diagnosa_akhir,kode_diagnosa_akhir,operasi,penyakit,terapi,kondisi_pulang,tanggal) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
$stmt->bind_param("iisssssssss", $dokter,$nomedis,$diagnosa_awal,$kode_diagnosa_awal,$diagnosa_akhir,$kode_diagnosa_akhir,$operasi,$penyakit,$terapi,$kondisi_pulang,$tanggal);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan saat memuat data."
		);
	echo json_encode($pesan);
	exit();
} else {
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"Resume medis pasien berhasil ditambahkan."
		);
	echo json_encode($pesan);
	exit();
};
?>