<?php
		include_once("classes/Session.php");
		include_once("classes/Database.php");
		include_once("classes/Member.php");
		include_once("classes/Data.php");
	global $db;
	$id = $db->escape_value($_GET['id']);
				$sql = "select * from job_posts INNER JOIN members on posts.username = members.member_username where postid ='".$id."'";
				$result = $db->query($sql);
			  	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			  		if($_SESSION['MEMBER_ID']!=$row['member_id']){

					header("Location: login.php");
					}
					$location = $row['location'];
				}

				$sql = "DELETE FROM job_posts WHERE id = '".$id."'";
				$db -> query($sql);

				header("Location:index.php");

	?>
