<?php
require('connect.php');
include('functions.php');

// Redirect logged-in users to the homepage with a message
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    $_SESSION['alert_message'] = "You are already registered and logged in.";
    $_SESSION['alert_type'] = "danger";
    header('Location: index.php');
    exit;
}


include('header.php');

$error_message = ''; // Variable to store error message
$success_message = ''; // Variable to store success message



if (!empty($_POST)) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the passwords match
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match. Please try again.";
    } else {
        // Check if the username already exists
        if ($stm = $db->prepare('SELECT * FROM users WHERE username = ?')) {
            $stm->bindParam(1, $username, PDO::PARAM_STR);
            $stm->execute();

            if ($stm->fetch(PDO::FETCH_ASSOC)) {
                $error_message = "Username already exists. Please choose another one.";
            } else {
                // Insert the new user into the database with the role 'user'
                if ($stm = $db->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)')) {
                    $role = 'user';
                    $stm->bindParam(1, $username, PDO::PARAM_STR);
                    $stm->bindParam(2, $password, PDO::PARAM_STR);
                    $stm->bindParam(3, $role, PDO::PARAM_STR);
                    $stm->execute();

                    $success_message = "Registration successful! You can now <a href='login.php'>log in</a>.";
                } else {
                    $error_message = "Error: Could not prepare SQL statement.";
                }
            }

            $stm->closeCursor();
        } else {
            $error_message = "Error: Could not prepare SQL statement.";
        }
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
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

                <!-- Confirm Password input -->
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required />
                </div>

                <!-- Submit button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary mt-4 py-2 px-5 fixed-size">
                        Sign up
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>