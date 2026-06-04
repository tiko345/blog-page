<?php
    session_start();
    require_once './config/db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $email    = trim($_POST['login-email']);
        $password = $_POST['login-password'];

    if (empty($email) || empty($password)) {
        $loginError = 'All fields are required';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE Email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        

        if ($user && password_verify($password, $user['Password'])) {
            $_SESSION['user_id']  = $user['Id'];
            $_SESSION['username'] = $user['UserName'];
            $_SESSION['role']     = $user['Role'];

            if ($user['Role'] === 'admin') {
                header('Location: ./admin_dashboard.php');
            } else {
                header('Location: ./user_dashboard.php');
            }
            exit;
        } else {
            $loginError = 'Invalid email or password';
        }
    }
    }
?>

<?php
//handles input from register form and registers the user in the database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match';
    } else {
        // hash the password 
        $hashed = password_hash($password, PASSWORD_BCRYPT);

        // insert into database
        $stmt = $conn->prepare("INSERT INTO users (UserName, Email, Password, Role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param('sss', $username, $email, $hashed);

        if ($stmt->execute()) {
            $success = 'Account created successfully';
        } else {
            $error = 'Username or email already taken';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
     <?php
        $currentPage='register';
        require_once "./templates/head.php"
    ?>
    <body>
        <?php
            require_once "./templates/header.php"
        ?>
        <main class="main-content-register">
            <section class="register-side-info">
                <h1>Chronicle</h1>
                <span>Where ideas come to life.</span>
                <div class="text-container">
                    <h2>Join thousands of writers sharing their stories with the world.</h2>
                    <div class="span-container">
                        <span>Publish unlimited articles for free</span>
                        <span>Reach a global audience of readers</span>
                        <span>Build your personal brand as a writer</span>
                    </div>
                </div>
            </section>
            <section class="register-container">
                <div class="forms-container">
                    <h2 id="signin-title" >Welcome back</h2>
                    <h2 id="register-title" class="hidden">Create your account</h2>
                    <h2 id="reset-password" class="hidden">Reset Password</h2>
                    <span id="signin-span" >Sign in to continue to Chronicle</span>
                    <span id="register-span" class="hidden">Start writing and sharing today</span>
                    <span id="reset-span"  class="hidden">Enter your email and we will send you instructions to reset your password.</span>
                    <div class="auth-tabs">
                        <button class="tab active" id="signin-tab">Sign in</button>
                        <button class="tab" id="register-tab">Register</button>
                    </div>
                    <button type="button" class="google">G continue with Google</button>
                    <span class="google-span">or continue with Email</span>
                        <form id="signin-form" action="" method="post">
                            <label for="email-signin">Email Address</label>
                            <input type="email" name="login-email" id="email-signin" placeholder="you@example.com" required>
                            <div class="label-row">
                                <label for="password-signin">Password</label>
                                <a class="forgotpass">Forgot password?</a>
                            </div>
                            <input type="password" name="login-password" id="password-signin" placeholder="at least 8 characters" required>
                            <div class="checkbox-container">
                                <input type="checkbox" id="remember">
                                <span>Remember me for 30 days</span> 
                            </div>
                            <?php if (isset($loginError)): ?>
                                <p class="error"><?php echo $loginError; ?></p>
                            <?php endif; ?>
                            <button type="submit" name="login" class="signin-btn">Sign In</button>
                        </form>
                        <form id="register-form" class="hidden" action="" method="post">
                            <label for="fullName" >User Name</label>
                            <input type="text" id="fullName" name="username" placeholder="John Doe" required>
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="you@example.com" required>
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="at least 8 characters" required>
                            <label for="confPassword">Confirm Password</label>
                            <input type="password" id="confPassword" name="confirm_password" placeholder="Repeat your password" required>
                            <div class="checkbox-container">
                                <input type="checkbox" id="terms" required>
                                <label for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
                            </div>
                            <button type="submit" class="signup-btn">Create Account</button>
                        </form>
                        <form id="reset-form" class="hidden" action="self">
                            <label for="reset">Email Address</label>
                            <input type="email" name="email" id="reset" placeholder="you@example.com" required>
                            <button type="submit" class="reset-link">Send Reset Link</button>
                            <span><a class="back-to-login active">back to login</a></span>
                        </form>
                 </div>
            </section>
        </main>



        <script src="./js/main.js"></script>
    </body>
</html>