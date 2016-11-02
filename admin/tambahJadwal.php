<?php
require '../koneksi.php';
$fields = array(
		"nopegawai"=>true,
		"senin"=>false,
		"selasa"=>false,
		"rabu"=>false,
		"kamis"=>false,
		"jumat"=>false,
		"sabtu"=>false,
		"minggu"=>false,
	);
require "../parameter.php";
$nopegawai = $data->nopegawai;
if (empty($data->senin) || $data->senin == null) {
	$senin = null;
} else {
	$senin = $data->senin;
};
if (empty($data->selasa) || $data->selasa == null) {
	$selasa = null;
} else {
	$selasa = $data->selasa;
};
if (empty($data->rabu) || $data->rabu == null) {
	$rabu = null;
} else {
	$rabu = $data->rabu;
};
if (empty($data->kamis) || $data->kamis == null) {
	$kamis = null;
} else {
	$kamis = $data->kamis;
};
if (empty($data->jumat) || $data->jumat == null) {
	$jumat = null;
} else {
	$jumat = $data->jumat;
};
if (empty($data->sabtu) || $data->sabtu == null) {
	$sabtu = null;
} else {
	$sabtu = $data->sabtu;
};
if (empty($data->minggu) || $data->minggu == null) {
	$minggu = null;
} else {
	$minggu = $data->minggu;
};
$new_list = array();
$stmt = $conn->prepare("INSERT INTO jadwal_dokter (nopegawai,senin,selasa,rabu,kamis,jumat,sabtu,minggu) VALUES (?,?,?,?,?,?,?,?)");
$stmt->bind_param("isssssss", $nopegawai,$senin,$selasa,$rabu,$kamis,$jumat,$sabtu,$minggu);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$stmt = $conn->prepare("SELECT * FROM jadwal_dokter WHERE nopegawai= ? ORDER BY id DESC LIMIT 1 ");
	$stmt->bind_param("i", $data->nopegawai);
	if ($stmt->execute()) {
		$res = $stmt->get_result();
		while ($output = $res->fetch_array(MYSQLI_ASSOC)) {
			array_push($new_list, $output);	
		};		
	}
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"jadwal berhasil ditambahkan",
			"data"=>$new_list
		);
	echo json_encode($pesan);
	exit();
};
?>