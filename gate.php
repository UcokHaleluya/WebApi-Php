<?php
//koneksi ke server
require "koneksi.php";
//deklarasi parameter required atau opsional
$fields = array('email' => true);
require "parameter.php";
//////////////////////
$email = $data->email;
$dataLogin = array();
$stmt = $conn->prepare("SELECT role, email, auth_id FROM user WHERE email=? OR auth_id =?");
$stmt->bind_param("ss",$email, $email);
if (!$stmt->execute()) {
	$pesan = array("status" => "gagal",
		           "pesan" => "gagal memuat data"
				);
	echo json_encode($pesan);
	exit();
}
$res = $stmt->get_result();
$dataLogin = $res->fetch_array(MYSQLI_ASSOC);
if (!$dataLogin['role']) {
	$pesan = array("status" => "gagal", "pesan" => "Anda belum terdaftar");
};
if ($dataLogin['role'] == "P") {
	$pesan = array("status" => "berhasil", "pesan" => "login berhasil", "url" => "http://localhost:8000/", "data"=>$dataLogin);
} elseif ($dataLogin['role'] == "D") {
	$pesan = array("status" => "berhasil", "pesan" => "login berhasil", "url" => "http://localhost:8111/", "data"=>$dataLogin);
} elseif ($dataLogin['role'] == "M") {
	$pesan = array("status" => "berhasil", "pesan" => "login berhasil", "url" => "http://localhost:8008/", "data"=>$dataLogin);
} elseif ($dataLogin['role'] == "A") {
	$pesan = array("status" => "berhasil", "pesan" => "login berhasil", "url" => "http://localhost:8888/", "data"=>$dataLogin);
};
echo json_encode($pesan);

// if ($dataLogin->role == "P") {
// 	$pesan = array("status" => "gagal", "pesan" => "email atau nomor identitas sudah terdaftar");
// 	echo json_encode($pesan);
// 	exit();
// }