<?php
require 'Database.php';

$pdo = Database::connect('127.0.0.1', 'php', 'test123', 'php_day5');

if (!$pdo) {
	exit('Database connection failed.');
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? '';

	if ($action === 'insert') {
		$name = trim($_POST['name'] ?? '');
		$email = trim($_POST['email'] ?? '');

		if ($name !== '' && $email !== '') {
			$message = Database::insert('users', [
				'name' => $name,
				'email' => $email,
			]) ? 'User inserted successfully.' : 'Insert failed.';
		}
	}

	if ($action === 'update') {
		$id = (int)($_POST['id'] ?? 0);
		$name = trim($_POST['name'] ?? '');
		$email = trim($_POST['email'] ?? '');

		if ($id > 0 && $name !== '' && $email !== '') {
			$message = Database::update('users', $id, [
				'name' => $name,
				'email' => $email,
			]) ? 'User updated successfully.' : 'Update failed.';
		}
	}

	if ($action === 'delete') {
		$id = (int)($_POST['id'] ?? 0);

		if ($id > 0) {
			$message = Database::delete('users', $id) ? 'User deleted successfully.' : 'Delete failed.';
		}
	}
}

$users = iterator_to_array(Database::select('users'), false);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Database Demo</title>
	<style>
		body { font-family: Arial, sans-serif; margin: 24px; background: #f6f7fb; }
		.card { background: #fff; border: 1px solid #ddd; border-radius: 10px; padding: 16px; margin-bottom: 16px; max-width: 720px; }
		label { display: block; margin-top: 10px; }
		input, button { padding: 8px; margin-top: 4px; width: 100%; box-sizing: border-box; }
		button { width: auto; cursor: pointer; }
		table { border-collapse: collapse; width: 100%; background: #fff; }
		th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
		.message { margin-bottom: 16px; color: #0a5; }
	</style>
</head>
<body>
	<h1>Database.php Demo</h1>

	<?php if ($message !== ''): ?>
		<div class="message"><?= htmlspecialchars($message) ?></div>
	<?php endif; ?>

	<div class="card">
		<h2>Insert User</h2>
		<form method="post">
			<input type="hidden" name="action" value="insert">
			<label>Name</label>
			<input type="text" name="name">
			<label>Email</label>
			<input type="email" name="email">
			<button type="submit">Insert</button>
		</form>
	</div>

	<div class="card">
		<h2>Update User</h2>
		<form method="post">
			<input type="hidden" name="action" value="update">
			<label>User ID</label>
			<input type="number" name="id">
			<label>Name</label>
			<input type="text" name="name">
			<label>Email</label>
			<input type="email" name="email">
			<button type="submit">Update</button>
		</form>
	</div>

	<div class="card">
		<h2>Delete User</h2>
		<form method="post">
			<input type="hidden" name="action" value="delete">
			<label>User ID</label>
			<input type="number" name="id">
			<button type="submit">Delete</button>
		</form>
	</div>

	<div class="card">
		<h2>All Users</h2>
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Email</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($users as $user): ?>
					<tr>
						<td><?= htmlspecialchars((string)$user['id']) ?></td>
						<td><?= htmlspecialchars($user['name']) ?></td>
						<td><?= htmlspecialchars($user['email']) ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</body>
</html>
