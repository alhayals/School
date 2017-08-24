<?php 
global $db;
$user_id = $_SESSION['MEMBER_ID'];
     /* $result = mysql_query("SELECT COUNT(comment) AS num FROM comments INNER JOIN posts on comments.postid = posts.postid WHERE posts.username = '".$username."' AND comments.read = 0");
      while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $notifications = $row['num'];
      }
      $result = mysql_query("SELECT COUNT(message) AS num FROM privatemessages WHERE recipient  = '".$username."' AND privatemessages.read = 0");
      while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $unreadmessages = $row['num'];
      }*/
 
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Job Seek</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="css/style.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/tinymce.min.js"></script>
  <link href='http://fonts.googleapis.com/css?family=Lato:400,600,700,800,300' rel='stylesheet' type='text/css'>
</head>
 <nav class="navbar navbar-default">
        <div class="container-fluid" id="header-wrap">
          <div class="container">
          <div class="navbar-header " >
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <!--<a class="navbar-brand  img-responsive" href="http://www.experiencedmg.com/forum"><img class = "headerimg" src="images/dmgInternalLogo.png" ></a>-->
          </div>  
          <div id="navbar" class="navbar-collapse collapse ">
          	  <!--<div id="account-menu">Register | Login</div><br />-->
            <ul class="nav navbar-nav navbar-right">
              <li><a href = "index.php">HOME</a></li>
          		<?php global $db;
            			$result = $db->query("SELECT * FROM members where member_id = '".$_SESSION['MEMBER_ID']."'");
            		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
             			if($row['is_admin']) echo '<li><a href="admin.php">ADMIN</a></li>';
          		}?>	
              <li><a href="profile.php">PROFILE</a></li>

          		<li><a href="logout.php">LOGOUT</a></li>
            </ul>
            
          </div><!--/.nav-collapse -->
        </div>
        </div><!--/.container-fluid -->
      </nav>
        <!-- /.container-fluid --> 
	</div>

</div>
</div>

<div class="headpanel">
	<div class = "headpanelText">
		<h1> JOB SEEK</h1>
		<p>ESTABLISH NEW CONNECTIONS</p>
	</div>	
</div>
