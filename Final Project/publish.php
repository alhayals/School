<?php  require('classes/Database.php'); 
	include_once("classes/Session.php");
global $db;
$sql  = "SELECT * FROM members WHERE member_id = '".$_SESSION['MEMBER_ID']."'";
        $result = $db->query($sql);
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
          $username= $row['member_username'];
          $company = $row['member_organization'];     
      }

 $title = $_POST['title'];
		$description =$db->escape_value($_POST['description']);
		$requirements =$db->escape_value($_POST['requirements']);
		$location =$db->escape_value($_POST['location']);
		$salary =$db->escape_value($_POST['salary']);
		$tags = $db->escape_value($_POST['tag']);
		if (empty($title)) {
			echo 'Don\'t forget to give your posting a title!';
		}
		else if (empty($description)) {
			echo 'Please add a job description to your post';
		}
		else if (empty($requirements)) {
			echo 'Please add some requirements to your post';
		}
		else if (empty($location)) {
			echo 'Please add a location to your post';
		}

		else if (empty($salary)) {
			echo 'Please add a salary to your post';
		}
		else{
		$sql="insert into job_posts (title,description, location, requirements, salary,date_added, member_id, tags) values ('".$_POST['title']."','".$_POST['description']."','".$_POST['location']."','".$_POST['requirements']."','".$_POST['salary']."','".date("Y-m-d H:i:s")."', '".$_SESSION['MEMBER_ID']."','".$tags."')";
		$db -> query($sql);
		echo "success";
}

	
	
?>