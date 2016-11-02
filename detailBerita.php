<?php
//koneksi ke server
require "koneksi.php";

//validasi parameter
$fields = array("id" => true);
require "parameter.php";

$id = $data->id;

$output = array();
$stmt = $conn->prepare("SELECT * FROM berita_terkini WHERE id= ?");
$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
	$pesan = array("status" => "gagal", "pesan" => "terjadi kesalahan server");
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {

		array_push($output, $row);
	}
};

if (count($output) < 1) {
	$pesan = array("status"=>"berhasil", "pesan"=>"identitas berita salah", "data"=> null);
} else {
	$pesan = array("status"=> "berhasil",
				   "pesan"=> null,
				   "data"=> $output
		);
};
echo json_encode($pesan);
exit();
?>