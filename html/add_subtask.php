<?php
    include 'config.php';

    $parent_todo = '';
    if(isset($_GET['id'])) {
        $parent_id = $_GET['id'];
        $stmt = $db->prepare("SELECT todo from todos WHERE id = :id");
        $stmt->bindValue(':id', $parent_id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        $parent_todo = $row['todo'];
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $todo = $_POST['todo'];
        $description = $_POST['description'];
        $deadline = $_POST['deadline'];
        $stmt = $db->prepare("INSERT INTO todos (todo, description, deadline, parent_id) VALUES (:todo, :description, :deadline, :parent_id)");
        $stmt->bindValue(':todo', $todo, SQLITE3_TEXT);
        $stmt->bindValue(':description', $description, SQLITE3_TEXT);
        $stmt->bindValue(':deadline', $deadline, SQLITE3_TEXT);
        $stmt->bindValue(':parent_id', $parent_id, SQLITE3_INTEGER);
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
    <h1>Add Sub-Task for "<?php echo htmlspecialchars($parent_todo, ENT_QUOTES, 'UTF-8'); ?>"</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . htmlspecialchars($parent_id, ENT_QUOTES, 'UTF-8');?>">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="todo">Sub-Task:</label><br>
        <input type="text" id="todo" name="todo" size="<?php echo $settings['text_box_length']; ?>" required><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="<?php echo $settings['description_box_height']; ?>" cols="<?php echo $settings['description_box_len']; ?>"></textarea><br>
        <label for="deadline">Deadline:</label><br>
        <input type="date" id="deadline" name="deadline"><br>
        <input type="submit" value="Add Sub-Task">
        <button onclick="window.location.href = 'index.php'; return false;">Cancel</button>
    </form>
</body>
</html>
