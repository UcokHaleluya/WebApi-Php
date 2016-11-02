<?php
require '../koneksi.php';
$fields = array(		
		"caption"=>true,
		"tanggal"=>true,		
		"gambar"=>true
	);
require "../parameter.php";
$caption = $data->caption;
$tanggal = $data->tanggal;
$gambar = $data->gambar;
function decodeBase($e){
	if ($e || $e != null) {
		$tmp = explode(",", $e);
		$eks = explode('/', $tmp[0]);
		$eks = explode(';', end($eks));
		$eks = ($eks[0] == 'jpeg') ? 'jpg' : $eks[0];
		$namaFile = $tanggal . "_". uniqid() . 'galeri.' . $eks;
		$gambar = base64_decode(end($tmp));
		if (isset($gambar)) {
			if(file_put_contents(__DIR__ . "/image/$namaFile", $gambar)) {
				$file = "http://localhost:8080/SKRIPSI/api-elsa/API/admin/image/" . $namaFile;
				return $file;
			};
		};
	}
};
$gambar = decodeBase($gambar);
$stmt = $conn->prepare("INSERT INTO galeri (foto, caption, date) VALUES (?,?,?)");
$stmt->bind_param("sss", $gambar,$caption,$tanggal);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan server"
		);
	echo json_encode($pesan);
	exit();
} else {		
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"berhasil menambah data"
	);
	echo json_encode($pesan);
	exit();	
};
?>