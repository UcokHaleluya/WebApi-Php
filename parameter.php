<?php
$data = $_POST;
if(count($data) == 0) {
	$data = (object)json_decode(rtrim(trim(file_get_contents("php://input")),':'));
}
$data = is_array($data) ? (object)$data : $data;
$param = new stdClass();
foreach ($fields as $key => $value) {
	if ($value == true) {
		if (empty($data->{$key})) {
			echo(json_encode(array('status' => false, 'pesan' => "$key harus diisi")));
			exit();
		}else{
			$param->{$key} =  mysqli_real_escape_string($conn, $data->{$key});
		}
	}else{
		$param->{$key} =  mysqli_real_escape_string($conn, $data->{$key});
	}
}
?>