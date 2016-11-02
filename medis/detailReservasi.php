<?php
require '../koneksi.php';
$fields = array(
		"no_reservasi" => true,
		"user_id"=>true
	);
require "../parameter.php";
$no_reservasi = $data->no_reservasi;
$output =array();
$stmt = $conn->prepare("SELECT r.id,r.tanggal, r.keterangan, r.nomedis, CONCAT(u.nama_depan, ' ', u.nama_belakang) as nama, f.nama as ruangan, f.kelas, f.id as id_ruangan FROM reservasi as r, pasien as p, fasilitas as f, user as u WHERE u.id = (SELECT user_id FROM pasien WHERE nomedis = r.nomedis ) AND r.kamar = f.id AND r.id = ? AND r.status = 0 ORDER BY r.id DESC LIMIT 1");
$stmt->bind_param("i", $no_reservasi);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	$output = $res->fetch_array(MYSQLI_ASSOC);
};
$pesan = array(
		"status"=>"berhasil",
		"pesan"=>null,
		"data"=>$output
	);
echo json_encode($pesan);
exit();
?>