<?php
require '../koneksi.php';
$fields = array(		
		"nama"=>true,
		"kelas"=>false,
		"TT_per_kamar"=>true,
		"jumlah_TT"=>true,
		"harga"=>true,
		"deskripsi"=>true,
		"jenis"=>true,
		"gambar5"=>true,
		"gambar6"=>true,
		"gambar7"=>true,
		"gambar8"=>true
	);
require "../parameter.php";
$nama = $data->nama;
if (empty($data->kelas) || $data->kelas == null) {
	$kelas = null;
} else {
	$kelas = $data->kelas;
};
$TT_per_kamar = $data->TT_per_kamar;
$jumlah_TT = $data->jumlah_TT;
$harga = $data->harga;
$deskripsi = $data->deskripsi;
$jenis = $data->jenis;
$gambar1 = $data->gambar5;
$gambar2 = $data->gambar6;
$gambar3 = $data->gambar7;
$gambar4 = $data->gambar8;
function decodeBase($e){
	if ($e || $e != null) {
		$tmp = explode(",", $e);
		$eks = explode('/', $tmp[0]);
		$eks = explode(';', end($eks));
		$eks = ($eks[0] == 'jpeg') ? 'jpg' : $eks[0];
		$namaFile = $nama . "_". uniqid() . 'fasilitas.' . $eks;
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
$gambar4 = decodeBase($gambar4);								
$stmt = $conn->prepare("INSERT INTO fasilitas (nama, kelas, TT_per_kamar, jumlahTT, harga, deskripsi, jenis) VALUES (?,?,?,?,?,?,?)");
$stmt->bind_param("ssiiiss", $nama, $kelas, $TT_per_kamar, $jumlah_TT, $harga, $deskripsi, $jenis);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan server"
		);
	echo json_encode($pesan);
	exit();
} else {	
	$id = $stmt->insert_id;
	$stmt = $conn->prepare("INSERT INTO gambar_fasilitas (id_fasilitas,url) VALUES (?,?),(?,?),(?,?),(?,?)");
	$stmt->bind_param("isisisis", $id,$gambar1,$id,$gambar2,$id,$gambar3,$id,$gambar4);
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