<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>MAGANG PDAM</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="Free HTML Templates" name="keywords">
  <meta content="Free HTML Templates" name="description">
  
  <!-- Favicon -->
  <link href="/assets/landing/img/favicon.ico" rel="icon">
  
  <!-- Google Web Fonts -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap"
        rel="stylesheet">
  
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  
  <!-- Libraries Stylesheet -->
  <link href="/assets/landing/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  
  <!-- Customized Bootstrap Stylesheet -->
  <link href="/assets/landing/css/style.css" rel="stylesheet">
</head>

<body>
<!-- Topbar Start -->
<div class="container-fluid bg-dark">
  <div class="row py-2 px-lg-5">
    <div class="col-lg-6 text-center text-lg-left mb-2 mb-lg-0">
      <div class="d-inline-flex align-items-center text-white">
        <small><i class="fa fa-phone-alt mr-2"></i>+62 711 355 222</small>
        <small class="px-3">|</small>
        <small><i class="fa fa-envelope mr-2"></i>ccperumdatirtamusipalembang@gmail.com</small>
      </div>
    </div>
    <div class="col-lg-6 text-center text-lg-right">
      <div class="d-inline-flex align-items-center">
        <a class="text-white px-2" href="https://www.facebook.com/perumdatirtamusi">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a class="text-white px-2" href="https://x.com/TirtaMusi">
          <i class="fab fa-twitter"></i>
        </a>
        <a class="text-white px-2" href="https://www.instagram.com/perumdatirtamusi/">
          <i class="fab fa-instagram"></i>
        </a>
      </div>
    </div>
  </div>
</div>
<!-- Topbar End -->


<!-- Navbar Start -->
<div class="container-fluid p-0">
  <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0 px-lg-5">
    <a href="#home" class="navbar-brand ml-lg-3">
      <h1 class="m-0 text-uppercase text-primary"><i class="fa fa-book-reader mr-3"></i>MAGANG PDAM</h1>
    </a>
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-between px-lg-3" id="navbarCollapse">
      <div class="navbar-nav mx-auto py-0">
        <a href="#home" class="nav-item nav-link active">Home</a>
        <a href="#about" class="nav-item nav-link">About</a>
        <a href="#contact" class="nav-item nav-link">Contact</a>
      </div>
      <a href="<?= base_url('/login') ?>" class="btn btn-primary py-2 px-4 d-none d-lg-block">Log In</a>
      <a href="<?= base_url('/register') ?>" class="btn btn-primary py-2 px-4 ml-2 d-none d-lg-block">Register</a>
    </div>
  </nav>
</div>
<!-- Navbar End -->


<!-- Header Start -->
<div id="home" class="jumbotron jumbotron-fluid position-relative overlay-bottom" style="margin-bottom: 90px;">
  <div class="container text-center my-5 py-5">
    <h1 class="text-white mt-4 mb-4">MAGANG PDAM</h1>
    <h1 class="text-white display-1 mb-5">Awali Mimpimu di Sini</h1>
    <h1 class="text-white"><a href="<?= base_url('/register') ?>" class="text-white">Register</a> untuk memulai karir mu!</h1>
  </div>
</div>
<!-- Header End -->


<!-- About Start -->
<div id="about" class="container-fluid py-5">
  <div class="container py-5">
    <div class="row">
      <div class="col-lg-5 mb-5 mb-lg-0" style="min-height: 500px;">
        <div class="position-relative h-100 d-flex align-items-center justify-content-center">
          <img class="position-absolute w-full h-50" src="/assets/landing/img/logo.png" style="object-fit: cover;">
        </div>
      </div>
      <div class="col-lg-7">
        <div class="section-title position-relative mb-4">
          <h6 class="d-inline-block position-relative text-secondary text-uppercase pb-2">About Us</h6>
          <h1 class="display-4">Tentang PDAM Tirta Musi Palembang</h1>
        </div>
        <p>
          PDAM Tirta Musi Palembang merupakan Perusahaan Daerah Air Minum yang bertugas menyediakan layanan air bersih
          bagi masyarakat Kota Palembang, Sumatera Selatan. Didirikan pertama kali pada tahun 1929 oleh pemerintah
          kolonial Belanda dengan nama Palembang Water Leading, perusahaan ini telah mengalami berbagai transformasi
          hingga menjadi PDAM seperti sekarang.
        </p>
        <p>
          Seiring waktu, PDAM Tirta Musi telah berkembang dengan menambah unit pelayanan di berbagai wilayah kota untuk
          menjangkau lebih banyak pelanggan dan meningkatkan kualitas layanan.
        </p>
        <h2>Visi</h2>
        <p>
          Menjadi Perusahaan Smart Happy yang unggul dalam penyediaan air minum di Indonesia dan pengelola air limbah di
          Indonesia pada tahun 2028.
        </p>
        <h2>Misi</h2>
        <ol>
          <li>Menjadi penyedia air minum yang andal dengan prinsip 4K (Kualitas, Kuantitas, Kontinuitas, Keterjangkauan)
            dan Good Corporate Governance.
          </li>
          <li>Mengintegrasikan teknologi digital untuk transformasi dan efisiensi dalam semua aspek operasional.</li>
          <li>Mengutamakan kepuasan pelanggan serta kesejahteraan karyawan dengan pelayanan yang inovatif dan
            bertanggung jawab.
          </li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!-- About End -->


<!-- Feature Start -->
<div class="container-fluid bg-image" style="margin: 90px 0;">
  <div class="container">
    <div class="row">
      <div class="col-lg-7 my-5 pt-5 pb-lg-5">
        <div class="section-title position-relative mb-4">
          <h6 class="d-inline-block position-relative text-secondary text-uppercase pb-2">Why Choose Us?</h6>
          <h1 class="display-4">Kenapa Magang di PDAM Tirta Musi?</h1>
        </div>
        <p class="mb-4 pb-2">
          Kami percaya bahwa pengalaman magang yang bermakna dapat menjadi langkah awal menuju karier yang sukses.
          Berikut alasan mengapa kamu harus memilih PDAM Tirta Musi Palembang sebagai tempat magangmu:
        </p>
        <div class="d-flex mb-3">
          <div class="btn-icon bg-primary mr-4">
            <i class="fa fa-2x fa-briefcase text-white"></i>
          </div>
          <div class="mt-n1">
            <h4>Lingkungan Profesional</h4>
            <p>
              Didampingi oleh tenaga profesional dari sektor layanan publik air bersih, peserta magang akan merasakan
              pengalaman kerja nyata di bidang teknik, pelayanan pelanggan, dan administrasi.
            </p>
          </div>
        </div>
        <div class="d-flex mb-3">
          <div class="btn-icon bg-secondary mr-4">
            <i class="fa fa-2x fa-certificate text-white"></i>
          </div>
          <div class="mt-n1">
            <h4>Sertifikat Resmi</h4>
            <p>
              Setiap peserta magang yang menyelesaikan program sesuai ketentuan akan memperoleh Sertifikat Pengalaman
              Magang resmi dari PDAM Tirta Musi, yang dapat memperkuat portofolio profesionalmu.
            </p>
          </div>
        </div>
        <div class="d-flex">
          <div class="btn-icon bg-warning mr-4">
            <i class="fa fa-2x fa-book-reader text-white"></i>
          </div>
          <div class="mt-n1">
            <h4>Jadwal Fleksibel & Terstruktur</h4>
            <p class="m-0">
              Magang dilaksanakan berdasarkan proposal yang disetujui dengan penjadwalan kegiatan sesuai divisi,
              memastikan setiap peserta mendapatkan pengalaman yang relevan dan terarah.
            </p>
          </div>
        </div>
      </div>
      <div class="col-lg-5" style="min-height: 500px;">
        <div class="position-relative h-100">
          <img class="position-absolute w-100 h-100" src="/assets/landing/img/feature.jpg" style="object-fit: cover;">
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Feature Start -->


<!-- Contact Start -->
<div id="contact" class="container-fluid py-5">
  <div class="container py-5">
    <div class="row align-items-center">
      <div class="col-lg-6 mb-5 mb-lg-0">
        <div class="bg-light d-flex flex-column justify-content-center px-5" style="height: 450px;">
          <div class="d-flex align-items-center mb-5">
            <div class="btn-icon bg-primary mr-4">
              <i class="fa fa-2x fa-map-marker-alt text-white"></i>
            </div>
            <div class="mt-n1">
              <h4>Alamat Kami</h4>
              <p class="m-0">Jl. Rambutan Ujung, No. 1
                Palembang , Sumatera Selatan , 30135 , Indonesia </p>
            </div>
          </div>
          <div class="d-flex align-items-center mb-5">
            <div class="btn-icon bg-secondary mr-4">
              <i class="fa fa-2x fa-phone-alt text-white"></i>
            </div>
            <div class="mt-n1">
              <h4>Hubungi Kami</h4>
              <p class="m-0">+62 711 355 222 </p>
            </div>
          </div>
          <div class="d-flex align-items-center">
            <div class="btn-icon bg-warning mr-4">
              <i class="fa fa-2x fa-envelope text-white"></i>
            </div>
            <div class="mt-n1">
              <h4>Email Kami</h4>
              <p class="m-0">ccperumdatirtamusipalembang@gmail.com</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="section-title position-relative mb-4">
          <h6 class="d-inline-block position-relative text-secondary text-uppercase pb-2">Butuh Bantuan?</h6>
          <h1 class="display-4">Kirim Pesan ke Kami</h1>
        </div>
        <div class="contact-form">
          <a href="mailto:ccperumdatirtamusipalembang@gmail.com" target="_blank">
            <button class="btn btn-primary py-3 px-5">Kirim Pesan</button>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Contact End -->


<!-- Footer Start -->
<div class="container-fluid position-relative overlay-top bg-dark text-white-50 py-5" style="margin-top: 90px;">
  <div class="container mt-5 pt-5">
    <div class="row">
      <div class="col-md-12 mb-5">
        <a href="<?= base_url('/') ?>" class="navbar-brand">
          <h1 class="mt-n2 text-uppercase text-white"><i class="fa fa-book-reader mr-3"></i>MAGANG PDAM</h1>
        </a>
        <p class="m-0">
          Tempat mengawali karir mu.
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 mb-5">
        <h3 class="text-white mb-4">Get In Touch</h3>
        <p><i class="fa fa-map-marker-alt mr-2"></i>Jl. Rambutan Ujung, No. 1
          Palembang , Sumatera Selatan , 30135 , Indonesia </p>
        <p><i class="fa fa-phone-alt mr-2"></i>+62 711 355 222 </p>
        <p><i class="fa fa-envelope mr-2"></i>ccperumdatirtamusipalembang@gmail.com</p>
        <div class="d-flex justify-content-start mt-4">
          <a class="text-white mr-4" href="https://x.com/TirtaMusi"><i class="fab fa-2x fa-twitter"></i></a>
          <a class="text-white mr-4" href="https://www.facebook.com/perumdatirtamusi"><i
              class="fab fa-2x fa-facebook-f"></i></a>
          <a class="text-white" href="https://www.instagram.com/perumdatirtamusi/"><i
              class="fab fa-2x fa-instagram"></i></a>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid bg-dark text-white-50 border-top py-4"
     style="border-color: rgba(256, 256, 256, .1) !important;">
  <div class="container">
    <div class="row">
      <div class="col-md-6 text-center text-md-left mb-3 mb-md-0">
        <p class="m-0">Copyright &copy; <a class="text-white" href="<?= base_url('/') ?>">MAGANG PDAM</a>. All Rights
          Reserved.
        </p>
      </div>
    </div>
  </div>
</div>
<!-- Footer End -->


<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary rounded-0 btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>


<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="/assets/landing/lib/easing/easing.min.js"></script>
<script src="/assets/landing/lib/waypoints/waypoints.min.js"></script>
<script src="/assets/landing/lib/counterup/counterup.min.js"></script>
<script src="/assets/landing/lib/owlcarousel/owl.carousel.min.js"></script>

<!-- Template Javascript -->
<script src="js/main.js"></script>
</body>

</html>