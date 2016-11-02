<?php
require '../koneksi.php';
$fields = array(
		"nopegawai"=> true
	);
require '../parameter.php';
$nopegawai = $data->nopegawai;
$output = array();
$stmt = $conn->prepare("SELECT kk.id, kk.judul, kk.waktu, sp.spesialis, (SELECT COUNT(kiriman_id) FROM jawaban_konsul WHERE kiriman_id = kk.id) as jumlah_reply, (SELECT CONCAT(nama_depan,' ',nama_belakang) as nama FROM user WHERE user.id = kk.pengirim) as pengirim FROM kiriman_konsul as kk INNER JOIN spesialis as sp ON kk.spesialis = sp.id WHERE kk.spesialis = (SELECT spesialis FROM dokter WHERE nopegawai = ?) GROUP BY kk.id ORDER BY waktu DESC");
$stmt->bind_param("i",$nopegawai);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalah pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
		array_push($output, $row);
	};
};
$pesan = array(
		"status"=>"berhasil",
		"pesan"=>null,
		"data"=>$output
	);
echo json_encode($pesan);
exit();
?>