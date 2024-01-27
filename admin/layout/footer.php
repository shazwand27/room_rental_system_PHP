     <!-- /.content-wrapper -->
     <footer class="main-footer">
         <strong>Copyright &copy; 2014-2021 <a href="<?= base_url() ?>">Rent Rental
                 System</a>.</strong>
         All rights reserved.
         <div class="float-right d-none d-sm-inline-block">
             <b>Version</b> 1.0.0
         </div>
     </footer>

     <!-- Control Sidebar -->
     <aside class="control-sidebar control-sidebar-dark">
         <!-- Control sidebar content goes here -->
     </aside>
     <!-- /.control-sidebar -->
     </div>
     <!-- ./wrapper -->

     <!-- jQuery -->
     <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
     <!-- jQuery UI 1.11.4 -->
     <script src="<?= base_url('assets/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
     <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
     <script>
         $.widget.bridge('uibutton', $.ui.button)
     </script>
     <!-- Bootstrap 4 -->
     <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
     <!-- ChartJS -->
     <script src="<?= base_url('assets/plugins/chart.js/Chart.min.js') ?>"></script>
     <!-- Sparkline -->
     <script src="<?= base_url('assets/plugins/sparklines/sparkline.js') ?>"></script>
     <!-- JQVMap -->
     <script src="<?= base_url('assets/plugins/jqvmap/jquery.vmap.min.js') ?>"></script>
     <script src="<?= base_url('assets/plugins/jqvmap/maps/jquery.vmap.usa.js') ?>"></script>
     <!-- jQuery Knob Chart -->
     <script src="<?= base_url('assets/plugins/jquery-knob/jquery.knob.min.js') ?>"></script>
     <!-- daterangepicker -->
     <script src="<?= base_url('assets/plugins/moment/moment.min.js') ?>"></script>
     <script src="<?= base_url('assets/plugins/daterangepicker/daterangepicker.js') ?>"></script>
     <!-- Tempusdominus Bootstrap 4 -->
     <script src="<?= base_url('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
     <!-- Summernote -->
     <script src="<?= base_url('assets/plugins/summernote/summernote-bs4.min.js') ?>"></script>
     <!-- overlayScrollbars -->
     <script src="<?= base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>
     <!-- DataTables  & Plugins -->
     <script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
     <script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
     <script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
     <script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
     <script src="<?= base_url('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
     <script src="<?= base_url('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>"></script>
     <script src="<?= base_url('assets/plugins/jszip/jszip.min.js') ?>"></script>
     <script src="<?= base_url('assets/plugins/pdfmake/pdfmake.min.js') ?>"></script>
     <script src="<?= base_url('assets/plugins/pdfmake/vfs_fonts.js') ?>"></script>
     <script src="<?= base_url('assets/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
     <script src="<?= base_url('assets/plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>
     <script src="<?= base_url('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>
     <!-- SweetAlert2 -->
     <script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>

     <!-- select2 -->
     <script src="<?= base_url('assets/plugins/select2/js/select2.full.min.js') ?>"></script>


     <!-- AdminLTE App -->
     <script src="<?= base_url('assets/dist/js/adminlte.js') ?>"></script>
     <script>
         function logout() {
             Swal.fire({
                 title: 'Are you sure?',
                 text: "You will be logged out!",
                 icon: 'warning',
                 showCancelButton: true,
                 confirmButtonColor: '#d33',
                 cancelButtonColor: '#3085d6',
                 confirmButtonText: 'Logout',
                 cancelButtonText: 'Cancel'
             }).then((result) => {
                 if (result.isConfirmed) {
                     window.location.href = "<?= base_url('logout.php') ?>";
                 }
             })
         }
         $('input, select, textarea').on('change', function() {
             $(this).removeClass('is-invalid');
         });
         //  phone
         $('#phone').on('keypress', function(e) {
             // number only
             var charCode = (e.which) ? e.which : e.keyCode;
             if (charCode != 46 && charCode > 31 &&
                 (charCode < 48 || charCode > 57)) {
                 return false;
             }
             return true;
         });

         $('#username').on('keypress', function(e) {
             // no whitespace and special character
             var regex = new RegExp("^[a-zA-Z0-9]+$");
             var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
             if (regex.test(str)) {
                 return true;
             }

             e.preventDefault();

             return false;
         });
     </script>
     </body>

     </html>