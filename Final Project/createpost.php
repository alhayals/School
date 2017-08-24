<?php
	include_once("classes/Session.php");
	include_once("classes/Database.php");
	include_once("classes/Member.php");
	include_once("classes/Data.php");
	global $db;
	if(!$session->is_logged_in()){
		header("Location: login.php");
	}
	$tags = "";
	$sql  = "SELECT * FROM members WHERE member_id = '".$_SESSION['MEMBER_ID']."' LIMIT 1";
	$result = $db->query($sql);
	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
		$name= $row['member_username'];
		$company = $row['member_organization'];
	}

?>


</head>
<body>

	<!--header-->
	<?php include_once("header.php"); ?>
	<script>
 $(document).ready(function() {
	$('#post').click(function()
		{
			var tags = " ";
			if (document.getElementById('general').checked){
				tags = tags.concat("general");
			}
			if (document.getElementById('accounting').checked){
				tags =tags.concat("accounting");
			}
			if (document.getElementById('engineering').checked){
				tags =tags.concat("engineering");
			}
			if (document.getElementById('computerscience').checked){
				tags =tags.concat("computerscience");
			}if (document.getElementById('retail').checked){
				tags =tags.concat("retail");
			}
			tinymce.triggerSave();
			$.ajax({
			    url : "publish.php",
			    type: "POST",
			    data : {title:$("#title").val(), description:$("#description").val(), location:$("#location").val(), requirements:$("#requirements").val(),salary:$("#salary").val(), tag: tags},
			    success: function(data,status, xhr)
			    {
       				 $('p.error').text(data);
       				 if (data=="success"){
       				 	window.location = "http://alhayals.myweb.cs.uwindsor.ca/60334/jobs/";
       				 }

			    },
			    error: function (jqXHR, status, errorThrown)
			    {
			    }
			});
		});
	});
	
	</script>
	<!--end header-->	
		<div class = "container submitform">
			<form  action="" method="post" >
				<div class = "row">
						

					<div class = "col-md-9">
						Title: <input type="text" id="title" name="title" cols="0" rows="3"/>
						Description: <textarea class = "projectcontent" rows="10" name="description" id = "description" ></textarea><Br>
						Location: <input id = "location" type = "text"><br>
						Requirements: <textarea class = "projectcontent" rows="10" name="requirements" id = "requirements" ></textarea><br>
						Salary: <input type = "text" id = "salary"><br>
					</div>
					<div class = "col-md-3">
						<p>Tag Your Post</p>
						<div class="taglist">
						    <input type="checkbox" value="general" id="general" 	name = "category[]" /> General <br />
						    <input type="checkbox" value="accounting" id="accounting" name = "category[]"/> Accounting <br />
						    <input type="checkbox" value="engineering" id="engineering"  name = "category[]"/> Engineering <br />
						    <input type="checkbox" value="computerscience" id="computerscience" name = "category[]"/> Computer Science <br />
						    <input type="checkbox" value="retail" id="retail" name = "category[]"/> Retail <br />
						</div>
					</div>
				</div>
	

				<div class = "row">
					<div class = "col-md-12">
						<p class = "error" ></p>
						<p><a class="button" style = "margin-top:30px; font-size:15px;" type="submit" id = "post" name="post">SUBMIT</a></p>
					</div>
				</div>
			</form>		 
		</div>
		<?php include_once("footer.php"); ?>
	</body>
</html>