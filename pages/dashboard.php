<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: ../login.php");
  exit();
}
include '../includes/db.php';

$user_id = $_SESSION['user_id'];

// Add task
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['task_name'])) {
  $task = mysqli_real_escape_string($conn, $_POST['task_name']);
  $due = !empty($_POST['due_date']) ? $_POST['due_date'] : "NULL";
  $sql = "INSERT INTO tasks (user_id, task_name, due_date) VALUES ($user_id, '$task', ".($due !== "NULL" ? "'$due'" : "NULL").")";
  mysqli_query($conn, $sql);
}

// Delete task
if (isset($_GET['delete'])) {
  $delete_id = (int) $_GET['delete'];
  mysqli_query($conn, "DELETE FROM tasks WHERE id=$delete_id AND user_id=$user_id");
  header("Location: dashboard.php");
  exit();
}

// Fetch tasks
$result = mysqli_query($conn, "SELECT * FROM tasks WHERE user_id=$user_id ORDER BY created_at DESC");
?>

<h2>Welcome, <?php echo $_SESSION['user']; ?> 👋</h2>
<a href="../logout.php">Logout</a>

<!-- Task Form -->
<form method="POST">
  <input type="text" name="task_name" placeholder="New Task" required>
  <input type="date" name="due_date">
  <button type="submit">Add Task</button>
</form>

<!-- Task List -->
<ul>
<?php while ($row = mysqli_fetch_assoc($result)) : ?>
  <li>
    <strong><?php echo htmlspecialchars($row['task_name']); ?></strong>
    <?php if ($row['due_date']) echo " (Due: " . $row['due_date'] . ")"; ?>
    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this task?')">❌</a>
  </li>
<?php endwhile; ?>
</ul>
