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
  <link rel="stylesheet" href="public/assets/css/adminlte.min.css?v=3.2.0">
  <script src="plugins/jquery/jquery.min.js"></script>
  <link rel="stylesheet" href="plugins/toastr/toastr.css">
  <script src="plugins/toastr/toastr.min.js"></script>
  <script src="plugins/vuejs/vue.min.js"></script>

  <link rel="shortcut icon" href="<?= $helper->storage_url('it_logo.png') ?>" type="image/x-icon">

  <title>CITRMU</title>


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


  <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@500;700&display=swap');


    * {
      font-family: Arial, Helvetica, sans-serif;
      /* Default font for body text */
    }


    /* Override toastr message text size */
    .toast-message {
      font-size: 17px;
      /* Change this value to adjust the size */
    }

    .content-wrapper {
      overflow: hidden;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper" id="app">


    <div id="nav-sidebar">
      <?php include __DIR__ . '/../../resources/components/navbar.php' ?>
      <?php include __DIR__ . '/../../resources/components/sidebar.php' ?>
    </div>


    <style>
      [type='text'],
      .btn-primary,
      .btn-danger,
      .btn-info,
      .form-control,
      .table {
        border-radius: 0px;
      }
    </style>



    <!-- <script>
      $(document).ready(function() {
        $('select').chosen({
          width: '200px',
          no_results_text: "Oops, nothing found!"
        });
      });
    </script> -->














    <style>
      .pace .pace-progress {
     
        height: 3px !important;
        /* Adjust this value to your desired height */
      }
    </style>