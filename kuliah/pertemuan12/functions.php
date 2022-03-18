<?php
function koneksi()
{
  // koneksi DB dan pilih database
  return mysqli_connect('localhost', 'root', '', 'pw_043040023');
}

function query($query)
{
  $conn = koneksi();

  // Query isi tabel mahasiswa
  $result = mysqli_query($conn, $query);

  // jika hasilnya hanya 1 data
  if (mysqli_num_rows($result) == 1) {
    return mysqli_fetch_assoc($result);
  }

  // ubah data ke dalam array, 
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }
  return $rows;
}

function tambah($data)
{
  // menghubungkan database
  $conn = koneksi();

  // membuat variabel untuk membantu di perintah query
  $nama = htmlspecialchars($data['nama']);
  $nrp = htmlspecialchars($data['nrp']);
  $email = htmlspecialchars($data['email']);
  $jurusan = htmlspecialchars($data['jurusan']);
  $gambar = htmlspecialchars($data['gambar']);

  $query = "INSERT INTO 
          mahasiswa
          VALUES
          (null, '$nama', '$nrp', '$$email', '$jurusan', '$gambar');
          ";
  // untuk menjalankan perintah fungsi tambah() dengan perintah query dan bisa terhubung ke database
  mysqli_query($conn, $query);

  // untuk menunjukkan error nya dimana
  echo mysqli_error($conn);

  // jika fungsi tambah() berhasil, maka akan keluar nilai 1
  return mysqli_affected_rows($conn);
}

function hapus($id)
{
  $conn = Koneksi();
  mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id") or die(mysqli_error($conn));
  return mysqli_affected_rows($conn);
}

function ubah($data)
{
  $conn = Koneksi();

  // membuat variabel untuk membantu di perintah query
  $id = htmlspecialchars($data['id']);
  $nama = htmlspecialchars($data['nama']);
  $nrp = htmlspecialchars($data['nrp']);
  $email = htmlspecialchars($data['email']);
  $jurusan = htmlspecialchars($data['jurusan']);
  $gambar = htmlspecialchars($data['gambar']);

  $query = "UPDATE mahasiswa SET 
            nama = '$nama',
            nrp = '$nrp',
            email = '$email',
            jurusan = '$jurusan',
            gambar = '$gambar'
            
            WHERE id = '$id';
                    ";
  // untuk menjalankan perintah fungsi tambah() dengan perintah query dan bisa terhubung ke database
  mysqli_query($conn, $query);

  // untuk menunjukkan error nya dimana
  echo mysqli_error($conn);

  // jika fungsi tambah() berhasil, maka akan keluar nilai 1
  return mysqli_affected_rows($conn);
}

function cari($keyword)
{
  $conn = Koneksi();
  $query = ("SELECT * FROM mahasiswa 
  WHERE 
  nama LIKE '%$keyword%' OR
  nrp LIKE '%$keyword%'
  
  ");

  $result =  mysqli_query($conn, $query);
  // ubah data ke dalam array, 

  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }
  return $rows;
}

function login($data)
{
  $conn = Koneksi();
  $username = htmlspecialchars($data['username']);
  $password = htmlspecialchars($data['password']);

  // cek dulu username
  if ($user = query("SELECT * FROM user WHERE username = '$username'  ")) {

    // cek password
    if (password_verify($password, $user['password'])) {
      // set session
      $_SESSION['login'] =  true;


      header('Location: index.php');
      exit;
    }
  }
  return [
    'error' => true,
    'pesan' => 'Username atau password salah input!!'
  ];
}


// jika password dan username kosong
function registrasi($data)
{
  $conn = Koneksi();

  $username = htmlspecialchars(strtolower($data['username']));
  $password1 = mysqli_real_escape_string($conn, $data['password1']);
  $password2 = mysqli_real_escape_string($conn, $data['password2']);

  if (empty($username) || empty($password1) || empty($password2)) {
    echo "<script>
    alert ('username atau password tidak boleh kosong');
    document.location.href = 'registrasi.php'
    </script>";
    return false;
  }

  // jika username sudah ada
  if (query("SELECT * FROM user WHERE username = '$username'")) {
    echo "<script>
    alert ('username sudah terdaftar');
    document.location.href = 'registrasi.php'
    </script>";
    return false;
  }

  if ($password1 !== $password2) {
    echo "<script>
    alert ('konfirmasi password tidak sesuai');
    document.location.href = 'registrasi.php'
    </script>";
    return false;
  }

  // jika password lebih kecil dari 5 digit
  if (strlen($password1 < 5)) {
    echo "<script>
    alert ('konfirmasi password tidak sesuai');
    document.location.href = 'registrasi.php'
    </script>";
    return false;
  }

  // jika username dan password sudah sesuai
  // enkripsi password
  $password_baru = password_hash($password1, PASSWORD_DEFAULT);

  //  insert ke tabel user
  $query = "INSERT INTO user
  VALUES
  (null, '$username', '$password_baru')
  ";

  mysqli_query($conn, $query) or die(mysqli_error($conn));
  return mysqli_affected_rows($conn);
}
