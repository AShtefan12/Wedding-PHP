<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="robots" content="noindex, nofollow"/>
    <title>Details Task</title>
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
<h1 class="center">Task 2 - Details (details.php)</h1>
<form action="details.php" method="get" id="details">
    <table border="1">
        <tr>
            <th scope="col">Key</th>
            <th scope="col">Value</th>
        </tr>
        <tr>
            <td><label for="venueId">Venue Id (venueId)</label></td>
            <td>
                <input name="venueId" type="text" class="larger" id="venueId" value="7" size="4"/>
            </td>
        </tr>
        <tr>
            <td>Submit</td>
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

        $venueid = $_GET["venueId"];

        //
        $sql = "SELECT * FROM venue WHERE venue_id = $venueid ";
        $res =& $db->query($sql);

        if (PEAR::isError($res)) {
            die($res->getMessage());
        }

        $venue = $res->fetchRow();


        echo '<tr>';
        echo '<td>venue id</td>';
        echo '<td>name</td>';
        echo '<td>capacity</td>';
        echo '<td>weekend price</td>';
        echo '<td>weekday price</td>';
        echo '<td>licensed</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>' . $venueid . '</td>';
        echo '<td>' . $venue["name"] . '</td>';
        echo '<td>' . $venue["capacity"] . '</td>';
        echo '<td>' . $venue["weekend_price"] . '</td>';
        echo '<td>' . $venue["weekday_price"] . '</td>';
        echo '<td>' . $venue["licensed"] . '</td>';
        echo '</tr>';


    }


    ?>
</table>
</body>
</html>
