<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="robots" content="noindex, nofollow"/>
    <title>Capacity Task</title>
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

<h1 class="center">Task 3 - Capacity (capacity.php)</h1>
<form action="capacity.php" method="get" id="capacity">
    <table border="1">
        <tr>
            <th scope="col">Key</th>
            <th scope="col">Value</th>
        </tr>
        <tr>
            <td><label for="minCapacity">Minimum capacity of venue (minCapacity)</label></td>
            <td>
                <input name="minCapacity" type="text" class="larger" id="minCapacity" value="150" size="12"/>
            </td>
        </tr>
        <tr>
            <td><label for="maxCapacity">Maximum capacity of venue (maxCapacity)</label></td>
            <td><input name="maxCapacity" type="text" class="larger" id="maxCapacity" value="220" size="12"/></td>
        </tr>
        <tr>
            <td>List names and prices of available licensed venues with given capacity</td>
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

        $minCapacity = $_GET["minCapacity"];
        $maxCapacity = $_GET["maxCapacity"];


        $sql = "SELECT * FROM venue WHERE capacity >= $minCapacity AND capacity <= $maxCapacity AND licensed = 1 ";
        $res =& $db->query($sql);

        if (PEAR::isError($res)) {
            die($res->getMessage());
        }

        $venues = $res->fetchAll();


        echo '<tr>';
        echo '<td>name</td>';
        echo '<td>weekend price</td>';
        echo '<td>weekday price</td>';
        echo '</tr>';

        for ($i = 0; $i < count($venues); $i++) {
            echo '<tr>';
            echo '<td>' . $venues[$i]["name"] . '</td>';
            echo '<td>' . $venues[$i]["weekend_price"] . '</td>';
            echo '<td>' . $venues[$i]["weekday_price"] . '</td>';
            echo '</tr>';
        }


    }


    ?>
</table>
</body>
</html>
