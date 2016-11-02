<?php
require '../koneksi.php';
$fields = array(
		"nopegawai"=>true,
		"pendidikan"=>true
	);
require "../parameter.php";
$nopegawai = $data->nopegawai;
$pendidikan = $data->pendidikan;
$new_list = array();
$stmt = $conn->prepare("INSERT INTO pendidikan_dokter (nopegawai,pendidikan) VALUES (?,?)");
$stmt->bind_param("is", $nopegawai,$pendidikan);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$stmt = $conn->prepare("SELECT * FROM pendidikan_dokter WHERE nopegawai = ?");
	$stmt->bind_param("i", $data->nopegawai);
	if ($stmt->execute()) {
		$res = $stmt->get_result();
		while ($output = $res->fetch_array(MYSQLI_ASSOC)) {
			array_push($new_list, $output);	
		};		
	}
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"pendidikan '" . $pendidikan . "' berhasil ditambahkan",
			"data"=>$new_list
		);
	echo json_encode($pesan);
	exit();
};
?>