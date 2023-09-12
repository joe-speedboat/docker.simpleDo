<?php
    include 'config.php';

    if(isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $todo = $_GET['todo'];
        $deadline = $_GET['deadline'];
        $stmt = $db->prepare("UPDATE todos SET todo = :todo, deadline = :deadline WHERE id = :id");
        $stmt->bindValue(':todo', $todo, SQLITE3_TEXT);
        $stmt->bindValue(':deadline', $deadline, SQLITE3_TEXT);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->execute();
    }

    if(isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $stmt = $db->prepare("DELETE from todos WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->execute();
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $todo = $_POST['todo'];
        $description = $_POST['description'];
        $deadline = $_POST['deadline'];
        $deadline = empty($deadline) ? NULL : $deadline;
        $stmt = $db->prepare("INSERT INTO todos (todo, description, deadline) VALUES (:todo, :description, :deadline)");
        $stmt->bindValue(':todo', $todo, SQLITE3_TEXT);
        $stmt->bindValue(':description', $description, SQLITE3_TEXT);
        $stmt->bindValue(':deadline', $deadline, SQLITE3_TEXT);
        $stmt->execute();
    }

    // Code for sorting by id removed

    function fetchTasks($parent_id = null, $indent = 0) {
        global $db, $settings;
        $query = "SELECT * from todos WHERE ";
        if ($parent_id === null) {
            $query .= "parent_id IS NULL ";
        } else {
            $query .= "parent_id = :parent_id ";
        }
        $stmt = $db->prepare($query);
        if ($parent_id !== null) {
            $stmt->bindValue(':parent_id', $parent_id, SQLITE3_INTEGER);
        }
        $result = $stmt->execute();
        while($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $stmt2 = $db->prepare("SELECT COUNT(*) as count from todos WHERE parent_id = :parent_id");
            $stmt2->bindValue(':parent_id', $row['id'], SQLITE3_INTEGER);
            $result2 = $stmt2->execute();
            $row2 = $result2->fetchArray(SQLITE3_ASSOC);
            echo '<tr>';
            echo '<td style="padding-left: ' . (10 + $indent * 30) . 'px">';
            echo htmlspecialchars($row['todo'], ENT_QUOTES, 'UTF-8') . '</td>';
            $deadline = new DateTime($row['deadline']);
            $now = new DateTime();
            if ($deadline < $now) {
                $color = $settings['prio1'];
            } else {
                $interval = $now->diff($deadline)->days;
                if ($interval <= $settings['days1']) {
                    $color = $settings['prio1'];
                } elseif ($interval <= $settings['days2']) {
                    $color = $settings['prio2'];
                } elseif ($interval <= $settings['days3']) {
                    $color = $settings['prio3'];
                } else {
                    $color = 'black';
                }
            }
            echo '<td style="color: ' . $color . ';">' . htmlspecialchars($row['deadline'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>';
            echo '<button onclick="editTodo(' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . ')">Edit</button>';
            echo '<button onclick="deleteTodo(' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . ')">Delete</button>';
            echo '</td>';
            echo '</tr>';
            fetchTasks($row['id'], $indent + 1);
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="content">
        <button onclick="window.location.href = 'add_task.php';">Add New Task</button>
    </div>
    <h2>Current Todos</h2>
    <table id="todoTable">
        <tr>
            <th>Task</th>
            <th>Due Date</th>
            <th>Actions</th>
        </tr>
        <?php fetchTasks(); ?>
    </table>
    <script src="main.js"></script>
    <div class="content">
        <p style="color: <?php echo $settings['prio1']; ?>;">Days until deadline: less than <?php echo $settings['days1']; ?> days</p>
        <p style="color: <?php echo $settings['prio2']; ?>;">Days until deadline: less than <?php echo $settings['days2']; ?> days</p>
        <p style="color: <?php echo $settings['prio3']; ?>;">Days until deadline: less than <?php echo $settings['days3']; ?> days</p>
    </div>
</body>
</html>
