<?php 
	include_once("classes/Session.php");
	include_once("classes/Database.php");
	include_once("classes/Member.php");
	include_once("classes/Data.php");
	if(!$session->is_logged_in())
		header("Location:/60334/jobs/login.php");
	$search = "";
	$by = "title";
	global $db;

	if(isset($_POST['search'])){
		header("Location:index.php?search=".$_POST['searchbar']."&by=".$_POST['by']);
	}
	if(isset($_GET['search'])){
		$search = true;
		$keyword = $db->escape_value($_GET['search']);
		$by = $db->escape_value($_GET['by']);
	}
	$result = $db->query("SELECT * FROM members where member_id = '".$_SESSION['MEMBER_ID']."'");
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
             $type = $row['member_type'];
 	}
?>
	<!--header-->
	<?php include_once("header.php"); ?>


	<!--end header-->
	<div class = "container">


		<div class = "col-md-10">
		<form>
			  <select name = "by"><option value = "title">title</option><option value = "tags">category</option><option value = "location">location</option><option value = "description">description</option></select><input type="text" name="search" class = "search" placeholder="Search..">
		</form></div><div class = "col-md-2">
			<?php if ($type==1)
				echo '<a href="createpost.php" class="button">Create Post</a>';
			?>
		</div>
	</div>
		<?php
		//get member name and company
		global $db;
		
		$id = $_SESSION['MEMBER_ID'];
		//total # of posts
		$result = $db->query("SELECT COUNT(title) AS num FROM job_posts");
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
			$totalPosts = $row['num']; 
		}
			$postsPerPage = 5;
			$totalPages = ceil($totalPosts / $postsPerPage);
			//initialise page #
			if(!isset($_GET['page'])){
			    $_GET['page'] = 1;
			}else{
			    // Convert the page number to an integer
			    $_GET['page'] = (int)$_GET['page'];
			}
			if($_GET['page'] > $totalPages)
	    		$_GET['page'] = $totalPages;
				echo '<div class = "container">';
				echo '<div class = "col-md-12"> <hr></div>';
			
				$startPost = ($_GET['page']-1) * $postsPerPage;
				$endPost = $startPost+$postsPerPage;
				if($search){
					$result = $db->query("SELECT * FROM job_posts INNER JOIN members on job_posts.member_id = members.member_id WHERE ".$by." LIKE '%".$keyword."%' ORDER BY date_added DESC limit ".$startPost.",".$endPost."");

				}
				else
					$result = $db->query("SELECT * FROM job_posts INNER JOIN members on job_posts.member_id = members.member_id ORDER BY date_added DESC limit ".$startPost.",".$endPost."");
				
				while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
					$title = strToUpper($row['title']);
					//if it was posted today, show time and allow user to edit their post
					if (date('Ymd') == date('Ymd', strtotime($row['date_added']))){
						$date =substr($row['date_added'], 10,16);
						$edit = true;
					}
					//else only show date and dont allow post edit
					else{
						$date=substr($row['date_added'], 0,10);
						$edit=false;
					}
					echo '<div class= "col-md-12 row">';
						echo '<div class= "col-md-2 col-sm-2 col-xs-3 threadPic">';
							 if($_SESSION['MEMBER_ID'] == $row['member_id']){
								//profile picture
								echo '<p><a href= "profile.php" >'.$row['member_firstname'].' '.$row['member_lastname'].'</a><br>';
							        echo '<p>'.$row['member_organization'].'<br>';
}
							else{
								//profile picture
								
								echo '<p><a href= "profile.php?id='.$row['member_username'].'">'.$row['member_firstname'].' '.$row['member_lastname'].'</a><br>';
								echo '<p>'.$row['member_organization'].'<br>';
							}
							//publish date
							echo '<span class = "date">'.$date.'</span></p>';
						echo '</p></div>';
						//post info
						echo '<div class= "col-md-10  col-sm-10 col-xs-9" style = "padding-left:30px;">';
						//post
							echo '<div class= "row posts">';
								echo '<h1>'.$title.'</h1>';
							echo '</div>';
							//buttons
							echo '<div class = "row threadButtons">';
								echo '<a href="viewpost.php?id='.$row['id'].'" class="button">VIEW</a>';
								//if they're the author
								if($_SESSION['MEMBER_ID'] == $row['member_id']){
									if($edit){
										echo '<a href="delete.php?id='.$row['id'].'" onclick="return confirm(\'Are you sure you want to delete?\')" name = "delete" id = "delete" class="button">DELETE</a>';	
									}
									
								}	
							echo '</div>';
						echo '</div>';
						echo '<div class = "col-md-12 col-sm-12 col-xs-12"> <hr></div>';
					echo '</div>';
				}
			echo '</div>';	
			if(isset( $_GET['id']))
				$id = $_GET['id'];
		//pagination
		echo '<div class = "container">';
		if($_GET['page']<$totalPages){
			echo '<div class = "col-md-9">';
				echo '<a href="?id='.$id.'&page='.($_GET['page']+1).'"><< OLDER POSTS</a>';
			echo '</div>';	
		}
		if($_GET['page']!=1){
			echo '<div class = "col-md-3 pull-right" >';
				echo '<a href="?id='.$id.'&page='.($_GET['page']-1).'"> NEWER POSTS >></a>';
			echo '</div>';	
		}
		echo '</div>';
		?>
		<!--comment form-->
 <?php include_once("footer.php"); ?>
	</body>
</html>