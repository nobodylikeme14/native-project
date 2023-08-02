<?php

class dataDiri
{
  //Deklarasi Variabel
  var $namaSiswa, $kelasSiswa, $tanggalLahirSiswa, $nomorTeleponSiswa, $alamatSiswa, $emailSiswa, $namaWebsite,
  $namaSekolah1, $namaSekolah2, $namaSekolah3, $deskripsiSekolah1, $deskripsiSekolah2, $deskripsiSekolah3, 
  $tahunSekolah1, $tahunSekolah2, $tahunSekolah3, $citaCita, $deskripsiPribadi1, $deskripsiPribadi2;

  //Function Nama Siswa
  function setNama ($nama){
    $this->namaSiswa = $nama;
  }
  function outputNama(){
    return $this->namaSiswa;
  }

  //Function Kelas Siswa
  function setKelas ($kelas){
    $this->kelasSiswa = $kelas;
  }
  function outputKelas(){
    return $this->kelasSiswa;
  }

  //Function Tanggal Lahir Siswa
  function setTanggalLahir ($tanggallahir){
    $this->tanggalLahirSiswa = $tanggallahir;
  }
  function outputTanggalLahir(){
    return $this->tanggalLahirSiswa;
  }

  //Function Nomor Telepon Siswa
  function setNomorTelepon ($nomortelepon){
    $this->nomorTeleponSiswa = $nomortelepon;
  }
  function outputNomorTelepon(){
    return $this->nomorTeleponSiswa;
  }

  //Function Alamat Siswa
  function setAlamatSiswa ($alamatsiswa){
    $this->alamatSiswa = $alamatsiswa;
  }
  function outputAlamatSiswa(){
    return $this->alamatSiswa;
  }

  //Function Email Siswa
  function setEmailSiswa ($emailsiswa){
    $this->emailSiswa = $emailsiswa;
  }
  function outputEmailSiswa(){
    return $this->emailSiswa;
  }

  //Function Nama Website Siswa
  function setNamaWebsite ($namawebsite){
    $this->namaWebsite = $namawebsite;
  }
  function outputNamaWebsite(){
    return $this->namaWebsite;
  }

  //Function Nama Sekolah SD Siswa
  function setNamaSekolah1 ($namasekolah1){
    $this->namaSekolah1 = $namasekolah1;
  }
  function outputNamaSekolah1(){
    return $this->namaSekolah1;
  }

  //Function Nama Sekolah SMP Siswa
  function setNamaSekolah2 ($namasekolah2){
    $this->namaSekolah2 = $namasekolah2;
  }
  function outputNamaSekolah2(){
    return $this->namaSekolah2;
  }

  //Function Nama Sekolah SMK Siswa
  function setNamaSekolah3 ($namasekolah3){
    $this->namaSekolah3 = $namasekolah3;
  }
  function outputNamaSekolah3(){
    return $this->namaSekolah3;
  }

  //Function Tahun Sekolah SD Siswa
  function setTahunSekolah1 ($tahunsekolah1){
    $this->tahunSekolah1 = $tahunsekolah1;
  }
  function outputTahunSekolah1(){
    return $this->tahunSekolah1;
  }

  //Function Tahun Sekolah SMP Siswa
  function setTahunSekolah2 ($tahunsekolah2){
    $this->tahunSekolah2 = $tahunsekolah2;
  }
  function outputTahunSekolah2(){
    return $this->tahunSekolah2;
  }

  //Function Tahun Sekolah SMK Siswa
  function setTahunSekolah3 ($tahunsekolah3){
    $this->tahunSekolah3 = $tahunsekolah3;
  }
  function outputTahunSekolah3(){
    return $this->tahunSekolah3;
  }

  //Function Cita Cita Siswa
  function setCitacita ($citacita){
    $this->citaCita = $citacita;
  }
  function outputCitacita(){
    return $this->citaCita;
  }

  //Function Deskripsi Pengenalan Diri Pribadi
  function setDeskripsiPribadi1 ($deskripsipribadi1){
    $this->deskripsiPribadi1 = $deskripsipribadi1;
  }
  function outputDeskripsiPribadi1(){
    return $this->deskripsiPribadi1;
  }

  //Function Deskripsi Hobi dan Kesukaan
  function setDeskripsiPribadi2 ($deskripsipribadi2){
    $this->deskripsiPribadi2 = $deskripsipribadi2;
  }
  function outputDeskripsiPribadi2(){
    return $this->deskripsiPribadi2;
  }
}

//Deklarasi Object
$datadiri = new dataDiri();
$datadiri->setNama('Faradilla Fairuz Alsha');
$datadiri->setKelas('XI RPL');
$datadiri->setTanggalLahir('04 April 2004');
$datadiri->setNomorTelepon('0823-6252-6958');
$datadiri->setAlamatSiswa('Jalan Garu VI Gang Cenderawasih');
$datadiri->setEmailSiswa('faradilla.alsha@gmail.com');
$datadiri->setNamaWebsite('www.websitepribadi.com');
$datadiri->setNamaSekolah1("SD Swasta As Shafi'iyah");
$datadiri->setNamaSekolah2('SMP Negeri 2 Medan');
$datadiri->setNamaSekolah3('SMK Swasta Multi Karya');
$datadiri->setTahunSekolah1('2016');
$datadiri->setTahunSekolah2('2019');
$datadiri->setTahunSekolah3('2022');
$datadiri->setCitacita(
    'Cita cita saya setelah lulus kuliah adalah menjadi seorang developer 
    game. Alasan saya ingin menjadi seorang developer game adalah karena 
    saya ingin berubah dari seorang pemain game menjadi seorang pembuat 
    game.'
);
$datadiri->setDeskripsiPribadi1(
    'Nama saya Faradilla Fairuz Alsha, saya lahir di kota Medan pada 
    tanggal 04 April 2004. Saya adalah anak pertama dari dua bersaudara, 
    anak dari pasangan Ihsan Mudzaffar Al Barqawi dan Farah Kharista Agatha. 
    Rara adalah nama panggilan akrabku. Ayah saya seorang Developer Properti.
    Sedangkan ibu saya adalah seorang Dosen di sebuah universitas negeri di 
    kota Medan. Sejak kecil, Ayah selalu menasehatiku agar rajin ibadah, 
    bersikap jujur dan baik terhadap sesama.'
);
$datadiri->setDeskripsiPribadi2(
    'Saya memiliki hobi yang berkaitan dengan teknologi dan juga tumbuh tumbuhan. 
    Terutama yang saya sukai adalah game. Saya sangat menyukai game. Bagi saya game 
    adalah segalanya. Setiap hari saya menghabiskan waktu-waktu luang saya dengan 
    bermain game online bersama teman teman saya. Tetapi biarpun saya banyak 
    menghabiskan waktu dengan bermain game, tentu saja saya tidak lupa dengan kewajiban 
    saya sebagai seorang murid untuk belajar.'
);

?>