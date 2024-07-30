<?php
// Create User
function createUser($username, $email, $password, $first_name, $last_name, $middle_name, $address, $picture, $verification_code) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name, middle_name, address, picture, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$username, $email, $password, $first_name, $last_name, $middle_name, $address, $picture, $verification_code])) {
        return true;
    } else {
        return false;
    }
}

// Read Users
function readUsers() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update User
function updateUser($id, $username, $email, $password, $first_name, $last_name, $middle_name, $address, $picture, $verification_code) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ?, first_name = ?, last_name = ?, middle_name = ?, address = ?, picture = ?, verification_code = ? WHERE id = ?");
    if ($stmt->execute([$username, $email, $password, $first_name, $last_name, $middle_name, $address, $picture, $verification_code, $id])) {
        return true;
    } else {
        return false;
    }
}

// Delete User
function deleteUser($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$id])) {
        return true;
    } else {
        return false;
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        case 'create':
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            if (createUser($_POST['username'], $_POST['email'], $password, $_POST['first_name'], $_POST['last_name'], $_POST['middle_name'], $_POST['address'], $_FILES['picture']['name'], $_POST['verification_code'])) {
                move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' . $_FILES['picture']['name']);
                echo json_encode(['success' => true, 'message' => 'User added successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error adding user.']);
            }
            exit;
        case 'update':
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            if (updateUser($_POST['id'], $_POST['username'], $_POST['email'], $password, $_POST['first_name'], $_POST['last_name'], $_POST['middle_name'], $_POST['address'], $_FILES['picture']['name'], $_POST['verification_code'])) {
                move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' . $_FILES['picture']['name']);
                echo json_encode(['success' => true, 'message' => 'User updated successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error updating user.']);
            }
            exit;
        case 'delete':
            if (deleteUser($_POST['id'])) {
                echo json_encode(['success' => true, 'message' => 'User deleted successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error deleting user.']);
            }
            header('Location: index.php?page=admin'); // Redirect after action
            exit;
    }
}

// Fetch users for display
$users = readUsers();
?>

<div class="container-fluid" style="margin-left: 0px!important;">
    <h1>Manage Admins</h1>

    <!-- User Table -->
    <table id="userTable" class="table table-bordered table-responsive-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Middle Name</th>
                <th>Address</th>
                <th>Picture</th>
                <th>Verification Code</th>
                <th>Created At</th>
                <th class="no-print">Actions</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            <?php
                $counter = 1; // Initialize counter variable
                foreach ($users as $user): ?>
                <tr>
                    <td><?= $counter++ ?></td>
                    <td><?= htmlentities($user['username']) ?></td>
                    <td><?= htmlentities($user['email']) ?></td>
                    <td><?= htmlentities($user['first_name']) ?></td>
                    <td><?= htmlentities($user['last_name']) ?></td>
                    <td><?= htmlentities($user['middle_name']) ?></td>
                    <td><?= htmlentities($user['address']) ?></td>
                    <td><img src="<?= 'uploads/' . htmlentities($user['picture']) ?>" alt="Picture" width="50"></td>
                    <td><?= htmlentities($user['verification_code']) ?></td>
                    <td><?= htmlentities($user['created_at']) ?></td>
                    <td class="no-print">
                        <div class="btn-group-vertical" role="group">
                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addUserModal">
                                <i class="fas fa-plus"></i> Add
                            </button>
                            <button class="btn btn-info btn-sm" onclick='openEditModal(<?= json_encode($user) ?>)'>
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser(<?= $user['id'] ?>)">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addUserForm" enctype="multipart/form-data" onsubmit="addUser(); return false;">
        <div class="modal-header">
          <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Add form fields here -->
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required>
          </div>
          <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required>
          </div>
          <div class="mb-3">
            <label for="middle_name" class="form-label">Middle Name</label>
            <input type="text" class="form-control" id="middle_name" name="middle_name" required>
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" required>
          </div>
          <div class="mb-3">
            <label for="picture" class="form-label">Picture</label>
            <input type="file" class="form-control" id="picture" name="picture">
          </div>
          <div class="mb-3">
            <label for="verification_code" class="form-label">Verification Code</label>
            <input type="text" class="form-control" id="verification_code" name="verification_code" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
