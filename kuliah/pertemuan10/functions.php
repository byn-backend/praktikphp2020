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
