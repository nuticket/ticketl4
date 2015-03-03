<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>AdminLTE | Dashboard</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="//code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Morris chart -->
        <!-- <link href="css/morris/morris.css" rel="stylesheet" type="text/css" /> -->
        <!-- jvectormap -->
        <!-- <link href="css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" /> -->
        <!-- Date Picker -->
        <!-- <link href="css/datepicker/datepicker3.css" rel="stylesheet" type="text/css" /> -->
        <!-- Daterange picker -->
        <!-- <link href="css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" /> -->
        <!-- bootstrap wysihtml5 - text editor -->
        <!-- <link href="css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" /> -->
        <!-- Theme style -->
        <!-- <link href="{{ theme('assets/css/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css" /> -->
        <link href="{{ theme('assets/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ theme('assets/css/select2.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ theme('assets/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ theme('assets/css/daterangepicker-bs3.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ theme('assets/css/styles.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ theme('assets/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" type="text/css" />


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="skin-blue fixed">{{-- dd($main->roots()[1]) --}}
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="index.html" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                Support
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        
                        <!-- Notifications: style can be found in dropdown.less -->
                        
                        <!-- Tasks: style can be found in dropdown.less -->
                        
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span>{{ user('display_name') }} <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Profile</a></li>
                                <li class="divider"></li>
                                <li><a href="{{ route('session.end') }}">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                @include('common.sidebar', ['items' => $nav->roots()])
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                    @yield('content')

                </section>
            </aside>
        </div>


        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- // <script src="//code.jquery.com/ui/1.11.1/jquery-ui.min.js" type="text/javascript"></script> -->
        <!-- Morris.js charts -->
        <!-- // <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script> -->
        <!-- // <script src="js/plugins/morris/morris.min.js" type="text/javascript"></script> -->
        <!-- Sparkline -->
        <!-- // <script src="js/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script> -->
        <!-- jvectormap -->
        <!-- // <script src="js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script> -->
        <!-- // <script src="js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script> -->
        <!-- jQuery Knob Chart -->
        <!-- // <script src="js/plugins/jqueryKnob/jquery.knob.js" type="text/javascript"></script> -->
        <!-- daterangepicker -->
        <!-- // <script src="js/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script> -->
        <!-- datepicker -->
        <!-- // <script src="js/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script> -->
        <!-- Bootstrap WYSIHTML5 -->
        <!-- // <script src="js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script> -->
        <!-- iCheck -->
        <!-- // <script src="js/plugins/iCheck/icheck.min.js" type="text/javascript"></script> -->

        <!-- AdminLTE App -->
        
        <!-- // <script src="{{ theme('assets/js/jquery.dataTables.js') }}" type="text/javascript"></script> -->
        <script src="{{ theme('assets/js/select2.min.js') }}" type="text/javascript"></script>
        <script src="{{ theme('assets/js/moment.js') }}" type="text/javascript"></script>
        <script src="{{ theme('assets/js/daterangepicker.js') }}" type="text/javascript"></script>
        <script src="{{ theme('assets/js/bootstrap3-wysihtml5.all.min.js') }}" type="text/javascript"></script>
        <script src="{{ theme('assets/js/app.js') }}" type="text/javascript"></script>

        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="js/AdminLTE/dashboard.js" type="text/javascript"></script>

        <!-- AdminLTE for demo purposes -->
        <script src="js/AdminLTE/demo.js" type="text/javascript"></script>

                <script type="text/javascript">
            $(document).ready(function() {
              $("select.select2-default").select2({minimumResultsForSearch: 8});
              $(".status-select").select2({
                    multiple: true,
                    separator: '-',
                    data:[
                        {id:'closed',text:'Closed'},
                        {id:'open',text:'Open'},
                        {id:'new',text:'New'}
                    ]
              });
              $(".priority-select").select2({
                    multiple: true,
                    separator: '-',
                    data:[
                        {id:1,text:'1'},
                        {id:2,text:'2'},
                        {id:3,text:'3'},
                        {id:4,text:'4'},
                        {id:5,text:'5'}
                    ]
              });
              $(".dept-select").select2({
                    multiple: true,
                    separator: '-',
                    data: [@foreach($depts as $id => $name){id:{{ $id }},text:'{{ $name }}'},@endforeach ]
              });
                $(".assigned-select").select2({
                    multiple: true,
                    separator: '-',
                    data: [@foreach(App\Staff::all() as $row){id:{{ $row->id }},text:'{{ $row->user->display_name }}'},@endforeach]
              });
                $('#createtime').daterangepicker();
                $('.daterange').daterangepicker({
                    opens: 'left',
                    ranges: {
                     'Today': [moment(), moment()],
                     'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                     'This Week': [moment().startOf('week'), moment().endOf('week')],
                     'Last Week': [moment().subtract('weeks', 1).startOf('week'), moment().subtract('weeks', 1).endOf('week')],
                     'This Month': [moment().startOf('month'), moment().endOf('month')],
                     'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                  },
                });
                // $(".textarea").wysihtml5({
                //         "size": "sm" // options are xs, sm, lg
                // });
                $('#reply-date').daterangepicker({ singleDatePicker: true, timePickerIncrement: 15, format: 'MM/DD/YYYY h:mm a', timePicker: true, opens: 'right' });
            });
        </script>
    </body>
</html>
