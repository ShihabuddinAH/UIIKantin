<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/PENJUAL/signup.css">
    <title>Pendaftaran Data Usaha</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pendaftaran Kantin</h1>
            <p>Lengkapi Data & Dokumen yang Diperlukan Untuk Syarat Pendaftaran Kantin</p>
        </div>
        <div class="main">
            <div class="left-column">
                <form action="check.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text"  name="username" placeholder="Masukkan Username" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" name="email" placeholder="Masukkan Email" recuired>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Masukkan password" recuired>
                    </div>
                    <div class="form-group">
                        <label>Nomor Handphone</label>
                        <input type="tel" name="kontak" placeholder="Masukkan Nomor Handphone" recuired>
                    </div>
                    <div class="form-group">
                        <label>Nama Kantin</label>
                        <input type="text" name="Nama_Warung" placeholder="Masukkan Nama Kantin" recuired>
                    </div>
                    <div class="form-group">
                        <label>Foto Kantin</label>
                        <input type="file" name="Gambar_Warung" recuired>
                    </div>
                    <button type="submit">Submit</button>
                </form>
            </div>
            <div class="right-column">
            </div>
        </div>
    </div>
</body>
</html>
