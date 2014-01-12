<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
session_start();
?>
<html>
  <head>
    <!--Load the AJAX API-->
    <title>
      My Awesome Stat site
    </title>
    <style type="text/css">
      #loading{
      position: absolute;
    top: 50%;
    left: 50%;
    z-index: 9999;
    display: none;
      }
      body {
    padding-top: 60px;
  }
    </style>
    <link href="http://eternicode.github.io/bootstrap-datepicker/bootstrap-datepicker/css/datepicker.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
    </script>

    
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js">
    </script>
    
    <script src="http://eternicode.github.io/bootstrap-datepicker/google-code-prettify/prettify.min.js"></script>
    <script src="http://eternicode.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    
    <script type="text/javascript" src="https://www.google.com/jsapi">
    </script>
    <script type="text/javascript">
     $(document).ready(function () {
                
                $('#sandbox-container .input-daterange').datepicker({
    format: "yyyy-mm-dd"
});
            
            });
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']}
                 );
      
      // Set a callback to run when the Google Visualization API is loaded.
      //google.setOnLoadCallback(drawChart);
      
      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function fintime(sec){
        var delta = sec;
        var days = Math.floor(delta / 86400);
        var hours = Math.floor(delta / 3600) % 24;
        var minutes = Math.floor(delta / 60) % 60;
        var seconds = delta % 60;
        if(days==0)
          return hours+' hours, '+minutes+' minutes and '+seconds+' seconds';
        return days+' days, '+hours+' hours, '+minutes+' minutes and '+seconds+' seconds';
      }
      
      function count(total, arg){
        var count=0;
        for (var i = total.length - 1; i >= 0; i--) {
          //console.log(total[i].type.summary);
          if(arg=="outa"){
            if("outgoing "==total[i].type){
              if(total[i].time>0)
                count++;
            }
            
          }
          if(arg=="out"){
            if("outgoing "==total[i].type){
              if(total[i].time==0)
                count++;
            }
            
          }
          else if(arg==total[i].type)
            count++;
        }
        return count;
        
      }
      function gettotal(total, arg){
        var count=0;
        for (var i = total.length - 1; i >= 0; i--) {
          if(arg==total[i].type){
            count+=total[i].time;
          }
        }
        console.log(count);
        return count;
      }
      
      function drawChart() {
        
        //gettotal(totaldata, "outgoing ");
        /*
['incoming', count(totaldata, "incoming ")],
['outgoing-actuall', count(totaldata, "outa")],
['outgoing-missed', count(totaldata, "out")],
['missed', count(totaldata, "missed ")]
*/
        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['incoming', count(totaldata, "incoming ")],
          ['outgoing-actuall', count(totaldata, "outa")],
          ['outgoing-missed', count(totaldata, "out")],
          ['missed', count(totaldata, "missed ")]
        ]);
        
        // Set chart options
        var options = {'title':'My total Calls: '+totaldata.length,
                       'width':1200,
                       'height':300}
            ;
        
        
        
        t1=gettotal(totaldata, "incoming ");
        t2=gettotal(totaldata, "outgoing ");
        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Topping');
        data2.addColumn('number', 'Slices');
        data2.addRows([
          ['incoming '+fintime(t1), t1],
          ['outgoing '+fintime(t2), t2]
          
        ]);
        
        var options2 = {
          title: 'My Total Talk Times: '+fintime(t1+t2),
          is3D: true,
          'width':1200,
          'height':300
        }
            ;
        
        
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div1'));
        chart.draw(data, options);
        var chart2 = new google.visualization.PieChart(document.getElementById('chart_div2'));
        chart2.draw(data2, options2);
      }
      var totaldata;
      $("#loading").hide();
      function Plot(){
        var start = $('#start').val();
        var end = $('#end').val();
        url = "get.php?start="+start+"&end="+end;
        //alert(url);
        $("#loading").show();
    $.get(url, function( data ) {
      totaldata=JSON.parse(data);
      //console.log(totaldata);
          // obj = JSON.parse(totaldata);
          // alert(obj[0].id);
      google.setOnLoadCallback(drawChart);
      $("#loading").hide();
      drawChart();
    }
             );
      }
      
    </script>
  </head>
  <div id="loading">
    <img src="http://i.imgur.com/MPw8nvR.gif"/>
  </div>
  <body>
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
      <li class="active"><a href="#">How to...</a></li>
      <li><a href="#">About Us</a></li>
    </ul>
    <div class="navbar-form navbar-left">
      <div id="sandbox-container"><div class="input-daterange" id="datepicker">
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Start Date" id="start"name="start">
      </div>
      <div class="form-group">
        <input type="text" class="form-control" placeholder="End Date" id="end" name="end" autocomplete="off">
      </div>
      <button class="btn btn-default" onclick="Plot()">Plot</button>
      </div></div>
      
    </div>
    <ul class="nav navbar-nav navbar-right" style="
    padding-right: 20;
">
      
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['email']; ?><b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="./?change">Change Calendar</a></li>
          <li class="divider"></li>
          <li><a href="./?logout">Logout</a></li>
        </ul>
      </li>
    </ul>
  </div><!-- /.navbar-collapse -->
</nav>
    
     

    

   <div class="container">
    <!--Div that will hold the pie chart-->
    <div id="chart_div1">
    </div>
    <div id="chart_div2">
    </div>
  </div>
  </body>
</html>