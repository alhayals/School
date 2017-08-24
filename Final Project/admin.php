<?php require('classes/Database.php'); 
	include_once("classes/Session.php");
	global $db;
	//only admin has access
		$result = $db->query("SELECT * FROM members where member_id = '".$_SESSION['MEMBER_ID']."'");
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
             if(!$row['is_admin'])
		header("Location:/60334/jobs/");
 	}
?>

<?php include_once("header.php"); ?>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawPostsChart);

      function drawPostsChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day']
        <?php  
  $query  = "SELECT job_posts.member_id,members.member_firstname,members.member_lastname, COUNT(*) as cnt FROM job_posts INNER JOIN members on job_posts.member_id = members.member_id GROUP BY job_posts.member_id";
  $result = $db->query($query);
	 while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	     $name = $row['member_firstname']." ".$row['member_lastname'];
	    echo ",['".$name."',".$row['cnt']."]";
	}
        ?>
   
        ]);

        var options = {
          title: 'Posts'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart1'));

        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawPostsChart);

      function drawPostsChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day']
        <?php  
  $query  = "SELECT member_type, COUNT(*) as cnt FROM members GROUP BY member_type";
  $result = $db->query($query);
	 while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	 	if($row['member_type']==1)
	    	echo ",['Job Seeker',".$row['cnt']."]";
	    else
	    	echo ",['Job Reqruiter',".$row['cnt']."]";
	}
        ?>
   
        ]);

        var options = {
          title: 'Posts'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart2'));

        chart.draw(data, options);
      }
    </script>
<div class = "container">
  			<p>Users</p>
			<table class="table">
			    <thead>
			      <tr>
			      	<th>Username</th>
			        <th>Firstname</th>
			        <th>Lastname</th>
			        <th>ID</th>
			        <th>Email</th>
			        <th>Company</th>
			        <th>Role</th>
			      </tr>
			    </thead>
			    <tbody>
			<?php $sql  = "SELECT * FROM members";
		  	$result = $db->query($sql);
		  	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
		  	 	echo '<tr>';
			    echo  '<td>'.$row['member_username'].'</td>';
			    echo  '<td>'.$row['member_firstname'].'</td>';
			    echo  '<td>'.$row['member_lastname'].'</td>';
			    echo  '<td>'.$row['member_id'].'</td>';
			    echo  '<td>'.$row['member_email'].'</td>';
			    echo  '<td>'.$row['member_organization'].'</td>';
			      echo  '<td>';
			      if($row['member_type']==1)
			      	echo "Job Reqruiter";
			      else echo"Job Seeker";
			      	echo '</td>';
			   	//  echo  '<td><a href="deleteuser.php?id='.$row['member_id'].'" onclick="return confirm(\'Are you sure you want to delete?\')" >Delete</a></td>';
			    echo  '</tr>';
			}
			?>
			    </tbody>
			  </table>
		</div>
		<div class = "container">
		<div class ="row">
			<div class = "col-md-6">
		  <div id="piechart1" style="width: 100%; height: 500px;"></div>
		</div>
			<div class = "col-md-6">
		 <div id="piechart2" style="width: 100%; height: 500px;"></div>
		</div>
	</div>
		</div>	<?php include_once("footer.php"); ?>
</body>
</html>