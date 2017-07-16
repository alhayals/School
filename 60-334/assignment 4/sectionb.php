<?php // sectionb.php
  $hn = "localhost";
  $un = "alhayals_pbl";
  $pw = "mypassword";
  $db = "alhayals_pbl";
  $conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);
?>
<html>
  <head>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day']
        <?php  
  $query  = "SELECT `category`, COUNT(*) as cnt FROM `classics` GROUP BY `category`";
  $result = $conn->query($query);
  if (!$result) die ("Database access failed: " . $conn->error);
  $rows = $result->num_rows;
  for ($j = 0 ; $j < $rows ; ++$j)
  {
     $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);
    echo ",['".$row[0]."',".$row[1]."]";
}
        ?>
        
   
        ]);

        var options = {
          title: 'My Daily Activities'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
  </body>
</html>