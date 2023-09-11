<?php
    include 'config.php';

    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $db->prepare("SELECT t1.*, t2.todo as parent_todo from todos t1 LEFT JOIN todos t2 ON t1.parent_id = t2.id WHERE t1.id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $todo = $_POST['todo'];
        $description = $_POST['description'];
        $deadline = $_POST['deadline'];
        $stmt = $db->prepare("UPDATE todos SET todo = :todo, description = :description, deadline = :deadline WHERE id = :id");
        $stmt->bindValue(':todo', $todo, SQLITE3_TEXT);
        $stmt->bindValue(':description', $description, SQLITE3_TEXT);
        $stmt->bindValue(':deadline', $deadline, SQLITE3_TEXT);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
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
    <h1>Edit Todo</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8');?>">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="parent_todo">Parent Task:</label><br>
        <input type="text" id="parent_todo" name="parent_todo" value="<?php echo htmlspecialchars($row['parent_todo'], ENT_QUOTES, 'UTF-8'); ?>" size="<?php echo $settings['description_box_len']; ?>" readonly><br>
        <label for="todo">Task:</label><br>
        <input type="text" id="todo" name="todo" value="<?php echo htmlspecialchars($row['todo'], ENT_QUOTES, 'UTF-8'); ?>" size="<?php echo $settings['description_box_len']; ?>"><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="<?php echo $settings['description_box_height']; ?>" cols="<?php echo $settings['description_box_len']; ?>"><?php echo htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8'); ?></textarea><br>
        <label for="deadline">Deadline:</label><br>
        <input type="date" id="deadline" name="deadline" value="<?php echo htmlspecialchars($row['deadline'], ENT_QUOTES, 'UTF-8'); ?>"><br>
        <input type="submit" value="Update Task">
        <button onclick="window.location.href = 'add_subtask.php?id=' + <?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8');?>; return false;">Add Sub-Task</button>
        <button onclick="window.location.href = 'index.php'; return false;">Cancel</button>
    </form>
</body>
</html>
