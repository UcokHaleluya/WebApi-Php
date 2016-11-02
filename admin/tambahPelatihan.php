<?php
require '../koneksi.php';
$fields = array(
		"nopegawai"=>true,
		"pelatihan"=>true
	);
require "../parameter.php";
$nopegawai = $data->nopegawai;
$pelatihan = $data->pelatihan;
$new_list = array();
$stmt = $conn->prepare("INSERT INTO pelatihan_dokter (nopegawai,pelatihan) VALUES (?,?)");
$stmt->bind_param("is", $nopegawai,$pelatihan);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$stmt = $conn->prepare("SELECT * FROM pelatihan_dokter WHERE nopegawai = ?");
	$stmt->bind_param("i", $data->nopegawai);
	if ($stmt->execute()) {
		$res = $stmt->get_result();
		while ($output = $res->fetch_array(MYSQLI_ASSOC)) {
			array_push($new_list, $output);	
		};		
	}
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"pelatihan '" . $pelatihan . "' berhasil ditambahkan",
			"data"=>$new_list
		);
	echo json_encode($pesan);
	exit();
};
?>