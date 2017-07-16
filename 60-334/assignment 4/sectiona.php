<?php // sectiona.php
  $hn = "localhost";
  $un = "alhayals_assign4";
  $pw = "password";
  $db = "alhayals_assign4";
  $conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);
?>


<!DOCTYPE html>
<Html>

<body>

  <form action="sectiona.php" method="post"><pre>
    First name: <input type="text" name="fname">
    Last name:  <input type="text" name="lname">
    User Type:  <select name = "user_type">
<?php
     $query  = "SELECT * FROM User_codes";
  $result = $conn->query($query);
  if (!$result) die ("Database access failed: " . $conn->error);

  $rows = $result->num_rows;
  
  for ($j = 0 ; $j < $rows ; ++$j)
  {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);
 
    echo "<option value = \"".$row[0]."\">".$row[1]."</option>";

  }
  ?>
</select>
    Email:      <input type="email" name="email">
    Password:   <input type="password" name="password">
                <input type="submit" value="Submit">
  </form>

<?php
 
  if (isset($_POST['fname'])   &&
      isset($_POST['lname'])    &&
      isset($_POST['user_type']) &&
      isset($_POST['email'])     &&
      isset($_POST['password']))
  {
    $fname     = get_post($conn, 'fname');
    $lname     = get_post($conn, 'lname');
    $user_type = get_post($conn, 'user_type');
    $email     = get_post($conn, 'email');
    $password  = get_post($conn, 'password');
    $query     = "INSERT INTO user_profiles(fname, lname, usercode, email, password) VALUES" .
      "('$fname', '$lname', '$user_type', '$email', '$password')";
    $result   = $conn->query($query);

    if (!$result) echo "INSERT failed: $query<br>" .$conn->error . "<br><br>";
        else{ echo "Insert Successful";}
  }


  
  $result->close();
  $conn->close();
  
  function get_post($conn, $var)
  {

    return $conn->real_escape_string($_POST[$var]);
  }
?>


</body>
</html>
