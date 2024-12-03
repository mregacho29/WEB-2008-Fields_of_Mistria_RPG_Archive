<?php
require('connect.php');
include('functions.php');
include('header.php');

$error_message = ''; // Variable to store error message
$success_message = ''; // Variable to store success message

if (!empty($_POST)) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Log the user in
    if ($stm = $db->prepare('SELECT * FROM users WHERE username = ?')) {
        $stm->bindParam(1, $username, PDO::PARAM_STR);
        $stm->execute();

        $user = $stm->fetch(PDO::FETCH_ASSOC);

        if ($user && $password == $user['password']) {
            // Login successful!
            
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['loggedin'] = true;

            // Set success message
            $success_message = "Welcome, " . $user['username'] . "! You are now logged in.";

            // Redirect to homepage after a delay
            header("refresh:3;url=index.php");
        } else {
            // Login failed
            $error_message = "Invalid username or password."; // Store error message
        }

        $stm->closeCursor();
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
            <form method="post">
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