<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../utils/config.php';
require '../controllers/db_controller.php';
require '../utils/validators.php';

$pdo = connect_db();
if (!$pdo) {
    echo 'Database connection failed.';
    exit;
}

$edit_user = null;

if (isset($_GET['action']) && $_GET['action'] === 'delete' && !empty($_GET['id'])) {
    delete_user($pdo, (int)$_GET['id']);
    header('Location: users_table.php'); // refresh
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'edit' && !empty($_GET['id'])) {
    $edit_user = get_user_by_id($pdo, (int)$_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $id = (int)($_POST['id'] ?? 0);
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $room_no = $_POST['Room_No'] ?? '';
    $ext = $_POST['Ext.'] ?? '';
    $current_password = $_POST['current_password'] ?? '';
    $current_profile_picture = $_POST['current_profile_picture'] ?? '';

    if (validate_empty($name, 'Name') || validate_empty($email, 'Email') || validate_email($email)) {
        echo '<div class="alert alert-danger">Please fix the name and email fields.</div>';
    } else {
        $profile_picture = $current_profile_picture;

        if (!empty($_FILES['profile_picture']['name'])) {
            $uploaded_file = $_FILES['profile_picture'];
            move_uploaded_file($uploaded_file['tmp_name'], '../uploads/' . $uploaded_file['name']);
            $profile_picture = $uploaded_file['name'];
        }

        if (edit_user($pdo, $id, $name, $email, $current_password, $room_no, $ext, $profile_picture)) {
            header('Location: users_table.php');
            exit;
        }

        echo '<div class="alert alert-danger">Failed to update user.</div>';
    }

    $edit_user = get_user_by_id($pdo, $id);
}

$users = get_all_users($pdo) ?: [];
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-3">
    <div class="container">
        <h2 class="mb-3">Users</h2>
        <a href="../main.php" class="btn btn-sm btn-secondary mb-3">Back</a>

        <?php if ($edit_user): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-3">Edit User #<?= (int)$edit_user['id'] ?></h3>
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= (int)$edit_user['id'] ?>">
                        <input type="hidden" name="current_password" value="<?= htmlspecialchars($edit_user['password']) ?>">
                        <input type="hidden" name="current_profile_picture" value="<?= htmlspecialchars($edit_user['profile_picture']) ?>">
                        <input type="hidden" name="update_user" value="1">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="name">Name</label>
                                <input class="form-control" type="text" id="name" name="name" value="<?= htmlspecialchars($edit_user['name']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control" type="email" id="email" name="email" value="<?= htmlspecialchars($edit_user['email']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="Room_No">Room No</label>
                                <select class="form-select" id="Room_No" name="Room_No">
                                    <option value="101" <?= $edit_user['room_no'] === '101' ? 'selected' : '' ?>>Application1</option>
                                    <option value="102" <?= $edit_user['room_no'] === '102' ? 'selected' : '' ?>>Application2</option>
                                    <option value="103" <?= $edit_user['room_no'] === '103' ? 'selected' : '' ?>>Cloud</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="Ext.">Ext.</label>
                                <input class="form-control" type="text" id="Ext." name="Ext." value="<?= htmlspecialchars($edit_user['ext']) ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="profile_picture">Profile Picture</label>
                                <input class="form-control" type="file" id="profile_picture" name="profile_picture">
                                <?php if (!empty($edit_user['profile_picture'])): ?>
                                    <div class="mt-2">
                                        <small class="text-muted">Current file: <?= htmlspecialchars($edit_user['profile_picture']) ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button class="btn btn-primary" type="submit">Update</button>
                            <a href="users_table.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <?php if (count($users) === 0): ?>
            <div class="alert alert-info">No users found.</div>
        <?php else: ?>
            <table class="table table-sm table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Room</th>
                        <th>Ext</th>
                        <th>Photo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['room_no']) ?></td>
                            <td><?= htmlspecialchars($user['ext']) ?></td>
                            <td>
                                <?php if (!empty($user['profile_picture'])): ?>
                                    <img src="<?= htmlspecialchars('../uploads/' . basename($user['profile_picture'])) ?>" alt="photo" style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:1px solid #ddd">
                                <?php else: ?>
                                    <span class="text-muted small">No image</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="users_table.php?action=edit&id=<?= (int)$user['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="users_table.php?action=delete&id=<?= (int)$user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>