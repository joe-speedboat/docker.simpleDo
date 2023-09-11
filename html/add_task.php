<?php
    include 'config.php';

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $todo = $_POST['todo'];
        $description = $_POST['description'];
        $deadline = $_POST['deadline'];
        $stmt = $db->prepare("INSERT INTO todos (todo, description, deadline) VALUES (:todo, :description, :deadline)");
        $stmt->bindValue(':todo', $todo, SQLITE3_TEXT);
        $stmt->bindValue(':description', $description, SQLITE3_TEXT);
        $stmt->bindValue(':deadline', $deadline, SQLITE3_TEXT);
        $stmt->execute();
        header('Location: index.php');
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Add New Task</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="todo">Task:</label><br>
        <input type="text" id="todo" name="todo" size="<?php echo $settings['text_box_length']; ?>" required><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="<?php echo $settings['description_box_height']; ?>" cols="<?php echo $settings['description_box_len']; ?>"></textarea><br>
        <label for="deadline">Deadline:</label><br>
        <input type="date" id="deadline" name="deadline"><br>
        <input type="submit" value="Add Task">
        <button onclick="window.location.href = 'index.php'; return false;">Cancel</button>
    </form>
</body>
</html>
