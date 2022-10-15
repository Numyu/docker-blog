<?php
session_start();
require("../includes/pdo.inc.php");
$submit = filter_input(INPUT_POST, "submit");
$post = filter_input(INPUT_POST, "post");
// Check if the user is already logged in, if no then redirect him to loginpage
if (!isset($_SESSION['id'])) {
  header("Location: login.php");
} else {
  // Check if the user has submitted the form
  if (isset($submit)) {
    // Check if the post is not empty
    if (!empty($post)) {
      $userId = $_SESSION['id'];
      $addPost = $db->query("INSERT INTO posts(content, user_id, created_at, updated_at ) VALUES ('$post', '$userId', NOW(), NOW())");
    }
  }
}
?>

<?php require("../partials/header.php"); ?>
<div class="home-page">
  <div class="home-box">
    <nav>
      <img src="../public/icon/logo.svg" alt="docker-blog-logo" />

      <div class="nav-link">
        <h4><?= $_SESSION['username'] ?></h4>
        <span></span>
        <a href="../includes/logout.inc.php">
          <img src="../public/icon/logout.svg" alt="logout-icon" />
        </a>
      </div>
    </nav>

    <form method="post">
      <textarea name="post" placeholder="What's new ?"></textarea>
      <input id="publish" type="submit" name="submit" value="Publish" />
    </form>

    <div class="post-container">
      <?php
      $getPosts = $db->query("SELECT * FROM posts ORDER BY created_at DESC");
      $getPosts = $getPosts->fetchAll(PDO::FETCH_ASSOC);
      foreach ($getPosts as $post) {
        $userId = $post['user_id'];
        $getUser = $db->query("SELECT * FROM users WHERE id = '$userId'");
        $user = $getUser->fetch(PDO::FETCH_ASSOC)['username'];
      ?>
        <div class="post">
          <div class="post-head">
            <div>
              <h4><?= $user ?></h4>
              <span><?= $post['created_at'] ?></span>
            </div>
              <?php if ($_SESSION['permission'] === 1) { ?>
              <a href="../includes/delete.inc.php?id=<?= $post['id'] ?>">
                <img src="../public/icon/delete.svg" alt="delete-icon" />
              </a>
              <?php } else if ($_SESSION['id'] === $post['user_id']) { ?>
              <a href="../includes/delete.inc.php?id=<?= $post['id'] ?>">
                <img src="../public/icon/delete.svg" alt="delete-icon" />
              </a>
              <?php } ?>
          </div>
          <div class="post-content">
            <p><?= $post['content'] ?></p>
          </div>
        <?php } ?>
        </div>
    </div>
  </div>
</div>

<script>
  const textarea = document.querySelector("textarea");
  const publish = document.querySelector("#publish");
  textarea.addEventListener("keyup", (e) => {
    textarea.style.height = "auto";
    let scHeight = e.target.scrollHeight;
    textarea.style.height = `${scHeight}px`;
  });
  publish.addEventListener("click", (e) => {
    if (textarea.value === "") {
      e.preventDefault();
    }
  });
</script>
<?php require("../partials/header.php"); ?>