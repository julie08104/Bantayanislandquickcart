<?php
// Ensure this file includes PDO and User class initialization

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize User object with PDO
    $Ouser = new User($pdo);

    // Check if user is logged in
    if (!$Ouser->is_login()) {
        $response['message'] = 'You must be logged in to update your profile.';
        echo json_encode($response);
        exit();
    }

    // Fetch user details
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // Handle avatar upload
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $avatar_tmp_name = $_FILES['avatar']['tmp_name'];
        $avatar_name = basename($_FILES['avatar']['name']);
        $avatar_path = 'uploads/' . $avatar_name;

        // Move uploaded file to the desired directory
        if (move_uploaded_file($avatar_tmp_name, $avatar_path)) {
            // Update database with the new avatar file name
            $stmt = $pdo->prepare("UPDATE users SET first_name = :name, last_name = :lastname, email = :email, address = :address, picture = :picture WHERE id = :id");
            $stmt->bindParam(":picture", $avatar_name, PDO::PARAM_STR);
        } else {
            $response['message'] = "Failed to upload avatar.";
            echo json_encode($response);
            exit();
        }
    } else {
        $stmt = $pdo->prepare("UPDATE users SET first_name = :name, last_name = :lastname, email = :email, address = :address WHERE id = :id");
    }

    $stmt->bindParam(":name", $name, PDO::PARAM_STR);
    $stmt->bindParam(":lastname", $lastname, PDO::PARAM_STR);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->bindParam(":address", $address, PDO::PARAM_STR);
    $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Profile updated successfully.";
    } else {
        $response['message'] = "Failed to update profile.";
    }

    echo json_encode($response);
    exit();
}

// Initialize User object with PDO for fetching user details
$Ouser = new User($pdo);

// Check if user is logged in
if (!$Ouser->is_login()) {
    header("Location: login.php");
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        .avatar {
            max-width: 100px;
            max-height: 150px;
            border-radius: 50%;
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <br>
    <div class="container mt-5">
        <h2>Admin Profile</h2>

        <?php if (isset($_SESSION['update_success'])): ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['update_success'];
                unset($_SESSION['update_success']);
                ?>
            </div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Display avatar -->
        <div class="text-center">
            <?php if ($user['picture']): ?>
                <center><img src="uploads/<?php echo htmlspecialchars($user['picture']); ?>" alt="Avatar" class="avatar">  </center>
            <?php else: ?>
               <center><img src="uploads/images2.png" alt="Avatar" class="avatar"> </center>
            <?php endif; ?>
        </div>
                     <form id="updateProfileForm" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">First Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="lastname">Last Name</label>
                                <input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
        <!-- Button to open the modal -->
        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#profileModal">
            Update Profile
        </button>

        <!-- Modal -->
        <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="profileModalLabel">Update Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateProfileForm" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">First Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="lastname">Last Name</label>
                                <input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="form-group">
                            <label for="address">Address</label>
                            <textarea name="address" id="address" class="form-control" rows="4" required><?php echo htmlspecialchars($user['address'] ?? ''); ?>
</textarea>

                           <!--  <div class="form-group">
                                <label for="avatar">Avatar</label>
                                <input type="file" name="avatar" id="avatar" class="form-control">
                            </div> -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert and AJAX Submission -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('updateProfileForm');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(form);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Profile Updated!',
                        text: 'Your profile has been updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload(); // Reload the page
                    });
                } else {
                    Swal.fire({
                        title: 'Update Failed',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Update Failed',
                    text: 'An error occurred while updating your profile.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });
    });
    </script>
