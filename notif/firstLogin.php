<?php
require '../koneksi.php';
$fields = array(
		"user_id"=>true
	);
require '../parameter.php';
$user_id = $data->user_id;
$output = array();
$stmt = $conn->prepare("SELECT notif.* , CONCAT(user.nama_depan,' ',user.nama_belakang) AS nama , user.foto FROM notif INNER JOIN user ON user.id = notif.user_id WHERE notif.to = ? AND notif.terbaca = '0'");
$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi keselahan pada server."
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($output, $row);
	}
	$pesan = array(
			"status"=>"berhasil",
			"pesan"=>null,
			"data"=>$output
		);
	echo json_encode($pesan);
	exit();
};
?>