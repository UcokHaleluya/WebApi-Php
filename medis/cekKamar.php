<?php
require '../koneksi.php';
$fields = array(
		"ruangan"=>true,
		"no_TT"=>true
	);
require '../parameter.php';
$ruangan = $data->ruangan;
$no_TT = $data->no_TT;
$output = array();
$stmt = $conn->prepare("SELECT id FROM data_perawatan WHERE ruangan = ? AND no_TT = ?");
$stmt->bind_param("ii",$ruangan, $no_TT);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$row = $stmt->get_result();
	$output = $row->fetch_array(MYSQLI_ASSOC);
	if (count($output) || $output != null) {
		$pesan = array(
			"status"=>"gagal",
			"pesan"=>"Ruangan Inap masih diisi pasien."
		);
		echo json_encode($pesan);
		exit();
	};
};
?>