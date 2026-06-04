<!DOCTYPE html>
<html lang="en" data-theme="light">

<?php
    session_start();
    require_once "./templates/head.php";
    require_once './config/db.php';

    // get the id from the URL
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $loggedUserId = $_SESSION['user_id'] ?? 0;

    $stmt = $conn->prepare("
        SELECT articles.*, users.UserName 
        FROM articles 
        JOIN users ON articles.user_id = users.Id
        WHERE articles.Id = ?
    ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $article = $stmt->get_result()->fetch_assoc();

    // if article doesn't exist go back to articles page
    if (!$article) {
        header('Location: articles.php');
        exit;
    }

    $isOwner = $loggedUserId === (int)$article['User_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_like'])) {
        if (!$loggedUserId) {
            header('Location: login.php');
            exit;
        }
        if (!$isOwner) {
            $stmt = $conn->prepare("SELECT 1 FROM likes WHERE user_id = ? AND article_id = ?");
            $stmt->bind_param('ii', $loggedUserId, $id);
            $stmt->execute();
            $alreadyLiked = $stmt->get_result()->num_rows > 0;

            if ($alreadyLiked) {
                $stmt = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND article_id = ?");
            } else {
                $stmt = $conn->prepare("INSERT INTO likes (user_id, article_id) VALUES (?, ?)");
            }
            $stmt->bind_param('ii', $loggedUserId, $id);
            $stmt->execute();
        }
        header("Location: read.php?id=$id");
        exit;
    }

    // handle comment submit
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
        if (!$loggedUserId) {
            header('Location: login.php');
            exit;
        }
        if (!$isOwner) {
            $content = trim($_POST['comment_content']);
            if (!empty($content)) {
                $stmt = $conn->prepare("INSERT INTO comments (user_id, article_id, content) VALUES (?, ?, ?)");
                $stmt->bind_param('iis', $loggedUserId, $id, $content);
                $stmt->execute();
            }
        }
        header("Location: read.php?id=$id");
        exit;
    }

    // fetch categories
    $stmt = $conn->prepare("
        SELECT categories.Name 
        FROM categories
        JOIN article_categories ON categories.Id = article_categories.category_id
        WHERE article_categories.article_id = ?
    ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $categories = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // fetch like count and whether current user liked it
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM likes WHERE article_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $likeCount = $stmt->get_result()->fetch_assoc()['total'];

    $userLiked = false;
    if ($loggedUserId) {
        $stmt = $conn->prepare("SELECT 1 FROM likes WHERE user_id = ? AND article_id = ?");
        $stmt->bind_param('ii', $loggedUserId, $id);
        $stmt->execute();
        $userLiked = $stmt->get_result()->num_rows > 0;
    }

    // fetch comments with usernames
    $stmt = $conn->prepare("
        SELECT comments.content, comments.Id, users.username
        FROM comments
        JOIN users ON comments.user_id = users.Id
        WHERE comments.article_id = ?
        ORDER BY comments.Id DESC
    ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $comments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // fetch categories for this article
    $stmt = $conn->prepare("
        SELECT categories.Name 
        FROM categories
        JOIN article_categories ON categories.Id = article_categories.Category_Id
        WHERE article_categories.Article_id = ?
    ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $categories = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
?>

<?php $currentPage='read';
require_once './templates/header.php'; ?>
<body>
<main class="single-article">
    <article>
        <h1><?php echo $article['Title']; ?></h1>
        <div class="article-categories">
            <?php foreach ($categories as $category): ?>
                <span class="category-badge"><?php echo $category['Name']; ?></span>
            <?php endforeach; ?>
        </div>
        <p class="author">By <?php echo $article['UserName']; ?></p>
        <p class="date"><?php echo date('M d, Y', strtotime($article['CreatedAt'])); ?></p>
        <div class="content">
            <?php echo nl2br($article['Content']); ?>
        </div>
        <div class="article-actions">
        <div class="likes-section">
            <?php if ($loggedUserId && !$isOwner): ?>
                <form method="POST">
                    <button type="submit" name="toggle_like" class="like-btn <?php echo $userLiked ? 'liked' : ''; ?>">
                        <?php echo $userLiked ? '♥ Unlike' : '♡ Like'; ?>
                    </button>
                </form>
            <?php endif; ?>
            <span><?php echo $likeCount; ?> <?php echo $likeCount === 1 ? 'like' : 'likes'; ?></span>
        </div>
        <div class="comments-section">
            <h3>Comments (<?php echo count($comments); ?>)</h3>

            <?php if ($loggedUserId && !$isOwner): ?>
                <form method="POST" class="comment-form">
                    <textarea name="comment_content" placeholder="Write a comment..." required></textarea>
                    <button type="submit" name="submit_comment" class="post-comm-btn">Post Comment</button>
                </form>
            <?php elseif ($isOwner): ?>
                <p class="owner-note">You cannot like or comment on your own article.</p>
            <?php else: ?>
                <p><a href="login.php">Log in</a> to like and comment.</p>
            <?php endif; ?>

            <?php foreach ($comments as $comment): ?>
        </div>
        </div>
                <div class="comment">
                    <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                    <p><?php echo htmlspecialchars($comment['content']); ?></p>
                </div>
            <?php endforeach; ?>
            
        
        <a href="articles.php">← Back to articles</a>
    </article>
</main>

<?php require_once './templates/footer.php'; ?>

<script src="./js/main.js"></script>
</body>

</html>