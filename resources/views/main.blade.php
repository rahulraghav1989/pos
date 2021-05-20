<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>Just1Click - MAKE SELLING EASIER</title>
        <meta content="Responsive admin theme build on top of Bootstrap 4" name="description" />
        <meta content="Themesdesign" name="author" />
        <link rel="shortcut icon" href="{{ asset('posview') }}/assets/images/favicon.ico">

        <!-- DataTables -->
        <link href="{{ asset('posview') }}/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('posview') }}/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="{{ asset('posview') }}/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <link href="{{ asset('posview') }}/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="{{ asset('posview') }}/assets/css/metismenu.min.css" rel="stylesheet" type="text/css">
        <link href="{{ asset('posview') }}/assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="{{ asset('posview') }}/assets/css/style.css" rel="stylesheet" type="text/css">

    </head>

    <body>
        @yield('content')
        <!-- jQuery  -->
        <!-- <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script> -->
        <script src="{{ asset('posview') }}/assets/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('posview') }}/assets/js/metismenu.min.js"></script>
        <script src="{{ asset('posview') }}/assets/js/jquery.slimscroll.js"></script>
        <script src="{{ asset('posview') }}/assets/js/waves.min.js"></script>

        <!-- Required datatable js -->
        <script src="{{ asset('posview') }}/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/datatables/dataTables.bootstrap4.min.js"></script>
        <!-- Buttons examples -->
        <script src="{{ asset('posview') }}/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/datatables/buttons.bootstrap4.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/datatables/jszip.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/datatables/pdfmake.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/datatables/vfs_fonts.js"></script>
        <script src="{{ asset('posview') }}/plugins/datatables/buttons.html5.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/datatables/buttons.print.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/datatables/buttons.colVis.min.js"></script>
        <!-- Responsive examples -->
        <script src="{{ asset('posview') }}/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/datatables/responsive.bootstrap4.min.js"></script>

        <!-- Datatable init js -->
        <script src="{{ asset('posview') }}/assets/pages/datatables.init.js"></script>

        <!-- App js -->
        <script src="{{ asset('posview') }}/assets/js/app.js"></script>
        
    </body>

</html>