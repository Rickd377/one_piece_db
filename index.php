<?php
include 'db_conn.php';

$sql = "SELECT ep_number, ep_title, ep_type, ep_airdate, `ep_saga-arc` FROM episodes";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="./assets/tab_icon_black.ico" media="(prefers-color-scheme: light)"/>
  <link rel="icon" type="image/x-icon" href="./assets/tab_icon_white.ico" media="(prefers-color-scheme: dark)"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="./styles/dist/css/style.css">
  <title>One Piece Database | Home</title>
</head>
<body>

  <div class="input-search">
    <input type="text" id="search" placeholder="Search for episode #, title, type, airdate, saga/arc">
    <div class="filter"><i class="fa-solid fa-arrow-down-wide-short"></i></div>
  </div>

  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Title</th>
        <th>Type</th>
        <th>Airdate</th>
        <th>Saga - Arc</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['ep_number']) . "</td>";
              echo "<td>" . htmlspecialchars(ucwords($row['ep_title'])) . "</td>";
              echo "<td>" . htmlspecialchars(ucwords($row['ep_type'])) . "</td>";
              echo "<td>" . htmlspecialchars($row['ep_airdate']) . "</td>";
              echo "<td>" . htmlspecialchars(ucwords($row['ep_saga-arc'])) . "</td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='5'>No episodes found</td></tr>";
      }
      $conn->close();
      ?>
    </tbody>
  </table>
  
  <script src="./js/general.js"></script>
</body>
</html>