<?php
require '../koneksi.php';
$fields = array(		
		"nama"=>true,
		"jumlah_dokter"=>true,
		"keterangan"=>true,
		"layanan"=>true,
		"gambar1"=>true,
		"gambar2"=>true,
		"gambar3"=>true
	);
require "../parameter.php";
$nama = $data->nama;
$jumlah_dokter = $data->jumlah_dokter;
$keterangan = $data->keterangan;
$layanan = $data->layanan;
$gambar1 = $data->gambar1;
$gambar2 = $data->gambar2;
$gambar3 = $data->gambar3;
function decodeBase($e){
	if ($e || $e != null) {
		$tmp = explode(",", $e);
		$eks = explode('/', $tmp[0]);
		$eks = explode(';', end($eks));
		$eks = ($eks[0] == 'jpeg') ? 'jpg' : $eks[0];
		$namaFile = $pengirim . "_". uniqid() . 'layanan.' . $eks;
		$gambar = base64_decode(end($tmp));
		if (isset($gambar)) {
			if(file_put_contents(__DIR__ . "/image/$namaFile", $gambar)) {
				$file = "http://localhost:8080/SKRIPSI/api-elsa/API/admin/image/" . $namaFile;
				return $file;
			};
		};
	}
};
$gambar1 = decodeBase($gambar1);
$gambar2 = decodeBase($gambar2);
$gambar3 = decodeBase($gambar3);
$spesialis = $data->spesialis;
$new_list = array();
$stmt = $conn->prepare("INSERT INTO layanan (nama, jumlah_dokter, keterangan, layanan) VALUES (?,?,?,?)");
$stmt->bind_param("siss", $nama,$jumlah_dokter,$keterangan,$layanan);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan server"
		);
	echo json_encode($pesan);
	exit();
} else {	
	$id = $stmt->insert_id;
	$stmt = $conn->prepare("INSERT INTO gambar_layanan (id_layanan,url) VALUES (?,?),(?,?),(?,?)");
	$stmt->bind_param("isisis", $id,$gambar1,$id,$gambar2,$id,$gambar3);
	if (!$stmt->execute()) {
		$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan server."
		);
		echo json_encode($pesan);
		exit();		
	}
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"berhasil menambah data"
	);
	echo json_encode($pesan);
	exit();	
};
?>