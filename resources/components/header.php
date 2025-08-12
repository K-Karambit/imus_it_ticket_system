<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">



  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="plugins/dataTables/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/dataTables/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/dataTables/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="public/assets/css/adminlte.css?v=3.2.0">
  <script src="plugins/jquery/jquery.min.js"></script>
  <link rel="stylesheet" href="plugins/toastr/toastr.css">
  <script src="plugins/toastr/toastr.min.js"></script>
  <script src="plugins/vuejs/vue.min.js"></script>

  <link rel="shortcut icon" href="<?= $helper->storage_url('it_logo.png') ?>" type="image/x-icon">

  <title>CITRMU - SYSTEM</title>


  <!-- Include Select2 CSS -->
  <link href="plugins/select2/select2.min.css" rel="stylesheet" />

  <script src="plugins/sweetalert/sweetalert2@11.js"></script>

  <script src="plugins/select2/select2.full.min.js"></script>

  <!-- Include Select2 JS -->
  <script src="plugins/select2/select2.min.js"></script>





  <!-- Include Chosen CSS -->
  <link rel="stylesheet" href="plugins/chosen/chosen.min.css">

  <!-- Include Chosen JS -->
  <script src="plugins/chosen/chosen.jquery.min.js"></script>

  <link rel="stylesheet" href="plugins/pacejs/pace-theme-default.min.css">
  <script src="plugins/pacejs/pace.min.js"></script>


  <script src="plugins/axios/axios.min.js"></script>

  <script src="https://unpkg.com/swapy/dist/swapy.min.js"></script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@500;700&display=swap');


    * {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 15px;
      /* Default font for body text */
    }


    /* Override toastr message text size */
    .toast-message {
      font-size: 15px;
      /* Change this value to adjust the size */
    }

    .content-wrapper {
      overflow: hidden;
    }

    .modal-fullscreen {
      width: 100vw;
      height: 100vh;
      margin: 0;
      max-width: none;
    }

    .modal-fullscreen .modal-content {
      height: 100%;
      border-radius: 0;
    }

    .modal-body {
      overflow-y: auto;
    }

    .pace .pace-progress {

      height: 3px !important;
    }

    /* .main-header {
      background-color: #602588ff;
    }

    .nav-link {
      color: #fff;
    } */
  </style>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper" id="app">


    <div id="nav-sidebar">
      <?php include __DIR__ . '/../../resources/components/navbar.php' ?>
      <?php include __DIR__ . '/../../resources/components/sidebar.php' ?>
    </div>