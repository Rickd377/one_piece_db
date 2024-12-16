<?php
include 'db_conn.php';

$passcode = 'Hallo123!';
$cookie_name = "admin_passcode";
$cookie_value = "validated";
$cookie_duration = 86400; // 1 day in seconds

if (isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] === $cookie_value) {
    $passcode_validated = true;
} else {
    $passcode_validated = false;
}
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
  <title>One Piece Database | Admin</title>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var passcodeValidated = <?php echo json_encode($passcode_validated); ?>;
      if (!passcodeValidated) {
        var passcode = prompt("Please enter the passcode:");
        if (passcode !== "<?php echo $passcode; ?>") {
          document.body.innerHTML = "Access denied. Invalid passcode.";
        } else {
          document.cookie = "<?php echo $cookie_name; ?>=<?php echo $cookie_value; ?>; max-age=<?php echo $cookie_duration; ?>; path=/";
          document.getElementById("admin-content").style.display = "block";
        }
      } else {
        document.getElementById("admin-content").style.display = "block";
      }
    });
  </script>
</head>
<body>
  <div id="admin-content" style="display:none;">
    <?php
    $message = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['number'])) {
        $number = $_POST['number'];
        $title = $_POST['title'];
        $type = $_POST['type'];
        $airdate = $_POST['airdate'];
        $saga = $_POST['saga'];

        // Get the lowest available ID
        $id = getLowestAvailableId($conn);

        $sql = "INSERT INTO episodes (id, ep_number, ep_title, ep_type, ep_airdate, `ep_saga-arc`) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissss", $id, $number, $title, $type, $airdate, $saga);

        if ($stmt->execute()) {
            $message = "New episode added successfully";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
        $conn->close();
    }
    ?>
    <form action="admin.php" method="post" class="admin-form">
      <h2>Add an episode</h2>
      <a href="index.php" class="link-to-home">Back to home</a>
      <p class="message"><?php echo htmlspecialchars($message); ?></p>
      <input type="text" name="number" id="number" placeholder="Number..." required autocomplete="off">
      <input class="title" type="text" name="title" id="title" placeholder="Title..." required>
      <input type="text" name="type" id="type" placeholder="Type..." required>
      <input type="text" name="airdate" id="airdate" placeholder="Airdate..." required autocomplete="off">
      <input type="text" name="saga" id="saga" placeholder="Saga/Arc..." required>
      <button type="submit" class="submit-add">Submit</button>
    </form>
    <br>
    <form action="edit.php" method="get" class="admin-form">
      <h2>Edit episode</h2>
      <input type="text" name="number" id="number" placeholder="Episode number..." required autocomplete="off">
      <button type="submit" class="submit-edit">Submit</button>
    </form>
  </div>
  
  <script src="./js/general.js"></script>
</body>
</html>