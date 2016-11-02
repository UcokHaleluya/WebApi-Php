<?php
require 'koneksi.php';
$fields = array(
		"user_id"=>true,
		"nomedis"=>true,
		"no_resume"=>true
	);
require 'parameter.php';
$user_id = $data->user_id;
$nomedis = $data->nomedis;
$no_resume = $data->no_resume;
$resume_medis = array();
$user_data = array();
$dokter = array();
$ruangan = array();
$tambahan = array();
$stmt = $conn->prepare("SELECT dokter,nomedis,diagnosa_awal,diagnosa_akhir,kode_diagnosa_akhir,operasi,penyakit,terapi, kondisi_pulang,tanggal FROM resume_medis WHERE id = ?");
$stmt->bind_param("iissssssss", $dokter,$nomedis,$diagnosa_awal,$diagnosa_akhir,$kode_diagnosa_akhir,$operasi,$penyakit,$terapi, $kondisi_pulang,$tanggal); iissssssss
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server."
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	$resume_medis = $res->fetch_array(MYSQLI_ASSOC);
};
$nopegawai_dokter = $resume_medis["dokter"];
$stmt = $conn->prepare("SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama, tgl_lahir FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server."
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	$user_data = $res->fetch_array(MYSQLI_ASSOC);
};
$stmt = $conn->prepare("SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama, tgl_lahir FROM user WHERE id = (SELECT user_id FROM dokter WHERE nopegawai = ?)");
$stmt->bind_param("i", $nopegawai_dokter);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server."
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	$dokter = $res->fetch_array(MYSQLI_ASSOC);
};
$stmt = $conn->prepare("SELECT CONCAT(c.nama,' - ',c.kelas) AS ruangan FROM data_perawatan a INNER JOIN fasilitas c ON a.ruangan = c.id WHERE c.id = (SELECT ruangan FROM data_perawatan WHERE nomedis = ? AND status = 0)");
$stmt->bind_param("i", $nomedis);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server."
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	$ruangan = $res->fetch_array(MYSQLI_ASSOC);
};
$stmt = $conn->prepare("SELECT b.nama FROM trans_penunjang a INNER JOIN penunjang_medis b ON a.id_penunjang = b.id WHERE a.nomedis = ? AND a.active = 1 GROUP BY b.nama");
$stmt->bind_param("i", $nomedis);
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
		array_push($tambahan, $row);
	};
	
};
$pesan = array(
		"status"=>"berhasil",
		"pesan"=>null,
		"resume_medis"=>$resume_medis,
		"user_data"=>$user_data,
		'ruangan'=>$ruangan,
		"dokter"=>$dokter,
		"tambahan"=>$tambahan
	);
echo json_encode($pesan);
exit();
?>