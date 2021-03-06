<?php
	include '../include/database.php';

	$time = time();
	srand($time);

	$tag = escapeshellarg(ereg_replace("[^A-Za-z0-9%]", "", "%".$_POST["tag"]."%"));

	if ($_POST["tag"] != "NOTAG") {
		$query = "SELECT * FROM cats WHERE tags LIKE $tag AND flag = 0 AND $time - lastView > 900 ORDER BY RAND() LIMIT 1";
	} else {
		$query = "SELECT * FROM cats WHERE flag = 0 ORDER BY RAND() LIMIT 1";
	}

	$results = mysqli_query($con, $query);
	$row = mysqli_fetch_array($results);

	$query = "UPDATE cats SET views = views+1 WHERE reblogKey = \"".$row["reblogKey"]."\"";
	mysqli_query($con, $query);
	mysqli_close($con);

	if (isset($row["imageUrl"])) {
		$output = array('imageUrl' => $row["imageUrl"],
			'postUrl' => $row["postUrl"],
			'rating' => $row["rating"],
			'reblogKey' => $row["reblogKey"],
			'error' => 0);
	} else {
		$output = array('error' => 1);
	}

	echo json_encode($output);
?>