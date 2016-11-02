<?php
require '../koneksi.php';
$fields = array(
		"email"=>true,
		"nama_depan"=>true,
		"nama_belakang"=>true,
		"tgl_lahir"=>true,
		"jenis_kelamin"=>true,
		"foto"=>false,
		"spesialis"=>true		
);
require "../parameter.php";
$email = $data->email;
$nama_depan = $data->nama_depan;
$nama_belakang = $data->nama_belakang;
$tgl_lahir = $data->tgl_lahir;
$jenis_kelamin = $data->jenis_kelamin;
$spesialis = $data->spesialis;
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
			$foto = "http://localhost:8080/SKRIPSI/api-elsa/API/doc_img/" . $namaFile;
		};
	};
} else {
	$foto = null;
};//
$role = "D";
$stmt = $conn->prepare("INSERT INTO user (email, role, nama_depan, nama_belakang, tgl_lahir, jenis_kelamin, foto) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $email, $role, $nama_depan, $nama_belakang, $tgl_lahir, $jenis_kelamin, $foto);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"kesalahan pada server."
		);
	echo json_encode($pesan);
	exit();
} else {
	$id = $stmt->insert_id;
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
};
$stmt = $conn->prepare("INSERT INTO dokter (user_id, spesialis) VALUES (? , ?)");
$stmt->bind_param("ii", $id, $spesialis);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$nopegawai = $stmt->insert_id;
	if (!$auth_id || $auth_id == null) {
			$pesan = array(
				"status"=>"berhasil",
				"pesan"=>"pasien baru berhasil ditambahkan, gunakan email untuk Login",
				"data"=> array("nomor medis"=>$nopegawai)
				);
		} else {
			$pesan = array(
				"status"=>"berhasil",
				"pesan"=>"pasien baru berhasil ditambahkan",
				"data"=> array("ID_Login"=>$auth_id, "nomor_pegawai"=>$nopegawai)
			);
		};
		echo json_encode($pesan);
		exit();
};
?>