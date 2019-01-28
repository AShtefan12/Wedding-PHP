<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="robots" content="noindex, nofollow"/>
    <title>Costs Task</title>
    <style type="text/css">
        body {
            font-family: "Apple Chancery", Times, serif;
            background-color: #D6D6D6;
        }

        .center {
            text-align: center;
        }

        body, td, th {
            color: #06F;
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
<h1 class="center">Task 4 - Costs (costs.php)</h1>

<form action="costs.php" method="get" id="costs">
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
            <td><label for="partySize">Party size (partySize)</label></td>
            <td><input name="partySize" type="text" class="larger" id="partySize" value="150" size="5"/></td>
        </tr>

        <tr>
            <td>List names and costs of available venues for the given date and party size</td>
            <td><input type="submit" name="submit" id="submit" value="Submit" class="larger"/></td>
        </tr>
    </table>
</form>

<table border="1">
    <?php
    require_once 'MDB2.php';

    include "coa123-mysql-connect.php"; //to provide $username,$password

    // define $host
    $host = 'localhost';


    // make connection to the server
    $dsn = "mysql://$username:$password@$host/$dbName";
    $db =& MDB2::connect($dsn);


    $db->setFetchMode(MDB2_FETCHMODE_ASSOC);

    if (isset($_GET["submit"])) {

        $date = $_GET["date"];
        $partySize = $_GET["partySize"];
        $date = str_replace("/", "-", $date);
        $date = strtotime($date);


        $sql = "SELECT `name`, weekend_price, weekday_price, venue_id FROM venue WHERE venue_id NOT IN (SELECT venue_booking.venue_id FROM venue_booking WHERE date_booked = '" . date("Y-m-d", $date) . "') AND capacity >= $partySize   ";
        $res =& $db->query($sql);

        if (PEAR::isError($res)) {
            die($res->getMessage());
        }

        $venues = $res->fetchAll();


        echo '<tr>';
        echo '<td>name</td>';
        echo '<td>price</td>';
        echo '</tr>';

        for ($i = 0; $i < count($venues); $i++) {
            echo '<tr>';
            echo '<td>' . $venues[$i]["name"] . '</td>';
            if (date("N", $date) > 5) {
                echo '<td>' . $venues[$i]["weekend_price"] . '</td>';
            } else {
                echo '<td>' . $venues[$i]["weekday_price"] . '</td>';
            }
            echo '</tr>';
        }


    }


    ?>
</table>
</body>
</html>
