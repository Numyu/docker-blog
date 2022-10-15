<?php
session_start();
require("../includes/pdo.inc.php");
// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION['id'])) {
    header("Location: ./home.php");
    exit;
} else {
    $submit = filter_input(INPUT_POST, "submit");
    $username = filter_input(INPUT_POST, "username");
    $password = filter_input(INPUT_POST, "password");
    // Check if the user has submitted the form 
    if (isset($submit)) {
        // Check if the username and password are not empty
        if (!empty($username) && !empty($password)) {
            $background = true;
            // Check if the username exists
            $getUserUsername = $db->query("SELECT username FROM users WHERE username = '$username'");
            if ($getUserUsername->rowCount() === 0) {
                $error = "Error, please try again !";
            } else {
                // Check if the password is correct
                $getHash = $db->query("SELECT password FROM users WHERE username = '$username'");
                $getHash = $getHash->fetch(PDO::FETCH_ASSOC)['password'];
                if (password_verify($password, $getHash)) {
                    // Set session variables
                    $getUserData = $db->query("SELECT id, permission FROM users WHERE username = '$username'");
                    $getUserData = $getUserData->fetch(PDO::FETCH_ASSOC);

                    $userId = $getUserData['id'];
                    $userPermission = $getUserData['permission'];

                    $_SESSION['id'] = $userId;
                    $_SESSION['username'] = $username;
                    $_SESSION['permission'] = $userPermission;

                    // Redirect user to home page
                    header("Location: home.php");
                } else {
                    $error = "Error, please try again !";
                }
            }
        } else {
            $error = "Please fill in all the fields !";
        }
    }
}
?>

<?php require("../partials/header.php"); ?>
<div class="auth-page">
    <div class="auth-box">
        <img class="logo" src="../public/icon/logo.svg" alt="docker-blog-logo">
        <h4>Log in to Docker blog</h4>
        <form method="post">
            <input class="auth-form-username" type="text" name="username" placeholder="Username">
            <div class="input-box">
                <input class="auth-form-password" type="password" name="password" placeholder="Password">
                <img class="show-password" src="../public/icon/eye-visible.svg" alt="eye-visible">
            </div>
            <?= isset($error) ? '<span style="color:red">' . $error . '</span>' : '' ?>
            <input id="submit" class="auth-form-submit" type="submit" name="submit" value="Log in">
        </form>
        <p>Don't have an account ? <a href="./signup.php">Sign in</a></p>
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