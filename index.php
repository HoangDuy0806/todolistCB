<?php
$dataFile = 'data.json';

function loadTasks() {
    global $dataFile;
    if (file_exists($dataFile)) {
        $json = file_get_contents($dataFile);
        return json_decode($json, true);
    }
    return [];
}

function saveTasks($tasks) {
    global $dataFile;
    file_put_contents($dataFile, json_encode($tasks, JSON_PRETTY_PRINT));
}

// Xử lý thêm công việc
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['new_task'])) {
    $tasks = loadTasks();
    $tasks[] = $_POST['new_task'];
    saveTasks($tasks);
    header("Location: index.php");
    exit();
}

// Xử lý xóa công việc
if (isset($_GET['delete'])) {
    $index = (int)$_GET['delete'];
    $tasks = loadTasks();
    if (isset($tasks[$index])) {
        array_splice($tasks, $index, 1);
        saveTasks($tasks);
    }
    header("Location: index.php");
    exit();
}

$tasks = loadTasks();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>TodoList PHP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>📝 Todo List PHP</h1>
        <form method="POST">
            <input type="text" name="new_task" placeholder="Nhập công việc..." required>
            <button type="submit">➕ Thêm</button>
        </form>

        <ul>
            <?php foreach ($tasks as $i => $task): ?>
                <li>
                    <?= htmlspecialchars($task) ?>
                    <a href="?delete=<?= $i ?>" onclick="return confirm('Bạn có chắc muốn xoá?')">❌</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
