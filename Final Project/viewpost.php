<?php 
	require('classes/Database.php'); 
	include_once("classes/Session.php");
	global $db;
	$postid = $db->escape_value($_GET['id']);
	if(isset($_SESSION['MEMBER_ID']))
		$id =$_SESSION['MEMBER_ID'];
	else{
		header("Location: login.php");
	}
	$sql  = "SELECT * FROM members WHERE member_id = '".$id."' LIMIT 1";
	$result = $db->query($sql);
	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	  	$username= $row['member_username']; 
	  	$isAdmin = $row['is_admin']; 
	  	$company = $row['member_organization']; 
	  	$name = $row['member_firstname'].' '.$row['member_lastname'];
	}

?>

	 	<?php include_once("header.php"); ?>
		<?php
			//get post
			$result = $db->query("SELECT * FROM job_posts INNER JOIN members on job_posts.member_id = members.member_id WHERE job_posts.id = '".$postid."' LIMIT 1");
				global $db;
				while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
					if (date('Ymd') == date('Ymd', strtotime($row['date_added']))){
						$date =substr($row['date_added'], 10,16);
						$edit = true;
						}
						else{
						$date =substr($row['date_added'], 0,10);
						$edit=false;
					}
					$location = $row['location'];
					if ($company==$location || $location = 'mainforum'){
						echo '<div class = "container">';
							echo '<div class = "col-md-12"> ';
								echo '<p><a href ="/60334/jobs">HOME > </a>';
								echo strtoupper($row['title']).'</p>';
							echo '</div>';
						echo '</div>';
						echo '<div class = "container">';
	
							echo '<div class= "col-md-12  col-sm-12 col-xs-12 ">';
								echo '<div class= "row postcontent">';
										echo '<p class = "title">'.$row['title'].'</p>';	
										if($_SESSION['MEMBER_ID'] == $row['member_id'])
										echo '<p><a href= "profile.php" >'.$row['member_firstname'].' '.$row['member_lastname'].'</a> • '.$row['date_added'].'</p>';
										else
										echo '<p><a href= "profile.php?id='.$row['member_username'].'" >'.$row['member_firstname'].' '.$row['member_lastname'].'</a> • '.$row['date_added'].'</p>';
										echo '<p class = "post"><strong>Description:</strong>'.$row['description'].'</p><br>';
										echo '<p class = "post"><strong>Requirements:</strong>'.$row['requirements'].'</p><br>';		
										echo '<p class = "post"><strong>Location:</strong>'.$row['location'].'</p><br>';	
										echo '<p class = "post"><strong>Salary:</strong>'.$row['salary'].'</p><br>';	

										
											
								echo '</div>';
								echo '<div class = "row threadButtons">';
											if($_SESSION['MEMBER_ID'] == $row['member_id']){
												$author =  true;
												if($edit){

										echo '<a href="delete.php?id='.$row['id'].'" onclick="return confirm(\'Are you sure you want to delete?\')" name = "delete" id = "delete" class="button">DELETE</a>';
										}

											}	
								echo '</div>';	
							echo '</div>';	
						echo '</div>';	
					echo '</div>';
					echo '<div class = "container"> <hr></div>';
					echo '<div class = "clear"></div>';
				}
			}
			
	
?>
<div class = "clear"></div>

</div>
 <?php include_once("footer.php"); ?>
	</body>
</html>