<?php

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
$tanggal = mysqli_real_escape_string($con, $data->tanggal);
$isi = mysqli_real_escape_string($con, $data->isi);
$id = mysqli_real_escape_string($con, $data->id);

 
$sql = "update berita_terkini set judul='$judul',kategori='$kategori',tanggal='$tanggal',isi='$isi' where id ='$id'";

if (!mysqli_query($con, $sql)) {
  die('Error: ' . mysqli_error($con));
}
echo "1 record update";

mysqli_close($conn);
 
?>