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
