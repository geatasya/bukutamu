<?php
session_start();

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "GeatasyaMySQL29.";
$dbname = "buku_tamu";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars(trim($_POST['nama']));
    $email = htmlspecialchars(trim($_POST['email']));
    $pesan = htmlspecialchars(trim($_POST['pesan']));

    // Gunakan prepared statement
    $stmt = $conn->prepare("INSERT INTO tb_tamu (nama, email, pesan) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama, $email, $pesan);

    if ($stmt->execute()) {
        echo "
        <div class='tamu-item'>
            <strong>" . htmlspecialchars($nama) . "</strong> (" . htmlspecialchars($email) . ")
            <em>" . nl2br(htmlspecialchars($pesan)) . "</em>
        </div>
        ";
    } else {
        echo "Gagal menyimpan data.";
    }

    $stmt->close();
}

$conn->close();
?>
