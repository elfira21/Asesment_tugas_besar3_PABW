<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE');
header('Content-Type: application/json; charset=utf8');
// Konfigurasi koneksi database
$host = 'localhost';  // Ganti dengan host MySQL Anda
$user = 'root';  // Ganti dengan username MySQL Anda
$password = '';  // Ganti dengan password MySQL Anda
$database = 'db_data_seniman';  // Ganti dengan nama database Anda
// Membuat koneksi ke database
$koneksi = mysqli_connect($host, $user, $password, $database);

// Memeriksa apakah koneksi berhasil atau tidak
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Mendapatkan data dari API
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Query untuk mendapatkan data dari database
    $query = "SELECT * FROM tb_data_seniman";  // Ganti dengan nama tabel Anda

    // Menjalankan query
    $result = mysqli_query($koneksi, $query);

    // Membuat array kosong untuk menyimpan data
    $data = array();

    // Mengambil data hasil query dan menyimpannya dalam array
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    // Mengembalikan data sebagai respon JSON
    echo json_encode($data);
}

// Menambahkan data melalui API
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan data yang dikirim melalui body request
    $nama_seniman = $_POST['nama_seniman'];
    $bidang_seni = $_POST['bidang_seni'];
    $umur = $_POST['umur'];
    $alamat = $_POST['alamat'];

    // Query untuk menambahkan data ke database
    $query = "INSERT INTO tb_data_seniman (nama_seniman, bidang_seni, umur, alamat) VALUES ('$nama_seniman', '$bidang_seni', '$umur', '$alamat')";  // Ganti dengan nama tabel Anda dan kolom yang sesuai

    // Menjalankan query
    $result = mysqli_query($koneksi, $query);

    // Memeriksa apakah data berhasil ditambahkan atau tidak
    if ($result) {
        echo json_encode(array('message' => 'Data berhasil ditambahkan'));
    } else {
        echo json_encode(array('message' => 'Gagal menambahkan data'));
    }
}
// Mengupdate data melalui API
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Mendapatkan data yang dikirim melalui body request
    parse_str(file_get_contents("php://input"), $putData);
    $id_seni = $putData['id_seni'];
    $nama_seniman = $putData['nama_seniman'];
    $bidang_seni = $putData['bidang_seni'];
    $umur = $putData['umur'];
    $alamat = $putData['alamat'];

    // Query untuk mengupdate data di database
    $query = "UPDATE tb_data_seniman SET nama_seniman ='$nama_seniman', bidang_seni='$bidang_seni', umur ='$umur', alamat ='$alamat' WHERE id_seni='$id_seni'";  // Ganti dengan nama tabel, kolom, dan kondisi yang sesuai

    // Menjalankan query
    $result = mysqli_query($koneksi, $query);

    // Memeriksa apakah data berhasil diupdate atau tidak
    if ($result) {
        echo json_encode(array('message' => 'Data berhasil diupdate'));
    } else {
        echo json_encode(array('message' => 'Gagal mengupdate data'));
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Mendapatkan data yang dikirim melalui body request
    parse_str(file_get_contents("php://input"), $deleteData);
    $id_seni = $deleteData['id_seni'];

    // Query untuk menghapus data dari database
    $query = "DELETE FROM tb_data_seniman WHERE id_seni='$id_seni'";  // Ganti dengan nama tabel dan kondisi yang sesuai

    // Menjalankan query
    $result = mysqli_query($koneksi, $query);

    // Memeriksa apakah data berhasil dihapus atau tidak
    if ($result) {
        echo json_encode(array('message' => 'Data berhasil dihapus'));
    } else {
        echo json_encode(array('message' => 'Gagal menghapus data'));
    }
}
?>







