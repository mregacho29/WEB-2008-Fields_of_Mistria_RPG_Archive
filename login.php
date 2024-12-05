<?php
require('connect.php');
include('functions.php');
include('header.php');


$error_message = ''; // Variable to store error message
$success_message = ''; // Variable to store success message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $captcha_input = htmlspecialchars(trim($_POST['captcha']));

    // Verify CAPTCHA
    if ($captcha_input === $_SESSION['captcha_code']) {
        // Log the user in
        if ($stm = $db->prepare('SELECT * FROM users WHERE username = ?')) {
            $stm->bindParam(1, $username, PDO::PARAM_STR);
            $stm->execute();

            $user = $stm->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Debugging: Check if user is fetched correctly
                // echo '<pre>'; print_r($user); echo '</pre>'; exit;

                // Verify the password
                if (password_verify($password, $user['password'])) {
                    // Login successful!
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['loggedin'] = true;

                    // Set success message
                    $_SESSION['alert_message'] = "Welcome, " . $user['username'] . "! You are now logged in.";
                    $_SESSION['alert_type'] = "success";

                    // Redirect to homepage after a delay
                    header("Location: index.php");
                    exit;
                } else {
                    // Password verification failed
                    $error_message = "Invalid username or password."; // Store error message
                }
            } else {
                // User not found
                $error_message = "Invalid username or password."; // Store error message
            }

            $stm->closeCursor();
        } else {
            // Database query failed
            $error_message = "Database query failed.";
        }
    } else {
        $error_message = 'CAPTCHA verification failed. Please try again.';
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <?php if (isset($_SESSION['alert_message'])): ?>
                <div class="alert alert-success text-center" role="alert">
                    <?= $_SESSION['alert_message'] ?>
                </div>
                <?php unset($_SESSION['alert_message']); ?>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <!-- Display error message using Bootstrap alert -->
                <div class="alert alert-danger text-center" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <?php if ($success_message): ?>
                <!-- Display success message using Bootstrap alert -->
                <div class="alert alert-success text-center" role="alert">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="login.php">
                <!-- Username input -->
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required />
                </div>

                <!-- Password input -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required />
                </div>

                <!-- CAPTCHA input -->
                <div class="mb-3">
                    <label for="captcha" class="form-label">CAPTCHA</label>
                    <div class="d-flex align-items-center">
                        <input type="text" class="form-control" id="captcha" name="captcha" required>
                        <img src="captcha.php" alt="CAPTCHA Image" class="me-3">
                    </div>
                </div>

                <!-- Submit button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary mt-4 py-2 px-5 fixed-size">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>