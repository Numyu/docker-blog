<?php
session_start();
require("../includes/pdo.inc.php");
$submit = filter_input(INPUT_POST, "submit");
$username = filter_input(INPUT_POST, "username");
$password = filter_input(INPUT_POST, "password");
// Check if the user has submitted the form
if (isset($submit)) {
    // Check if the username and password are not empty
    if (!empty($username) && !empty($password)) {
        // Check if the username exists
        $getUserUsername = $db->query("SELECT username FROM users WHERE username = '$username'");
        if ($getUserUsername->rowCount() > 0) {
            $error =  "This username is already taken !";
        } else {
            // Create the user
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $insertUserData = $db->query("INSERT INTO users(username, password, permission, created_at, updated_at) VALUES ('$username', '$passwordHash', 0, NOW(), NOW())");
            // Set session variables
            $getUserData = $db->query("SELECT id, username, permission FROM users WHERE username = '$username'");
            $getUserData = $getUserData->fetch(PDO::FETCH_ASSOC);
            $_SESSION['id'] = $getUserData['id'];
            $_SESSION['username'] = $getUserData['username'];
            $_SESSION['permission'] = $getUserData['permission'];
            // Redirect user to home page
            header("Location: home.php");
        }
    } else {
        $error = "Please field in all the fields !";
    }
}
?>

<?php require("../partials/header.php"); ?>
<div class="auth-page">
    <div class="auth-box">
        <img class="logo" src="../public/icon/logo.svg" alt="docker-blog-logo">
        <h4>Create your account</h4>
        <form method="post">
            <input class="auth-form-username" type="text" name="username" placeholder="Username">
            <div class="input-box">
                <input class="auth-form-password" type="password" name="password" placeholder="Password">
                <img class="show-password" src="../public/icon/eye-visible.svg" alt="eye-visible">
            </div>
            <?= isset($error) ? '<span style="color:red">' . $error . '</span>' : '' ?>
            <input class="auth-form-submit" type="submit" name="submit" value="Sign up">
        </form>
        <p>Already have an account ? <a href="./login.php">Log in</a></p>
    </div>
</div>

<script>
    const showPassword = document.querySelector('.show-password');
    showPassword.addEventListener('click', () => {
        const password = document.querySelector('.auth-form-password');
        if (password.type === 'password') {
            password.type = 'text';
            showPassword.src = '../../public/icon/eye-hidden.svg';
        } else {
            password.type = 'password';
            showPassword.src = '../../public/icon/eye-visible.svg';
        }
    })
</script>
<?php require("../partials/footer.php"); ?>