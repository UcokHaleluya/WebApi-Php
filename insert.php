<?php
date_default_timezone_set('Asia/Jakarta');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, x-xsrf-token");

$con=mysqli_connect("localhost","root","","elisabeth");

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$data = json_decode(file_get_contents("php://input"));
$judul = mysqli_real_escape_string($con, $data->judul);
$kategori = mysqli_real_escape_string($con, $data->kategori);
$tanggal = date('Y-m-d');
$isi = mysqli_real_escape_string($con, $data->isi);
$file = mysqli_real_escape_string($con, $data->file);
if ($file != null) {
	$tmp = explode(",", $file);
	$eks = explode('/', $tmp[0]);
	$eks = explode(';', end($eks));
	$eks = ($eks[0] == 'jpeg') ? 'jpg' : $eks[0];
	$namaFile = "_". uniqid() . 'news.' . $eks;
	$gambar = base64_decode(end($tmp));
	if (isset($gambar)) {
		if(file_put_contents(__DIR__ . "/news_img/$namaFile", $gambar)) {
			$file = "http://localhost:8080/SKRIPSI/api-elsa/API/news_img/" . $namaFile;
		};
	};
} else {
	$file = null;
};

$sql = "INSERT INTO berita_terkini(judul,kategori,tanggal,isi, gambar1) values ('$judul','$kategori','$tanggal','$isi', '$file')";
if (!mysqli_query($con, $sql)) {
  die('Error: ' . mysqli_error($con));
}
echo "1 record added";
mysqli_close($conn);
?>