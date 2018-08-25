<?php
require 'config.php';
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DATABASE_NAME);
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
header('Content-type: application/json');
if (isset($_POST['all'])) {
    $mysqli->set_charset("utf8");
    $query = 'select * from meteorite';
    $metArray = getResults($query);
    echo json_encode($metArray);
    //echo json_last_error_msg();

} elseif (isset($_POST['name'])) {
    $name = strip_tags($_POST['name']);
    $query = "select * from meteorite where name = '" . $name . "'";
    $metArray = getResults($query);
    echo json_encode($metArray);
} elseif (isset($_POST['mass'])) {
    $mass = strip_tags($_POST['mass']);
    $massOption = strip_tags($_POST['massOption']);
    $query = "";
    if ($massOption === "equal") {
        $query = "select * from meteorite where mass = " . $mass;

    } elseif ($massOption === "less") {
        $query = "select * from meteorite where mass < " . $mass;
    } elseif ($massOption === "greater") {
        $query = "select * from meteorite where mass > " . $mass;
    }
    $metArray = getResults($query);
    echo json_encode($metArray);
} elseif (isset($_POST['year'])) {
    $year = strip_tags($_POST['year']);
    $yearOption = strip_tags($_POST['yearOption']);
    $query = "";
    if ($yearOption === "equal") {
        $query = "select * from meteorite where year = " . $year;
    } elseif ($yearOption === "before") {
        $query = "select * from meteorite where year < " . $year;
    } elseif ($yearOption === "after") {
        $query = "select * from meteorite where year > " . $year;
    }
    $metArray = getResults($query);
    echo json_encode($metArray);
} elseif (isset($_POST['cont'])) {
    $continent = strip_tags($_POST['cont']);
    $query = "";
    if ($continent === "North America") {
        $query = "select * from meteorite where reclat between 20 and 90 AND reclong BETWEEN -170 AND -40\r\n" .
            "OR (reclat BETWEEN 70 and 90 AND reclong BETWEEN -140 and -110)\n" .
            "OR (reclat BETWEEN 68 and 90 and reclong BETWEEN -40 and -20)\n" .
            "OR (reclat BETWEEN 60 and 90 AND reclong BETWEEN -40 and -30) \n" .
            "OR (reclat BETWEEN 13 and 90 AND reclong BETWEEN -120 and -60)\n" .
            "OR (reclat BETWEEN 70 and 5 AND reclong BETWEEN -100 and -88)\n" .
            "OR (reclat BETWEEN 50 and 80 AND reclong BETWEEN -140 and -160)\n" .
            "OR (reclat BETWEEN 50 and 80 AND reclong BETWEEN -160 and -170);";
    } elseif ($continent === "South America") {
        $query = "select * from meteorite where reclat between -60 and 0 AND reclong BETWEEN -85 AND -30\r\n" .
            "OR (reclat BETWEEN 0 and 10 AND reclong BETWEEN -60 and -40)\n" .
            "OR (reclat BETWEEN 0 and 10 and reclong BETWEEN -60 and -75)\n" .
            "OR (reclat BETWEEN 0 and 7 AND reclong BETWEEN -60 and -80) \n" .
            "OR (reclat BETWEEN 10 and 14 AND reclong BETWEEN -60 and -75);";

    } elseif ($continent === "Europe") {
        $query = "select * from meteorite where reclat between  60 and 70 AND reclong BETWEEN -25 AND 60\n" .
            "OR (reclat BETWEEN 70 and 90 and reclong BETWEEN -10 and 70)\n" .
            "OR (reclat BETWEEN 50 and 60 AND reclong BETWEEN -10 and 60)\n" .
            "OR (reclat BETWEEN 40 and 50 AND reclong BETWEEN -10 and 50)\n" .
            "OR (reclat BETWEEN 35 and 40 and reclong BETWEEN -10 and 25);";
    } elseif ($continent === "Asia") {
        $query = "select * from meteorite where reclat between -10 and 65 AND reclong BETWEEN 60 AND 180\n" .
            "OR (reclat BETWEEN 65 and 90 AND reclong BETWEEN 70 and 180)\n" .
            "OR (reclat BETWEEN -10 and 65 and reclong BETWEEN 65 and 180)\n" .
            "OR (reclat BETWEEN 20 and 40 AND reclong BETWEEN 40 and 60)\n" .
            "OR (reclat BETWEEN 15 and 20 AND reclong BETWEEN 40 and 60)\n" .
            "OR (reclat BETWEEN 12 and 20 and reclong BETWEEN 45 and 60)\n" .
            "OR (reclat BETWEEN 40 and 50 AND reclong BETWEEN 50 and 60)\n" .
            "OR (reclat BETWEEN 40 and 43 and reclong BETWEEN 40 and 60)" .
            "OR (reclat BETWEEN 35 and 43 and reclong BETWEEN 30 and 40);";
    } elseif ($continent === "Africa") {
        $query = "select * from meteorite where reclat between 0 and 30 AND reclong BETWEEN -20 AND 35\n" .
            "OR (reclat BETWEEN 30 and 36 AND reclong BETWEEN -20 and 0)\n" .
            "OR (reclat BETWEEN -40 and 37 and reclong BETWEEN 0 and 12)\n" .
            "OR (reclat BETWEEN -40 and 35 AND reclong BETWEEN 12 and 20)\n" .
            "OR (reclat BETWEEN -40 and 33 AND reclong BETWEEN 20 and 35)\n" .
            "OR (reclat BETWEEN 10 and 20 and reclong BETWEEN 20 and 40)\n" .
            "OR (reclat BETWEEN 0 and 12 AND reclong BETWEEN 40 and 60)\n" .
            "OR (reclat BETWEEN -40 and 10 and reclong BETWEEN 20 and 60);";
    } elseif ($continent === "Australia") {
        $query = "select * from meteorite where reclat between -50 and -10 and reclong between 110 and 180 OR (reclat between -10 and 0 and reclong between 142 and 180);";
    } elseif ($continent === "Antarctica") {
        $query = "select * from meteorite where reclat between -90 and -60 AND reclong BETWEEN -180 AND 180;";
    }
    $metArray = getResults($query);
    echo json_encode($metArray);
} else {
    print 'Post array is not set';
}

function getResults($query)
{
    global $mysqli; // Access the mysqli variable
    $result = $mysqli->query($query);
    $metArray = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $meteorite = array(
                "name" => mb_convert_encoding($row["name"], 'UTF-8', 'UTF-8'), // Make sure there are no malformed UTF-8 characters
                "year" => mb_convert_encoding($row["year"], 'UTF-8', 'UTF-8'),
                "mass" => mb_convert_encoding($row["mass"], 'UTF-8', 'UTF-8'),
                "lat" => mb_convert_encoding($row["reclat"], 'UTF-8', 'UTF-8'),
                "long" => mb_convert_encoding($row["reclong"], 'UTF-8', 'UTF-8'),
            );

            $metArray[] = $meteorite;
        }

    }
    $result->free();
    $mysqli->close(); // close the conneciton
    return $metArray;
}
