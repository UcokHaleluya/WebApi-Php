<?php
require '../koneksi.php';
$fields = array(
		"nopegawai"=>true,
		"organisasi"=>true
	);
require "../parameter.php";
$nopegawai = $data->nopegawai;
$organisasi = $data->organisasi;
$new_list = array();
$stmt = $conn->prepare("INSERT INTO organisasi_dokter (nopegawai,organisasi) VALUES (?,?)");
$stmt->bind_param("is", $nopegawai,$organisasi);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$stmt = $conn->prepare("SELECT * FROM organisasi_dokter WHERE nopegawai = ?");
	$stmt->bind_param("i", $data->nopegawai);
	if ($stmt->execute()) {
		$res = $stmt->get_result();
		while ($output = $res->fetch_array(MYSQLI_ASSOC)) {
			array_push($new_list, $output);	
		};		
	}
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"organisasi '" . $organisasi . "' berhasil ditambahkan",
			"data"=>$new_list
		);
	echo json_encode($pesan);
	exit();
};
?>