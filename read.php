<!DOCTYPE html>
<html lang="en" data-theme="light">

<?php
require_once "./templates/head.php";
require './sapmle.php';

// get the id from the URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// find that article
$article = $articles[$id] ?? null;

// if article doesn't exist go back to articles page
if (!$article) {
    header('Location: articles.php');
    exit;
}
?>

<?php require_once './templates/header.php'; ?>
<body>
<main class="single-article">
    <article>
        <h1><?php echo $article['title']; ?></h1>
        <div class="article-categories">
            <?php foreach ($article['categories'] as $category): ?>
                <span class="category-badge"><?php echo $category; ?></span>
            <?php endforeach; ?>
        </div>
        <p class="author">By <?php echo $article['author']; ?></p>
        <p class="date"><?php echo $article['date']; ?></p>
        <div class="content">
            <?php echo nl2br($article['content']); ?>
        </div>
        
        <a href="articles.php">← Back to articles</a>
    </article>
</main>

<?php require_once './templates/footer.php'; ?>

<script src="./js/main.js"></script>
</body>

</html>