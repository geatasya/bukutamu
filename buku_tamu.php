<?php 
session_start();

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "GeatasyaMySQL29.";
$dbname = "buku_tamu";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM tb_tamu ORDER BY created_at DESC";
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
    
    $stmt = $conn->prepare("INSERT INTO tb_tamu (nama, email, pesan) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama, $email, $pesan);
    $stmt->execute();
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
            font-family: 'Poppins', sans-serif;
            background-color: #121212;
            color: #e0e0e0;
            margin: 0;
            padding: 20px;
        }

        .main-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .form-container {
            background: #1e1e1e;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
        }

        .tamu-container {
            background: #1e1e1e;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        h2 {
            color: #bb86fc;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }

        h3 {
            color: #ffffff; 
            font-weight: 500;
            margin-bottom: 20px;
            text-align: center;
            font-size: 18px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #333;
            border-radius: 8px;
            background: #2d2d2d;
            font-size: 14px;
            color: #e0e0e0;
            box-sizing: border-box;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        button, .logout-btn {
            padding: 12px;
            background: #bb86fc;
            border: none;
            color: #121212;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            text-align: center;
            text-decoration: none;
            flex: 1;
        }

        .logout-btn {
            background: #ff5555;
            display: block;
            text-align: center;
            padding: 12px;
        }

        button:hover {
            background: #9d65d0;
            color: #fff;
        }

        .logout-btn:hover {
            background: #ff3333;
            color: #fff;
        }

        .tamu-item {
            background: #252525;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #bb86fc;
        }

        .tamu-item strong {
            color: #bb86fc;
            font-size: 16px;
        }

        .tamu-item em {
            display: block;
            margin-top: 8px;
            font-size: 14px;
            font-style: normal;
            color: #b0b0b0;
            line-height: 1.5;
        }
    </style>
</head>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    $('#formTamu').on('submit', function(e) {
      e.preventDefault();

      $.ajax({
        url: 'proses_tamu.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
          $('#tamuContainer').prepend(response);
          $('#formTamu')[0].reset();
        }
      });
    });
  });
</script>

<body>
    <div class="main-container">
        <div class="form-container">
            <h2>Selamat Datang, <?php echo $_SESSION['username']; ?>!</h2>
            <h3>Silahkan mengisi kolom dibawah</h3>
            <form id="formTamu">
                <div class="form-group">
                    <input type="text" name="nama" placeholder="Nama" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <textarea name="pesan" placeholder="Pesan" required></textarea>
                </div>
                <div class="button-group">
                    <button type="submit">Kirim</button>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </form>
        </div>

        <div class="tamu-container">
            <h3>Daftar Buku Tamu</h3>
            <div id="tamuContainer">
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
