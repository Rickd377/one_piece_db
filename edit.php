<?php
include 'db_conn.php';

$number = $_GET['number'] ?? '';
$message = '';

if ($number) {
    $sql = "SELECT ep_number, ep_title, ep_type, ep_airdate, `ep_saga-arc` FROM episodes WHERE ep_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $number);
    $stmt->execute();
    $result = $stmt->get_result();
    $episode = $result->fetch_assoc();

    if (!$episode) {
        $message = "Episode not found";
    }

    $stmt->close();
} else {
    $message = "No episode number provided";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        // Delete the episode
        $sql = "DELETE FROM episodes WHERE ep_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $number);
        if ($stmt->execute()) {
            $message = "Episode deleted successfully";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
        $stmt->close();
    } else {
        // Update the episode
        $number = $_POST['number'];
        $title = $_POST['title'];
        $type = $_POST['type'];
        $airdate = $_POST['airdate'];
        $saga = $_POST['saga'];

        $sql = "UPDATE episodes SET ep_number = ?, ep_title = ?, ep_type = ?, ep_airdate = ?, `ep_saga-arc` = ? WHERE ep_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssi", $number, $title, $type, $airdate, $saga, $number);
        if ($stmt->execute()) {
            $message = "Episode updated successfully";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
        $stmt->close();
    }
    $conn->close();
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
  <title>One Piece Database | Edit</title>
</head>
<body>

  <form action="edit.php?number=<?php echo htmlspecialchars($number); ?>" method="post" class="admin-form">
    <h2>Edit episode <?php echo htmlspecialchars($number); ?></h2>
    <a href="index.php" class="link-to-home">Back to home</a>
    <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <input type="text" name="number" id="number" placeholder="Number..." required autocomplete="off" value="<?php echo htmlspecialchars($episode['ep_number'] ?? ''); ?>">
    <input class="title" type="text" name="title" id="title" placeholder="Title..." required value="<?php echo htmlspecialchars($episode['ep_title'] ?? ''); ?>">
    <input type="text" name="type" id="type" placeholder="Type..." required value="<?php echo htmlspecialchars($episode['ep_type'] ?? ''); ?>">
    <input type="text" name="airdate" id="airdate" placeholder="Airdate..." required autocomplete="off" value="<?php echo htmlspecialchars($episode['ep_airdate'] ?? ''); ?>">
    <input type="text" name="saga" id="saga" placeholder="Saga/Arc..." required value="<?php echo htmlspecialchars($episode['ep_saga-arc'] ?? ''); ?>">
    <button type="submit" class="submit-add">Save changes</button>
    <button type="submit" name="delete" class="submit-delete">Delete episode</button>
  </form>
  
  <script src="./js/general.js"></script>
</body>
</html>