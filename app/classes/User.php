<?php
// Enable detailed error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering
ob_start();

// Ensure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class User extends Objects {
    protected $pdo;

    // Constructor to initialize PDO connection
    function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // User login method
    public function login($username, $pass) {
        // Prepare the SQL statement
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");

        // Bind parameters
        $stmt->bindParam(':username', $username);

        // Execute the query
        $stmt->execute();

        // Fetch user data
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if ($user) {
            // Verify password
            if (password_verify($pass, $user->password)) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_role'] = $user->user_role;  // Assuming 'user_role' is the column name for user role
                $this->redirect("index.php");
            } else {
                $_SESSION['login_error'] = "Invalid Password";
                $this->redirect("login.php");
            }
        } else {
            $_SESSION['login_error'] = "User not found";
            $this->redirect("login.php");
        }
    }

    // Check if user is an admin
    public function is_admin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    // Redirect unauthorized users
    public function redirect_unauth_users($page) {
        if (!$this->is_admin()) {
            $this->redirect($page);
        }
    }

    // Check if user is logged in
    public function is_login() {
        return isset($_SESSION['user_id']);
    }

    // Logout user
    public function logOut() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_role']);
        $_SESSION = array();
        session_destroy();
        $this->redirect("login.php");
    }

    // Check if username exists
    public function checkUser($username) {
        $stmt = $this->pdo->prepare("SELECT username FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Check if email exists
    public function checkEmail($email) {
        $stmt = $this->pdo->prepare("SELECT email FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Fetch user logs
    public function userLog() {
        $stmt = $this->pdo->prepare("SELECT * FROM logs ORDER BY id DESC LIMIT 5");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Register a new user (sample method, uncomment and adjust as needed)
    public function register($screenName, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users(screenName, email, password, profileImage, profileCover) VALUES(:screenName, :email, :password, 'assets/images/defaultprofileimage.png', 'assets/images/defaultCoverImage.png')");
        $stmt->bindParam(':screenName', $screenName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
        $user_id = $this->pdo->lastInsertId();

        $_SESSION['user_id'] = $user_id;
        $this->redirect("home.php");
    }

    // Redirect method
    private function redirect($url) {
        header("Location: $url");
        exit();
    }
}
?>
