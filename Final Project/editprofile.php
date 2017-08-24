<?php
	include_once("classes/Session.php");
	include_once("classes/Database.php");
	include_once("classes/Member.php");
	include_once("classes/Data.php");
	$error = '';
if(!$session->is_logged_in()){
		header("Location: login.php");
	}
		$userid =$_SESSION['MEMBER_ID'];
	
		if(isset($_POST['save']))
			{
				global $db;
				$sql = "update members set summary = '".$_POST['summary']."', experience = '".$_POST['exp']."' where member_id = '".$_SESSION['MEMBER_ID']."'";
				$db -> query($sql);
				header("Location: profile.php");
			}

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
	//if they change their profile pciture
$target_dir = "profilepictures/";
 $basename = basename($_FILES["fileToUpload"]["name"]);
$target_file = $target_dir .  $basename;
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	    if($check !== false) {
	        $error =  "File is an image - " . $check["mime"] . ".";
	        $uploadOk = 1;
	    } else {
	        $error = "File is not an image.";
	        $uploadOk = 0;
	    }
	// Check if file already exists
	if (file_exists($target_file)) {
	     $error =   "Sorry, file already exists.";
	    $uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) {
	     $error =   "Sorry, your file is too large.";
	    $uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
	    $error =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	    $uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	     $error =   "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	         $error =  "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
	         //rename to their member id
	        rename($target_file, "profilepictures/".$_SESSION['MEMBER_ID']);
	    } else {
	         $error =  "Sorry, there was an error uploading your file.";
	    }
	}
}
?>

		<!--header-->
		<?php include_once("header.php"); ?>
		<!--end header-->	 	
		<div class = "container">
			<h1>Profile</h1>
		  		<hr>
		  		<?php 
			  		
			  		$sql  = "SELECT * FROM members where member_id = '".$_SESSION['MEMBER_ID']."'";
						$result = $db->query($sql);
						while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
							//profile picture

		  					//user info
							echo '<div class = "col-md-12 col-sm-12  col-xs-12 ">
			  					<div class ="row">';
		  	  						
									echo '<h1>'.$row['member_firstname']." ".$row['member_lastname'].' </h1>';
									$id = $row['member_id'];
						    echo '</div>';
						      echo '</div>';
						   
						    $summary = $row['summary'];

						    $exp= $row['experience'];
						}
					?>
		  	</div>
		  		
			<div class = "container">
				<hr>
			</div>
		<div class = "container">

			<form method="post" >
				<div class = "pull-right"><input class="button"  type="submit" id = "save" name="save" value="Save Changes"></div>
				<h1>SUMMARY</h1>
				<div>
					<label style="width:140px; float:left;"> </label><textarea class = "form-control" cols="87" rows = "8" name = "summary" id = "summary" ><?php echo $summary?></textarea>
				</div>	    
				<h1><br>EXPERIENCE</h1>
				<div>
					<label style="width:140px; float:left;"> </label><textarea class = "form-control" cols="87" rows = "8" name = "exp" id = "exp" ><?php echo $exp?></textarea>
				</div>		
				
			</form>	
		</div>
		    <?php include_once("footer.php"); ?> 
	</body>
</html>