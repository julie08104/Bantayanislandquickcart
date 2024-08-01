<<<<<<< HEAD
<?php
require_once 'app/init.php';// Make sure to include your database connection file

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $middle_name = $_POST['middle_name'];
    $address = $_POST['address'];
    $verification_code = $_POST['verification_code'];

    // Handle file upload if picture is provided
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['picture']['tmp_name'];
        $fileName = $_FILES['picture']['name'];
        $fileSize = $_FILES['picture']['size'];
        $fileType = $_FILES['picture']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Specify the upload path
        $uploadFileDir = 'uploads/';
        $dest_path = $uploadFileDir . $fileName;

        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            $picture = $fileName;
        } else {
            $response['message'] = 'Error moving the uploaded file';
            echo json_encode($response);
            exit;
        }
    } else {
        $picture = null;
    }

    try {
        // Update query
        $sql = "UPDATE users SET username = ?, email = ?, password = ?, first_name = ?, last_name = ?, middle_name = ?, address = ?, verification_code = ?";
        if ($picture) {
            $sql .= ", picture = ?";
        }
        $sql .= " WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        
        // Bind parameters
        $params = [$username, $email, $password, $first_name, $last_name, $middle_name, $address, $verification_code];
        if ($picture) {
            $params[] = $picture;
        }
        $params[] = $id;

        if ($stmt->execute($params)) {
            $response['success'] = true;
            $response['message'] = 'User updated successfully';
        } else {
            $response['message'] = 'Error updating user';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>
=======
<?php
require_once 'app/init.php';// Make sure to include your database connection file

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $middle_name = $_POST['middle_name'];
    $address = $_POST['address'];
    $verification_code = $_POST['verification_code'];

    // Handle file upload if picture is provided
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['picture']['tmp_name'];
        $fileName = $_FILES['picture']['name'];
        $fileSize = $_FILES['picture']['size'];
        $fileType = $_FILES['picture']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Specify the upload path
        $uploadFileDir = 'uploads/';
        $dest_path = $uploadFileDir . $fileName;

        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            $picture = $fileName;
        } else {
            $response['message'] = 'Error moving the uploaded file';
            echo json_encode($response);
            exit;
        }
    } else {
        $picture = null;
    }

    try {
        // Update query
        $sql = "UPDATE users SET username = ?, email = ?, password = ?, first_name = ?, last_name = ?, middle_name = ?, address = ?, verification_code = ?";
        if ($picture) {
            $sql .= ", picture = ?";
        }
        $sql .= " WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        
        // Bind parameters
        $params = [$username, $email, $password, $first_name, $last_name, $middle_name, $address, $verification_code];
        if ($picture) {
            $params[] = $picture;
        }
        $params[] = $id;

        if ($stmt->execute($params)) {
            $response['success'] = true;
            $response['message'] = 'User updated successfully';
        } else {
            $response['message'] = 'Error updating user';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>
>>>>>>> 77727cd3e771a23579e82cf59139d13c2619b713
