<?php
	include_once("classes/Session.php");
	include_once("classes/Database.php");
	include_once("classes/Member.php");
	include_once("classes/Data.php");
	if(!$session->is_logged_in()){
		header("Location: login.php");
	}
	if (!isset($_GET['id'])){
	  	$myprofile=true;
	  	$profileid = $_SESSION['MEMBER_ID'];
	  	$name = "My Profile";
	}
	else{
		$sql  = "SELECT * FROM members WHERE member_username = '".$_GET['id']."' LIMIT 1";
		  	$result = $db->query($sql);
		  	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
		  	 	$profileid= $row['member_id'];  	
	  			$name = $row['member_firstname']." ".$row['member_lastname']; 	
			}
	}
	$reqruiter = false;
?>

<!--header-->
	<?php include_once("header.php"); ?>
	<!--end header-->	 	
		<div class = "container">
			<div class = "col-md-12">
			<h1>PROFILE</h1>
		  		<hr>
		  		
		  			<?php
						$sql  = "SELECT * FROM members where member_id = '".$profileid."'";
						$result = $db->query($sql);
						while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
							//get profile pic from server
						  
					  		echo '<div class = "col-md-12 col-sm-12 col-xs-12">';
					  		//print name and save other info
						  		echo '<div class ="row">';
								echo '<p class = "name">'.$row['member_firstname']." ".$row['member_lastname'].' </p>';
								$summary = $row['summary'];
							    $exp= $row['experience'];
								echo '<p>Organization: '.$row['member_organization'].'</p>';
							echo '</div>';//end row
							$username  = $row['member_username'];
						}
					?>
			  		<div class= "row">
				    <?php
					    //buttons
					    if($myprofile){
					    	echo'<p><a href="editprofile.php" class="button">EDIT</a>';
						}
						else
							echo '<a href="privatemessage.php?id='.$username.'"class="button" style = "padding:5px;">MESSAGE</a></p>';
				   ?>
				   </div>
		  		</div>
	  		</div>
	  	</div>
		<div class = "container">
			<div class = "col-md-12"><hr></div>
		    <div class = "col-md-12">
			    <div class = "description" >
			        <p>
			        	<span class = "bold">SUMMARY</span><br>
						<?php
							echo $summary;
							echo '<br><br><span class = "bold">EXPERIENCE</span><br>';
							echo $exp;
						?>
					</p>
				</div>
				<?php if ($reqruiter){
					echo '<p style = "font-size:20px;"><br>RECENT JOB POSTS<br> </p>';
						$result = mysql_query("SELECT COUNT(title) AS num FROM job_posts WHERE member_id = '".$profileid."'");

						//info for pagination
						while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
							$totalPosts = $row['num'];
						}
						if($totalPosts>0){
							if ($totalposts<=3){
								$startPost = 0;
								$endPost = 2;
							}
							else{
								$postsPerPage = 3;
								$totalPages = ceil($totalPosts / $postsPerPage)-1;
								$id = $_GET['id'];
								//initialize page #
								if(!isset($_GET['page'])){
								    $_GET['page'] = 1;
								}else{
								    // Convert the page number to an integer
								    $_GET['page'] = (int)$_GET['page'];
								}
								if($_GET['page'] > $totalPages)
									$_GET['page'] = $totalPages;
								$startPost = ($_GET['page']-1) * $postsPerPage;
								$endPost = $startPost+$postsPerPage;
							}
						try {
							global $db;
							//get limit range
							
							//get posts based on page #
								$result = $db->query("SELECT * FROM posts INNER JOIN members on posts.username = members.member_username where username = '".$username."' AND location = 'MAINFORUM' ORDER BY dateadded DESC limit ".$startPost.",".$endPost);
							while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
								//if posted today, show time only, toherwise print date only
								if (date('Ymd') == date('Ymd', strtotime($row['dateadded'])))
									$date =substr($row['dateadded'], 10,16);
								else 
									$date =substr($row['dateadded'], 0,10);

								echo '<div class= "row">';
									echo '<div class= "col-md-3 col-sm-3 col-xs-3 threadPic" >';
									//profile pic
										echo '<img style = "width:200px;"class = "profilepic img-responsive" alt = "dmgLogo" src="profilepictures/'.$row['member_id'].'">';
										echo '<p><br><b>'.$row['member_firstname'].' '.$row['member_lastname'].'</b>   '.$date.'</p>';
									echo '</div>';
									echo '<div class = "col-md-9 col-sm-9 col-xs-9">';
										echo '<div class= "row">';
											$title = strToUpper($row['title']);
											echo '<p style="font-size:30px;" ><a style= "color:#404041; " href="viewpost.php?id='.$row['postid'].'">'.$title.'</a></p>';

										echo '</div>';
										echo '<div class = "row threadButtons" style = "margin-left:-5px">';
											echo '<a href="viewpost.php?id='.$row['postid'].'" class="button">VIEW</a>';
										echo '</div>';
									echo '</div>';
								echo '</div>';
								echo '<div class = "row col-md-12"> <hr></div>';
							}
						} catch(PDOException $e) {
		    
				}
		}
		$id = $_GET['id'];
		//pagination
			echo '<div class = "container">';
			if($totalPosts>3){
				if($_GET['page']<$totalPages-1){
					echo '<div class = "col-md-3 pull-left">';
						echo '<a href="?id='.$id.'&page='.($_GET['page']+1).'"><< OLDER POSTS</a>';
					echo '</div>';	
				}
				if($_GET['page']!=1){
					echo '<div class = "col-md-3 pull-right" >';
						echo '<a href="?id='.$id.'&page='.($_GET['page']-1).'"> NEWER POSTS >></a>';
					echo '</div>';	
				}
			echo '</div>';	
		}
		echo '</div>';}
			?>
	</div>
	</div></div>
		    <?php include_once("footer.php"); ?>
	</body>
</html>