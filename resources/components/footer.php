<script>
  function showLoading() {
    Swal.fire({
      title: 'Loading...',
      text: 'Please wait while we process your request.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });
  }

  function closeLoading() {
    Swal.close();
  }
</script>








<noscript>
  <div style='color: red; text-align: center;'>
    <h2>JavaScript is required to use this app!</h2>
  </div>
</noscript>




<footer class="main-footer">
  <div class="float-right d-none d-sm-block">
    <b>Version</b> 1.0
  </div>
  <strong>Copyright &copy; <?= date('Y') ?> CITRMU</strong> All rights reserved.
</footer>


</div>



<script src="public/assets/js/bootstrap.bundle.min.js"></script>
<script src="plugins/dataTables/jquery.dataTables.min.js"></script>
<script src="plugins/dataTables/dataTables.bootstrap4.min.js"></script>
<script src="plugins/dataTables/dataTables.responsive.min.js"></script>
<script src="plugins/dataTables/responsive.bootstrap4.min.js"></script>
<script src="plugins/dataTables/dataTables.buttons.min.js"></script>
<script src="plugins/dataTables/buttons.bootstrap4.min.js"></script>
<script src="plugins/dataTables/jszip.min.js"></script>
<script src="plugins/dataTables/pdfmake.min.js"></script>
<script src="plugins/dataTables/vfs_fonts.js"></script>
<script src="plugins/dataTables/buttons.html5.min.js"></script>
<script src="plugins/dataTables/buttons.print.min.js"></script>
<script src="plugins/dataTables/buttons.colVis.min.js"></script>
<script src="public/assets/js/adminlte.min.js?v=3.2.0"></script>
<script src="public/assets/js/scripts.js"></script>
<!-- <script src="public/assets/js/websocket.js"></script> -->
<script>
  function logout() {
    fetch('api/logout');
    window.location.href = './';
  }

  function checkSession() {
    fetch('api/session.php').then(res => res.json()).then(data => {
      if (!data.session) {
        toastr.error('Session expired.');
        setTimeout(() => {
          location.reload();
        }, 3000);
      }
    })
  }
  checkSession();
  setInterval(function() {
    checkSession();
  }, 5000);
</script>



<script>
  $(function() {
    $("#example1").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#dataTable').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>



<!-- <script>
  $(function() {
    var availableTags = ["Apple", "Banana", "Cherry", "Date", "Elderberry"];

    $("#autocomplete-select").autocomplete({
      source: availableTags
    });
  });
</script>
 -->




</body>

</html>