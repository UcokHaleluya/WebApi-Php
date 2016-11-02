<?php
require 'koneksi.php';
$fields = array(
		"identitas" => true,
		"password" => true
	);
require 'parameter.php';
$identitas = $data->identitas;
$password = $data->password;
$output = array();
$waktu = date('Y-m-d H:i:s');
$stmt = $conn->prepare("SELECT * FROM user WHERE auth_id = ? OR email = ?");
$stmt->bind_param("ss", $identitas, $identitas);
if (!$stmt->execute()) {
	$pesan = array(
			"status"=>"gagal",
			"pesan"=>"terjadi kesalahan pada server"
		);
	echo json_encode($pesan);
	exit();
} else {
	$res = $stmt->get_result();
	$output = $res->fetch_array(MYSQLI_ASSOC);	
	if ($output['password'] == null) {		
			$stmt = $conn->prepare("UPDATE user set password = ? WHERE id = ?");
			$stmt->bind_param("si", $password, $id);
			if (!$stmt->execute()) {
				$pesan = array(
						"status"=>"gagal",
						"pesan"=>"terjadi kesalahan"
					);
				echo json_encode($pesan);
				exit();
			} else {
				$pesan = array(
				"status"=>"berhasil",
				"pesan"=>null,
				"data"=>array(
						"id"=>$output['id'],
						"email"=>$output['email'],
						"token"=>$output['api_key'],
						"foto"=>$output["foto"]
							)
					);
				echo json_encode($pesan);
				exit();
			};		
	} else {
		if ($output['password'] == $password) {
			$stmt = $conn->prepare("INSERT INTO user_log (user_id, status, date) VALUES (?, 'Login', ?)");
			$stmt->bind_param("is",$output['id'], $waktu);
			if (!$stmt->execute()) {
				$pesan = array("status"=>"gagal",
								"pesan"=>"terjadi kesalahan pada server."
					);
				echo json_encode($pesan);
				exit();
			};
			$pesan = array(
					"status"=>"berhasil",
					"pesan"=>null,
					"data"=>array(
							"id"=>$output['id'],
							"email"=>$output['email'],
							"token"=>$output['api_key'],
							"foto"=>$output["foto"]
						)
				);
			echo json_encode($pesan);
			exit();
		} else {
			$pesan = array(
					"status"=>"gagal",
					"pesan"=>"Anda salah memasukkan sandi"
				);
			echo json_encode($pesan);
			exit();
		};//endif
	};//endif	
};
?>