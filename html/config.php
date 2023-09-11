<?php
    session_start();

    include 'settings.php';

    class MyDB extends SQLite3 {
        function __construct() {
            global $settings;
            $this->open($settings['db_file_name']);
        }
    }
    $db = new MyDB();
    if(!$db) {
        echo $db->lastErrorMsg();
    } else {
        $sql =<<<EOF
            CREATE TABLE IF NOT EXISTS todos
            (id INTEGER PRIMARY KEY AUTOINCREMENT,
            todo TEXT NOT NULL,
            description TEXT,
            deadline DATE,
            parent_id INTEGER);
EOF;
        $ret = $db->exec($sql);
        if(!$ret){
            echo $db->lastErrorMsg();
        }
    }

    if(empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            die('Invalid CSRF token');
        }
    }

    if(isset($_GET['get'])) {
        $id = $_GET['get'];
        $stmt = $db->prepare("SELECT * from todos WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        echo json_encode($row);
        exit();
    }
?>
