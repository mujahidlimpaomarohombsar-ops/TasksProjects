<?php
require "auth.php";
require "db.php";
require "icons.php";
$uid = $_SESSION["user_id"];
// Labels shown to users for the status values stored in the database.
$statusLabels = ["todo" => "Pending", "in_progress" => "In progress", "done" => "Completed"];
function flash(string $msg, string $type = "success"): void { $_SESSION["flash"] = ["msg" => $msg, "type" => $type]; }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";
    $id = (int)($_POST["task_id"] ?? 0);
    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $due = ($_POST["due_date"] ?? "") ?: null;
    $priority = in_array($_POST["priority"] ?? "", ["Low", "Normal", "High"], true) ? $_POST["priority"] : "Normal";
    try {
        if ($action === "add") {
            if ($title === "") { flash("Enter a task title to add a task.", "error"); }
            else {
                $pdo->prepare("INSERT INTO tasks (user_id, title, description, due_date, priority) VALUES (?, ?, ?, ?, ?)")
                    ->execute([$uid, $title, $description, $due, $priority]);
                flash("Task added.");
            }
        } elseif ($action === "toggle") {
            $pdo->prepare("UPDATE tasks SET status = IF(status='done','todo','done') WHERE id=? AND user_id=?")->execute([$id, $uid]);
            flash("Task status updated.");
        } elseif ($action === "delete") {
            $pdo->prepare("DELETE FROM tasks WHERE id=? AND user_id=?")->execute([$id, $uid]);
            flash("Task deleted.");
        } elseif ($action === "edit") {
            if ($title === "") { flash("Enter a task title to save your changes.", "error"); }
            else {
                $pdo->prepare("UPDATE tasks SET title=?, description=?, due_date=?, priority=? WHERE id=? AND user_id=?")
                    ->execute([$title, $description, $due, $priority, $id, $uid]);
                flash("Changes saved.");
            }
        }
    } catch (PDOException $e) {
        flash("Unable to save your changes. Please try again.", "error");
    }
    header("Location: dashboard.php");
    exit;
}

$tasks = $pdo->query("SELECT tasks.*, users.name AS owner_name FROM tasks JOIN users ON users.id = tasks.user_id ORDER BY tasks.status='done', tasks.due_date IS NULL, tasks.due_date ASC, tasks.created_at DESC")->fetchAll();
$today = date("Y-m-d");
$isOverdue = fn($t) => $t["status"] !== "done" && $t["due_date"] && $t["due_date"] < $today;
$total = count($tasks);
$completed = count(array_filter($tasks, fn($t) => $t["status"] === "done"));
$pending = $total - $completed;
$overdue = count(array_filter($tasks, $isOverdue));
$flash = $_SESSION["flash"] ?? null;
unset($_SESSION["flash"]);
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | I-Task</title><link rel="stylesheet" href="css/style.css"><script src="js/app.js" defer></script></head>
<body>
<header class="topbar"><a class="brand" href="dashboard.php"><span class="brand-mark">I</span> I-Task</a>
<div class="user-menu"><span class="user-name"><?= htmlspecialchars($_SESSION["name"]) ?></span><a class="logout" href="logout.php"><?= icon("log-out", 20) ?> Sign out</a></div></header>
<main class="container">
<div class="page-heading"><h1>Task Dashboard</h1><p class="muted">Keep your work organized and on schedule.</p></div>
<?php if ($flash): ?><div class="alert <?= $flash["type"] === "error" ? "error" : "success" ?>" role="<?= $flash["type"] === "error" ? "alert" : "status" ?>"><?= icon($flash["type"] === "error" ? "circle-alert" : "circle-check") ?><span><?= htmlspecialchars($flash["msg"]) ?></span></div><?php endif; ?>
<section class="stats" aria-label="Task summary">
<div class="stat-card"><span class="stat-label"><i class="dot"></i>All tasks</span><strong><?= $total ?></strong></div>
<div class="stat-card"><span class="stat-label"><i class="dot pending"></i>Pending</span><strong><?= $pending ?></strong></div>
<div class="stat-card"><span class="stat-label"><i class="dot overdue"></i>Overdue</span><strong><?= $overdue ?></strong></div>
<div class="stat-card"><span class="stat-label"><i class="dot done"></i>Completed</span><strong><?= $completed ?></strong></div>
</section>
<section class="panel">
<h2>Add a task</h2><p class="muted">Enter the task details below.</p>
<form method="post" class="task-form">
<input type="hidden" name="action" value="add">
<div class="form-grid"><div class="field wide"><label for="addTitle">Task title</label><input id="addTitle" name="title" placeholder="e.g. Check computer laboratory" required></div>
<div class="field"><label for="addDue">Due date</label><input id="addDue" type="date" name="due_date"></div>
<div class="field"><label for="addPriority">Priority</label><select id="addPriority" name="priority"><option>Normal</option><option>High</option><option>Low</option></select></div>
<div class="field wide"><label for="addDesc">Description</label><textarea id="addDesc" name="description" rows="2" placeholder="Add a short note (optional)"></textarea></div></div>
<button class="btn" type="submit" data-loading="Adding task…"><?= icon("plus", 20) ?> Add task</button>
</form></section>
<section class="task-section"><div class="section-heading"><div><h2>All tasks</h2><span class="muted caption"><?= $total ?> total</span></div>
<div class="filters" role="group" aria-label="Filter tasks"><button type="button" class="filter-btn active" data-filter="all">All</button><button type="button" class="filter-btn" data-filter="pending">Pending</button><button type="button" class="filter-btn" data-filter="done">Completed</button></div></div>
<?php if (!$tasks): ?><div class="empty-state"><div class="empty-icon"><?= icon("check") ?></div><h3>No tasks yet</h3><p class="muted">Add your first task using the form above.</p></div>
<?php else: ?><div class="task-list">
<?php foreach ($tasks as $task):
    $done = $task["status"] === "done"; $late = $isOverdue($task); $mine = (int)$task["user_id"] === (int)$uid; ?>
<article class="task-card <?= $done ? "is-complete" : "" ?>" data-state="<?= $done ? "done" : "pending" ?>">
<div class="task-main"><?php if ($mine): ?><form method="post" class="inline-form"><input type="hidden" name="action" value="toggle"><input type="hidden" name="task_id" value="<?= $task["id"] ?>"><button class="check-btn" title="<?= $done ? "Mark as pending" : "Mark as completed" ?>" aria-label="<?= $done ? "Mark as pending" : "Mark as completed" ?>"><span class="circle"><?= $done ? icon("check") : "" ?></span></button></form><?php endif; ?>
<div class="task-content"><h3><?= htmlspecialchars($task["title"]) ?></h3><?php if ($task["description"]): ?><p><?= nl2br(htmlspecialchars($task["description"])) ?></p><?php endif; ?>
<div class="task-meta"><?php if ($task["due_date"]): ?><span class="due <?= $late ? "is-overdue" : "" ?>">Due <?= date("M j, Y", strtotime($task["due_date"])) ?></span><?php else: ?><span>No due date</span><?php endif; ?>
<?php if ($late): ?><span class="badge overdue">Overdue</span><?php endif; ?>
<span class="badge <?= strtolower($task["priority"]) ?>"><?= htmlspecialchars($task["priority"]) ?> priority</span><span class="badge status <?= $done ? "" : "pending" ?>"><?= htmlspecialchars($statusLabels[$task["status"]] ?? $task["status"]) ?></span><span>By <?= htmlspecialchars($task["owner_name"]) ?></span></div></div></div>
<?php if ($mine): ?><div class="task-actions"><button class="text-btn edit-btn" type="button" data-id="<?= $task["id"] ?>" data-title="<?= htmlspecialchars($task["title"], ENT_QUOTES) ?>" data-description="<?= htmlspecialchars($task["description"], ENT_QUOTES) ?>" data-due="<?= htmlspecialchars($task["due_date"] ?? "") ?>" data-priority="<?= htmlspecialchars($task["priority"]) ?>"><?= icon("pencil", 20) ?> Edit</button>
<form method="post" class="inline-form delete-form" data-title="<?= htmlspecialchars($task["title"], ENT_QUOTES) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="task_id" value="<?= $task["id"] ?>"><button class="text-btn danger delete-btn" type="button"><?= icon("trash", 20) ?> Delete</button></form></div>
<?php endif; ?>
</article>
<?php endforeach; ?><p class="no-match" id="noMatch" hidden>No tasks in this view.</p></div><?php endif; ?></section>
</main>

<div class="modal-backdrop" id="editModal" hidden><div class="modal" role="dialog" aria-modal="true" aria-labelledby="editHeading"><button class="modal-close" type="button" aria-label="Close"><?= icon("x") ?></button><h2 id="editHeading">Edit task</h2>
<form method="post"><input type="hidden" name="action" value="edit"><input type="hidden" name="task_id" id="editId">
<label for="editTitle">Task title</label><input name="title" id="editTitle" required>
<label for="editDescription">Description</label><textarea name="description" id="editDescription" rows="3"></textarea>
<label for="editDue">Due date</label><input type="date" name="due_date" id="editDue">
<label for="editPriority">Priority</label><select name="priority" id="editPriority"><option>Normal</option><option>High</option><option>Low</option></select>
<div class="modal-actions"><button class="btn btn-secondary cancel-edit" type="button">Cancel</button><button class="btn" type="submit" data-loading="Saving…">Save changes</button></div></form></div></div>

<div class="modal-backdrop" id="deleteModal" hidden><div class="modal" role="alertdialog" aria-modal="true" aria-labelledby="deleteHeading" aria-describedby="deleteText"><div class="warn-icon"><?= icon("alert") ?></div>
<h2 id="deleteHeading">Delete this task?</h2><p id="deleteText" class="muted" style="color:var(--text)"><span class="task-name" id="deleteName"></span><br>This can't be undone.</p>
<div class="modal-actions"><button class="btn btn-secondary" type="button" id="deleteCancel">Keep task</button><button class="btn btn-danger" type="button" id="deleteConfirm" data-loading="Deleting…">Delete task</button></div></div></div>
</body></html>
