<?php ini_set( 'display_errors',1); ini_set( 'display_startup_errors',1); error_reporting(-1); session_start(); ?>
<html>
<head>
    <title>
        My Awesome Stat site
    </title>
    <link href="assets/styles.css" rel="stylesheet">
    <link href="http://eternicode.github.io/bootstrap-datepicker/bootstrap-datepicker/css/datepicker.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    <script src="http://tablesorter.com/__jquery.tablesorter.min.js"></script>
    <script src="http://eternicode.github.io/bootstrap-datepicker/google-code-prettify/prettify.min.js"></script>
    <script src="http://eternicode.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi">
    </script>
    <script type="text/javascript" src="assets/scripts.js">
    </script>
</head>
<div id="loading">
    <img src="http://i.imgur.com/MPw8nvR.gif" />
</div>
<body>
    <?php include_once( "analytics.php");?>
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Call Log Analytics</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">How to...</a>
                </li>
                <li><a href="#">About Us</a>
                </li>
            </ul>
            <div class="navbar-form navbar-left">
                <div id="sandbox-container">
                    <div class="input-daterange" id="datepicker">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Start Date" id="start" name="start">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="End Date" id="end" name="end" autocomplete="off">
                        </div>
                        <button class="btn btn-default" onclick="Plot()">Call Log</button>
                        <button class="btn btn-default" onclick="Notif()">Notifications</button>
                    </div>
                </div>

            </div>
            <ul class="nav navbar-nav navbar-right" style="
    padding-right: 20;
">

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php echo $_SESSION[ 'email']; ?><b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="./?change">Change Calendar</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="./?logout">Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>




    <div class="container">
        <!--Div that will hold the pie chart-->
        <div id="chart_div1">
        </div>
        <div id="chart_div2">
        </div>
        <table id="myTable" class="table tablesorter"></table>


        <div class="singlenotif">
        <ul class="notiful">
          <li><table><tbody><tr><th rowspan="2"><img src="https://lh4.ggpht.com/a5tbkHBpZ2TGJQC-Rco4BTJOOR5LTgzB6YbLrxswlQsLi6Y-voGksMLIUybaCIaaFgA=w300-rw" width="40" height="40"></th><th><h4>BetaTurtle</h4></th></tr><tr><td><h6>undefined</h6></td></tr><tr><td colspan="2"><h6>com.google.android.talk 2014-07-17T10:15:06+05:30</h6></td></tr></tbody></table></li>
        </ul>

            
        </div>
    </div>
</body>

</html>