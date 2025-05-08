<?php 

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbname = "buku_tamu";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$data_tamu = [];
$query = "SELECT * FROM tb_tamu";
$data_tamu = $conn->query($query);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $pesan = htmlspecialchars($_POST['pesan']);

    $newEntry = [
        "nama" => $nama,
        "email" => $email,
        "pesan" => $pesan
    ];

    $_SESSION['buku_tamu'][] = $newEntry;
    $query = "INSERT INTO tb_tamu (nama, email, pesan) VALUES ('$nama','$email', '$pesan')";
    $fetch = $conn->query($query);
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu</title>
    <style>
        body {
            font-family: Arial, sans-serif; 
            text-align: center; 
            background-color: #f0f0f0;
        }

        .container {
            width: 50%; 
            margin: auto; 
            padding: 20px; 
            border: 1px solid #ccc; 
            border-radius: 10px; 
            background-color: #d9d9d9;
        }

        .sidebar {
            width: 35%;
            padding: 20px;
            background: #FFEBD2;
            border-radius: 10px;
            height: fit-content;
        }

        .content {
            width: 60%;
            background: #FFF8E7;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            height: 93%;
        }

        h2, h3 {
            font-weight: bold;
            font-size: 20px;
            text-align: center;
            color: #8B5E3C;
        }

        h3 {
            margin-top: 2px;
        }

        input, textarea {
            width: 94%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #8B5E3C;
            border-radius: 8px;
            background: #FFF8E7;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #8B5E3C;
            border: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #6F4E37;
        }

        .list-tamu {
            flex-grow: 1;
            background: #FFF8E7;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            max-height: 60vh;
            margin-top: 0px;
        }

        .tamu-item {
            background: #FFEBD2;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            width: 95%;
            display: inline-block;
            position: relative;
        }

        .tamu-item em {
            display: block;
            margin-top: 5px;
            font-size: 14px;
            font-style: normal;
            color: #5A3E2B;
        }

        a {
            display: block;
            padding: 10px 20px;
            background: #C06014;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
            font-weight: bold;
            text-align: center;
        }

        a:hover {
            background: #8B4513;
        }
    </style>
</head>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    $('#formTamu').on('submit', function(e) {
      e.preventDefault(); // Cegah reload

      $.ajax({
        url: 'proses_tamu.php', // file proses AJAX
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
          $('#tamuContainer').prepend(response); // Tambahkan entry baru di atas
          $('#formTamu')[0].reset(); // Kosongkan form
        }
      });
    });
  });
</script>

<body>
    <div class="container">
        <div class="sidebar">
            <h2>Selamat Datang, <?php echo $_SESSION['username']; ?>!</h2>
            <h3>Silahkan mengisi kolom dibawah</h3>
            <form id="formTamu">
                <input type="text" name="nama" placeholder="Nama" required><br>
                <input type="email" name="email" placeholder="Email" required><br>
                <textarea name="pesan" placeholder="Pesan" required></textarea><br>
                <button type="submit">Kirim</button>
            </form>
            <br>
            <a href="logout.php">Logout</a>
        </div>

        <div class="content">
            <h3>Daftar Buku Tamu</h3>
            <div class="list-tamu" id="tamuContainer">
                <?php foreach($data_tamu as $tamu) { ?>
                    <div class='tamu-item'>
                        <strong><?= $tamu['nama'] ?></strong> (<?= $tamu['email'] ?>) 
                        <em><?php echo $tamu['pesan'] ?></em>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>