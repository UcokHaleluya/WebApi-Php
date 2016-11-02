<?php
require "koneksi.php";
$fileds = array(
	"user_id"=>true,
	"dokumen"=>true
);
$user_id = $data->user_id;
$output = array();
require "paramter.php";
$stmt = $conn->prepare("SELECT * from dokumen WHERE user_id = ?");
$stmt->bind_param("i",$user_id);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server."
	);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($output, $row);
	};
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>null,
			"data"=>$output
		);
	echo json_encode($pesan);
	exit();
};
?>