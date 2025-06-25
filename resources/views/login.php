<!DOCTYPE html>
<html lang="en">

<head>
  <title>Login</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--===============================================================================================-->
  <link rel="icon" type="image/png" href="images/icons/favicon.ico" />
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?= $helper->public_url() ?>/login/vendor/bootstrap/css/bootstrap.min.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?= $helper->public_url() ?>/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?= $helper->public_url() ?>/login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?= $helper->public_url() ?>/login/vendor/animate/animate.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?= $helper->public_url() ?>/login/vendor/css-hamburgers/hamburgers.min.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?= $helper->public_url() ?>/login/vendor/animsition/css/animsition.min.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?= $helper->public_url() ?>/login/vendor/select2/select2.min.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?= $helper->public_url() ?>/login/vendor/daterangepicker/daterangepicker.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?= $helper->public_url() ?>/login/css/util.css">
  <link rel="stylesheet" type="text/css" href="<?= $helper->public_url() ?>/login/css/main.css">
  <!--===============================================================================================-->

  <script src="<?= $helper->public_url() ?>/login/vendor/jquery/jquery-3.2.1.min.js"></script>
  <link rel="stylesheet" href="plugins/toastr/toastr.css">
  <script src="plugins/toastr/toastr.min.js"></script>
</head>

<body>

  <div class="limiter">
    <div class="container-login100" style="background-image: url('<?= $api ?>/storage/it_logo.png');">
      <div class="wrap-login100 p-t-30 p-b-50">
        <span class="login100-form-title p-b-41">
          CITRMU TICKET SYSTEM LOGIN
        </span>

        <form class="login100-form validate-form p-b-33 p-t-5" id="loginForm">
          <div class="wrap-input100 validate-input" data-validate="Enter username">
            <input class="input100" type="text" name="username" placeholder="User name">
            <span class="focus-input100" data-placeholder="&#xe82a;"></span>
          </div>

          <div class="wrap-input100 validate-input" data-validate="Enter password">
            <input class="input100" type="password" name="password" placeholder="Password">
            <span class="focus-input100" data-placeholder="&#xe80f;"></span>
          </div>

          <div class="container-login100-form-btn m-t-32">
            <button type="submit" class="login100-form-btn">
              Login
            </button>
          </div>
        </form>

        <div class="text-center p-t-50" hidden>
          <span class="txt1">
            Or sign in with
          </span>
        </div>

        <div class="container-login100-form-btn m-t-15">
          <a href="api/microsoft_login.php" class="login100-form-btn" style="background-color: #fff; display: flex; align-items: center; justify-content: center; color:#000;">
            <img src="https://logolook.net/wp-content/uploads/2023/09/Microsoft-Azure-Logo.png" alt="Microsoft Logo" style="height: 24px; margin-right: 10px;">
            Sign In with Microsoft
          </a>
        </div>

      </div>
    </div>
  </div>


  <script>
    const params = new URLSearchParams(window.location.search);
    const route = params.get('route');
    const errorMessage = params.get('microsoftErrorMessage');

    if (errorMessage) {
      toastr.error(errorMessage);
    }

    const loginForm = document.getElementById('loginForm');
    loginForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const data = new FormData(this);
      submitLoginForm(data);
    });

    async function submitLoginForm(formData) {
      const response = await fetch('<?= $api ?>/login.php', {
        method: 'post',
        body: formData,
      });

      const data = await response.json();
      if (data.status === 'success') {

        if (route) {
          window.location.href = '?route=' + route;
        } else {
          window.location.href = '?route=/home';
        }


        return;
      }
      toastr.error(data.message);
    }
  </script>


  <div id="dropDownSelect1"></div>

  <!--===============================================================================================-->

  <!--===============================================================================================-->
  <script src="<?= $helper->public_url() ?>/login/vendor/animsition/js/animsition.min.js"></script>
  <!--===============================================================================================-->
  <script src="<?= $helper->public_url() ?>/login/vendor/bootstrap/js/popper.js"></script>
  <script src="<?= $helper->public_url() ?>/login/vendor/bootstrap/js/bootstrap.min.js"></script>
  <!--===============================================================================================-->
  <script src="<?= $helper->public_url() ?>/login/vendor/select2/select2.min.js"></script>
  <!--===============================================================================================-->
  <script src="<?= $helper->public_url() ?>/login/vendor/daterangepicker/moment.min.js"></script>
  <script src="<?= $helper->public_url() ?>/login/vendor/daterangepicker/daterangepicker.js"></script>
  <!--===============================================================================================-->
  <script src="<?= $helper->public_url() ?>/login/vendor/countdowntime/countdowntime.js"></script>
  <!--===============================================================================================-->
  <script src="<?= $helper->public_url() ?>/login/js/main.js"></script>

</body>

</html>