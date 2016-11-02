<?php
require '../koneksi.php';
$fields = array(
		"user_id"=>true,
		"tgl_awal"=>true,
		"tgl_akhir"=>false
	);
$output = array();
require "../parameter.php";
$tgl_awal = $data->tgl_awal;
if ($data->tgl_akhir || $data->tgl_akhir != null) {
	$tgl_akhir = $data->tgl_akhir;	
	$stmt = $conn->prepare("SELECT a.* , b.nama, b.kelas FROM reservasi as a INNER JOIN fasilitas as b ON a.kamar = b.id WHERE a.status = 0 AND a.tanggal BETWEEN ? AND ?");
	$stmt->bind_param("ss", $tgl_awal, $tgl_akhir);
	if (!$stmt->execute()) {
		$pesan = array(
				"status"=>"gagal",
				"pesan"=>"terjadi kesalahan pada server"
			);
		echo json_encode($pesan);
		exit();
	} else {
		$res = $stmt->get_result();		
		while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
			array_push($output, $row);
		};
		$pesan = array(
				"statu"=>"berhasil",
				"pesan"=>null,
				"data"=>$output
			);
		echo json_encode($pesan);
		exit();
	};
} else {
	$stmt = $conn->prepare("SELECT * FROM reservasi WHERE tanggal = ?");
	$stmt->bind_param("s", $tgl_awal);	
	if (!$stmt->execute()) {
			$pesan = array(
					"status"=>"gagal",
					"pesan"=>"terjadi kesalahan pada server"
				);
			echo json_encode($pesan);
			exit();
		} else {
			$res = $stmt->get_result();
			while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
				array_push($output, $row);
			};
			$pesan = array(
					"statu"=>"berhasil",
					"pesan"=>null,
					"data"=>$output
				);
			echo json_encode($pesan);
			exit();
		};
};
?>