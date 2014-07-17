     $(document).ready(function () {
  //$("#myTable").tablesorter(); 

    $('#sandbox-container .input-daterange').datepicker({
        format: "yyyy-mm-dd"
    });

});
google.load('visualization', '1.0', {
    'packages': ['corechart']
});

function fintime(sec) {
    var delta = sec;
    var days = Math.floor(delta / 86400);
    var hours = Math.floor(delta / 3600) % 24;
    var minutes = Math.floor(delta / 60) % 60;
    var seconds = delta % 60;
    if (days == 0)
        return hours + ' hours, ' + minutes + ' minutes and ' + seconds + ' seconds';
    return days + ' days, ' + hours + ' hours, ' + minutes + ' minutes and ' + seconds + ' seconds';
}

function count(total, arg) {
    var count = 0;
    for (var i = total.length - 1; i >= 0; i--) {
        //console.log(total[i].type.summary);
        if (arg == "outa") {
            if ("outgoing " == total[i].type) {
                if (total[i].time > 0)
                    count++;
            }

        }
        if (arg == "out") {
            if ("outgoing " == total[i].type) {
                if (total[i].time == 0)
                    count++;
            }

        } else if (arg == total[i].type)
            count++;
    }
    return count;

}

function gettotal(total, arg) {
    var count = 0;
    for (var i = total.length - 1; i >= 0; i--) {
        if (arg == total[i].type) {
            count += total[i].time;
        }
    }
    //console.log(count);
    return count;
}

function drawChart() {
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
    var options = {
        'title': 'My total Calls: ' + totaldata.length,
        'width': 1200,
        'height': 300
    };



    t1 = gettotal(totaldata, "incoming ");
    t2 = gettotal(totaldata, "outgoing ");
    var data2 = new google.visualization.DataTable();
    data2.addColumn('string', 'Topping');
    data2.addColumn('number', 'Slices');
    data2.addRows([
        ['incoming ' + fintime(t1), t1],
        ['outgoing ' + fintime(t2), t2]

    ]);

    var options2 = {
        title: 'My Total Talk Times: ' + fintime(t1 + t2),
        is3D: true,
        'width': 1200,
        'height': 300
    };


    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.PieChart(document.getElementById('chart_div1'));
    chart.draw(data, options);
    var chart2 = new google.visualization.PieChart(document.getElementById('chart_div2'));
    chart2.draw(data2, options2);
}
var totaldata;
$("#loading").hide();

function Plot() {
 
    totaldata = {};
    var start = $('#start').val();
    var end = $('#end').val();
    url = "get.php?start=" + start + "&end=" + end;
    //alert(url);
    $("#loading").show();
    $.get(url, function (data) {
        totaldata = JSON.parse(data);
        //console.log(totaldata);
        // obj = JSON.parse(totaldata);
        // alert(obj[0].id);
        google.setOnLoadCallback(drawChart);
        $("#loading").hide();
        drawChart();
        datamaker(totaldata);
    });
}

function datamaker(data) {

    var result = [];
    for (i = 0; i<data.length; i++) {
        //console.log("i " + i);
        var j = 0;
        var number = data[i].number.slice(-11).replace(" ", "");
        var name = data[i].summary.replace("Missed call from ", "")
        name = name.replace("Call from ", "");
        name = name.replace("Called ", "");
        if (result.length == 0) {
            result[0] = {};
            result[0].number = number;
            result[0].name = name;
            result[0].num_calls = 0;
            result[0].call_time = 0;

        }
        var flag = 0;
        for (j = 0; j < result.length; j++) {
            if (result[j].number == number) {
                result[j].num_calls++;
                result[j].call_time += data[i].time;
                flag = 1;
            }
        }
        if (j == result.length) {
              if(flag==0){
                result[j] = {};
                result[j].number = number;
                result[j].name = name;
                result[j].num_calls = 1;
                result[j].call_time = data[i].time;
                }
            }
        

    }
   var html="";
    $(".table").html("<thead><tr><th>Name</th><th>Number</th><th>Total Calls</th><th>Total Call Time</th></tr></thead>");
    $(".table").append(html);
    for (var key=0, size=result.length; key<size; key++){
       html += '<tr><td>'
             + result[key].name
             + '</td><td>'
             + result[key].number
             + '</td><td>'
             + result[key].num_calls
             + '</td><td>'
             + fintime(result[key].call_time)
             + '</td></tr>';
 }
 $(".table").append(html);
 $("#myTable").tablesorter(); 
}

arr={};
arr["com.google.android.music"] = "https://lh5.ggpht.com/u0cJl6arXfebTaDYDuuG4fO_9op5OGoRvamZWt2Udbt2rGrdoXVRAdt1A7Gd_c9q2Gk=w300-rw";
arr["com.zegoggles.smssync"] = "https://lh6.ggpht.com/84qKKFHW0D7cRuLKHOLnPRVl38dqj5xBOWZS-cz5d3-OsINdJr6t83s12P2RQFxX23E=w300-rw";
arr["com.google.android.talk"] = "https://lh4.ggpht.com/a5tbkHBpZ2TGJQC-Rco4BTJOOR5LTgzB6YbLrxswlQsLi6Y-voGksMLIUybaCIaaFgA=w300-rw";
arr["com.whatsapp"] = "https://lh3.ggpht.com/bwBj9B4fGmTN_of0JS8xdkwklCmqCzSne1tJ9RaUNRQIzU-FEyCuFWzlsLyyPoTbyJE=w300-rw";



function getImage(package){
    if (arr[package]==undefined){
        return "assets/android.png";
    }
    return arr[package];
}

function Notif(){
    var start = $('#start').val();
    var end = $('#end').val();
    url = "getnotif?start=" + start + "&end=" + end;
    //alert(url);
    $("#loading").show();
    $.get(url, function (data) {
        totaldata = JSON.parse(data);
        console.log(totaldata[100].summary["android.text"]);
        //$(".notiful").append('<li>'+totaldata[100].summary["android.text"]+'</li>');
        for(i=1; i<totaldata.length; i++){

            if(typeof totaldata[i].summary["android.textLines"] != "undefined"){
                desc = totaldata[i].summary["android.textLines"];
            } else if(totaldata[i].summary["android.text"] == "null" || totaldata[i].summary["android.text"] == "undefine"){
                desc = ""    
            } else{
                desc = totaldata[i].summary["android.text"];
            }
                html='<li><table><tr><th rowspan="2"><img src='+getImage(totaldata[i].summary["package"])+' width="40" height="40"></th><th>'
                +'<h4>'+totaldata[i].summary["android.title"]+'</h2></th>'
                +'</tr><tr><td>'
                +'<h6>'+desc+'</h4>'
                +'</td></tr><tr><td colspan="2">'
                +'<h6>'+totaldata[i].summary["package"]+" "+totaldata[i].start+'</h6>'
                +'</td></tr></table></li>';
                
                $(".notiful").append(html);
           
          
        }
        //console.log(totaldata);
        // obj = JSON.parse(totaldata);
        // alert(obj[0].id);
       //google.setOnLoadCallback(drawChart);
        $("#loading").hide();
        //drawChart();
        //datamaker(totaldata);
    });
}

