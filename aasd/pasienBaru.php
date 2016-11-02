<?php
require '../koneksi.php';
$fields = array(
		"email"=>false,
		"nama_depan"=>true,
		"nama_belakang"=>true,
		"tgl_lahir"=>true,
		"jenis_kelamin"=>true,
		"foto"=>false,
		"no_identitas"=>true,
		"agama"=>true,
		"suku"=>true,
		"bangsa"=>true,
		"pekerjaan"=>true,
		"gol_darah"=>false,
		"desa"=>true,
		"kecamatan"=>true,
		"kab_kota"=>true,
		"notelp"=>false,
		"nohp"=>true
	);
require "../parameter.php";
if (!$data->email || $data->email == null) {
	$email = null;
} else {
	$email = $data->email;
};
if (!$data->no_identitas || $data->no_identitas == null) {
	$no_identitas = null;
} else {
	$no_identitas = $data->no_identitas;
};
if (!$data->gol_darah || $data->gol_darah == null) {
	$gol_darah = null;
} else {
	$gol_darah = $data->gol_darah;
};
if (!$data->notelp || $data->notelp == null) {
	$notelp = null;
} else {
	$notelp = $data->notelp;
};
//ambil gambar
if (!$data->foto || $data->foto != null) {
	$foto = $data->foto;
	$tmp = explode(",", $foto);
	$eks = explode('/', $tmp[0]);
	$eks = explode(';', end($eks));
	$eks = ($eks[0] == 'jpeg') ? 'jpg' : $eks[0];
	$namaFile = time() . "_". uniqid() . 'dokter.' . $eks;
	$gambar = base64_decode(end($tmp));
	if (isset($gambar)) {
		if(file_put_contents(__DIR__ . "/kons_img/$namaFile", $gambar)) {
			$foto = "http://localhost:8080/SKRIPSI/api-elsa/API/pas_img/" . $namaFile;
		};
	};
} else {
	$foto = null;
};//

$nama_depan = $data->nama_depan;
$nama_belakang = $data->nama_belakang;
$tgl_lahir = $data->tgl_lahir;
$jenis_kelamin = $data->jenis_kelamin;
$agama = $data->agama;
$suku = $data->suku;
$bangsa = $data->bangsa;
$pekerjaan = $data->pekerjaan;
$desa = $data->desa;
$kecamatan = $data->kecamatan;
$kab_kota = $data->kab_kota;
$nohp = $data->nohp;
$role = "P";
$stmt = $conn->prepare("INSERT INTO user (email, role, nama_depan, nama_belakang, tgl_lahir, jenis_kelamin, foto) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $email, $role, $nama_depan, $nama_belakang, $tgl_lahir, $jenis_kelamin, $foto);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$id = $stmt->insert_id;
	if (!$id || $id != null) {
	$auth_id = time() . $id;
	$stmt = $conn->prepare("UPDATE user set auth_id = ? WHERE id = ?");
	$stmt->bind_param("si", $auth_id, $id);
	if (!$stmt->execute()) {
		$pesan = array(
				"status"=>"gagal",
				"pesan"=>"terjadi kesalahan"
			);
		echo json_encode($pesan);
		exit();
	};
	}
		
};
$stmt = $conn->prepare("INSERT INTO pasien (user_id, no_identitas, agama, suku, bangsa, pekerjaan, gol_darah, desa, kecamatan, kab_kota, notelp, nohp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssssssssss", $id, $no_identitas, $agama, $suku, $bangsa, $pekerjaan, $gol_darah, $desa, $kecamatan, $kab_kota, $notelp, $nohp);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$nomedis = $stmt->insert_id;
	if (!$auth_id || $auth_id == null) {
		$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"pasien baru berhasil ditambahkan, gunakan email untuk Login",
			"data"=> array("nomor medis"=>$nomedis)
			);
	} else {
		$pesan = array(
			"status"=>"berhasil",
			"pesan"=>"pasien baru berhasil ditambahkan",
			"data"=> array("ID Login"=>$auth_id, "nomor medis"=>$nomedis)
		);
	};
	echo json_encode($pesan);
	exit();
};
?>