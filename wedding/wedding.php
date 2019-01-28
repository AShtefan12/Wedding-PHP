<?php

if (isset($_GET["submit"])) {

    require_once 'MDB2.php';

    include "coa123-mysql-connect.php"; //to provide $username,$password

// define $host
    $host = 'localhost';


// make connection to the server
    $dsn = "mysql://$username:$password@$host/$dbName";
    $db =& MDB2::connect($dsn);


    $db->setFetchMode(MDB2_FETCHMODE_ASSOC);

// define variables and format the dates
    $date = $_GET["date"];
    $date2 = $_GET["date2"];
    $partySize = $_GET["partySize"];
    $grade = $_GET["cateringGrade"];
    $date = str_replace("/", "-", $date);
    $date2 = str_replace("/", "-", $date2);
    $date = strtotime($date);
    $date2 = strtotime($date2);

//use a loop to go through the venues each day and retrieve the unbooked ones
    while ($date <= $date2) {
        $newdate = date("Y-m-d", $date);
        $sql = "SELECT * 
				FROM venue JOIN catering 
				ON venue.venue_id = catering.venue_id
				WHERE venue.venue_id
				NOT IN (
				SELECT DISTINCT venue.venue_id
				FROM venue JOIN catering ON venue.venue_id = catering.venue_id
				JOIN venue_booking ON venue.venue_id = venue_booking.venue_id
				WHERE date_booked = '$newdate' )
				AND capacity >= $partySize 
				AND grade = $grade  ";

        $res =& $db->query($sql);

        if (PEAR::isError($res)) {
            die($res->getMessage());
        }

        $venues[$newdate] = $res->fetchAll();

//determines the cost for weekdays and weekends
        for ($i = 0; $i < count($venues[$newdate]); $i++) {

            if (date("N", $date) > 5) {
                $venues[$newdate][$i]["total"] = $venues[$newdate][$i]["weekend_price"] + ($venues[$newdate][$i]["cost"] * $partySize);
            } else {
                $venues[$newdate][$i]["total"] = $venues[$newdate][$i]["weekday_price"] + ($venues[$newdate][$i]["cost"] * $partySize);
            }
        }

        $date = strtotime("+1 day", $date);
    }

    echo json_encode($venues);
    die();

}


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="robots" content="noindex, nofollow"/>
    <title>Costs Task</title>
    <style type="text/css">
        body {
            font-family: "Apple Chancery", Times, serif;
            background-image: url("wedding images/wedding.jpg");

        }

        .center {
            text-align: center;
        }

        td, th {
            background: white;

        }

        .larger {
            font-size: larger;
            text-align: right;
        }

        table {
            margin-left: auto;
            margin-right: auto;

        }
    </style>
</head>
<body>
<h3 class="center">COA123 - Server-Side Programming</h3>
<h2 class="center">Individual Coursework - Wedding Planner</h2>
<h1 class="center">Task 5 - Wedding (wedding.php)</h1>

<form action="wedding.php" method="get" id="wedding">
    <table border="1">
        <tr>
            <th scope="col">Key</th>
            <th scope="col">Value</th>
        </tr>
        <tr>
            <td><label for="date">Date as dd/mm/yyyy (date)</label></td>

            <td>
                <input name="date" type="text" class="larger" id="date" value="18/05/2018" size="12"/>
            </td>
        </tr>
        <tr>
            <td><label for="date2">Date as dd/mm/yyyy (date)</label></td>

            <td>
                <input name="date2" type="text" class="larger" id="date2" value="21/05/2018" size="12"/>
            </td>
        </tr>
        <tr>
            <td><label for="partySize">Party size (partySize)</label></td>
            <td><input name="partySize" type="text" class="larger" id="partySize" value="150" size="5"/></td>
        </tr>
        <tr>
            <td><label for="cateringGrade">Catering grade (cateringGrade)</label></td>
            <td>
                <select name="cateringGrade" id="cateringGrade" class="larger" style="width: 100px">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </td>
        </tr>


        <tr>
            <td>List names and costs of available venues for the given date and party size</td>
            <td><input type="button" onclick="Button()" name="submit" id="submit" value="Submit" class="larger"/></td>
        </tr>
    </table>
</form>
<div id="results"></div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
<script>
    function Button() {
        var date = $("#date").val();
        var date2 = $("#date2").val();
        var partySize = $("#partySize").val();
        var grade = $("#cateringGrade").val();

        $.get("wedding.php", {
            date: date,
            date2: date2,
            partySize: partySize,
            cateringGrade: grade,
            submit: ''
        }, function (venues) {
            var html = "";
//have the date show above all the venues available during that date
            for (var j in venues) {
                html += '<h3 class = "center">' + j + '</h3>';
                html += '<table border="1">';
                html += '<tr>';
                html += '<td>name</td>';
                html += '<td>price</td>';
                html += '</tr>';
                for (var i = 0; i < venues[j].length; i++) {
                    html += '<tr>';
                    html += '<td>' + venues[j][i]["name"] +
                        '</td>';
                    html += '<td>' + venues[j][i]["total"] +
                        '</td>';
                    html += '</tr>';
                }
                html += "</table><br>";
            }
            $("#results").html(html);

        }, "json");

    }


</script>
</body>
</html>
