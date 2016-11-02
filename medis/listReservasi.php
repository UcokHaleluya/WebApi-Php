<?php
require '../koneksi.php';
$fields = array(
		"user_id"=>true,
		"tanggal"=>false,
		"no_reservasi"=>false
	);
require '../parameter.php';
$output = array();

if (!empty($data->tanggal)) {
	$tanggal = $data->tanggal;
	$tanggal = "%".$tanggal."%";
	$stmt = $conn->prepare("SELECT *, (SELECT nama FROM fasilitas WHERE id = reservasi.kamar) as nama_kamar FROM reservasi WHERE tanggal LIKE ? AND status = 0");
	$stmt->bind_param("s",$tanggal);
} else if (!empty($data->no_reservasi)){
	$no_reservasi = $data->no_reservasi;
	$stmt = $conn->prepare("SELECT *, (SELECT nama FROM fasilitas WHERE id = reservasi.kamar) as nama_kamar FROM reservasi WHERE id = ? AND status = 0");
	$stmt->bind_param("i",$no_reservasi);
} else {
	$stmt = $conn->prepare("SELECT *, (SELECT nama FROM fasilitas WHERE id = reservasi.kamar) as nama_kamar FROM reservasi WHERE status = 0");
};
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan saat memuat data."
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