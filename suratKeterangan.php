<?php
require "koneksi.php";
$fields = array(
		'id_surat' => true
	);
require "parameter.php";
$output = array();
$id_surat = $data->id_surat;
$stmt = $conn->prepare("SELECT tanggal,
 masa_berlaku, 
(SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama FROM user WHERE id = (SELECT user_id FROM pasien WHERE nomedis = surat_keterangan.nomedis)) as nama,
(SELECT jenis_kelamin FROM user WHERE id = (SELECT user_id FROM pasien WHERE nomedis = surat_keterangan.nomedis)) as JK,
(SELECT pekerjaan FROM pasien WHERE nomedis = surat_keterangan.nomedis) as pekerjaan,
(SELECT desa FROM pasien WHERE nomedis = surat_keterangan.nomedis) as desa,
(SELECT kecamatan FROM pasien WHERE nomedis = surat_keterangan.nomedis) as kecamatan,
(SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama FROM user WHERE id = (SELECT user_id FROM dokter WHERE nopegawai = surat_keterangan.dokter)) as dokter 
FROM surat_keterangan WHERE id = ?");
$stmt->bind_param("i",$id_surat);
if (!$stmt->execute()) {
	$pesan = array(
			"status" => "gagal",
			"pesan" => "terjadi kesalahan server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	$output = $res->fetch_array(MYSQL_ASSOC);
	$pesan = array(
			"status" => "berhasil",
			"pesan" => null,
			"data" => $output
		);
	echo json_encode($output);
	exit();
};
?>