<?php 
  require_once("auth.php"); 

  $url = "http://103.129.222.101:8080/api/ssl/getMySSL";

  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  $headers = array(
    "Authorization: Bearer ".$_SESSION["jwt"],
  );
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  //for debug only!
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

  $resp = curl_exec($curl);
  curl_close($curl);
  $res = json_decode($resp);
  // var_dump($res);

  if(isset($_POST['download'])){
    $url2 = "http://103.129.222.101:8080/api/ssl/downloadCert";

    $curl2 = curl_init($url2);
    curl_setopt($curl2, CURLOPT_URL, $url2);
    curl_setopt($curl2, CURLOPT_POST, true);
    curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($curl2, CURLOPT_TIMEOUT, 28800);

    $headers2 = array(
      "Authorization: Bearer ".$_SESSION["jwt"],
      "Content-Type: application/json",
    );
    curl_setopt($curl2, CURLOPT_HTTPHEADER, $headers2);

    $domain2 = filter_input(INPUT_POST, 'domain', FILTER_SANITIZE_STRING);
    
    // $destination = dirname(__FILE__)."/$domain2.zip";
    $file = fopen(dirname(__FILE__)."$domain2.zip", "w+");
    // curl_setopt($curl2, CURLOPT_FILE, $file);

    $data2 = <<<DATA
    {
        "domain":"$domain2"
    }
    DATA;

    curl_setopt($curl2, CURLOPT_POSTFIELDS, $data2);

    //for debug only!
    curl_setopt($curl2, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, false);

    $resp2 = curl_exec($curl2);
    // var_dump($resp2);
    if ($resp2 === false){
        $info = curl_getinfo($curl2);
        die('error occured during curl exec. Additioanl info: ' . var_export($info));
    }

    curl_close($curl2);

    header('Content-type: ' . 'application/octet-stream');
    header('Content-Disposition: ' . 'attachment; filename='.$domain2.'.zip');
    echo $resp2; 
  }

  if(isset($_POST['revoke'])){
    $url3 = "http://103.129.222.101:8080/api/ssl/revokeMySSL";

    $curl3 = curl_init($url3);
    curl_setopt($curl3, CURLOPT_URL, $url3);
    curl_setopt($curl3, CURLOPT_POST, true);
    curl_setopt($curl3, CURLOPT_RETURNTRANSFER, true);

    $headers3 = array(
      "Authorization: Bearer ".$_SESSION["jwt"],
      "Content-Type: application/json",
    );
    curl_setopt($curl3, CURLOPT_HTTPHEADER, $headers3);

    $domain3 = filter_input(INPUT_POST, 'domain', FILTER_SANITIZE_STRING);

    $data3 = <<<DATA
    {
        "domain":"$domain3"
    }
    DATA;

    curl_setopt($curl3, CURLOPT_POSTFIELDS, $data3);

    //for debug only!
    curl_setopt($curl3, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl3, CURLOPT_SSL_VERIFYPEER, false);

    $resp3 = curl_exec($curl3);
    curl_close($curl3);
    echo "<script>alert(\"Certificate has been revoked\");window.location=".$_SERVER['PHP_SELF'].";</script>";
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>My SSL List</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin - v2.2.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">NeedSSL</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $_SESSION["user"]?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $_SESSION["user"]?></h6>
              <span>My Account</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="#">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="register-domain.php">
          <i class="bi bi-journal-text"></i>
          <span>Register a domain</span>
        </a>
      </li><!-- End Forms Nav -->

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>My SSL</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item">Tables</li>
          <li class="breadcrumb-item active">Data</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">SSL Table</h5>
              <p>Here's a list of your registered SSL</p>

              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Domain</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Download</th>
                    <th scope="col">Revoke</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $index = 1; ?>
                  <?php foreach ($res as $item): ?>
                  <tr>
                    <th scope="row"><?php echo $index ?></th>
                    <td><?php echo $item->domain ?></td>
                    <td><?php echo $item->createdAt ?></td>
                    <td><form method="post" action="">
                      <input type="hidden" name="domain" value="<?php echo $item->domain?>" /> 
                      <button value="download" name="download" type="submit" class="btn btn-primary me-2">Download</button>
                    </form></td>
                    <td><form method="post" action="">
                      <input type="hidden" name="domain" value="<?php echo $item->domain?>" /> 
                      <button value="revoke" name="revoke" type="submit" class="btn btn-danger me-2">Revoke</button>
                    </form></td>
                    <?php $index++; ?>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.min.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script>
    select('body').classList.toggle('toggle-sidebar')
  </script>
</body>

</html>