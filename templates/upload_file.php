<?php 
require_once './config/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_article'])) {
    $title  = trim($_POST['title']);
    $status = $_POST['status'];
    $categories = $_POST['categories'] ?? [];

    // get content from file or textarea
    if (!empty($_FILES['article_file']['tmp_name'])) {
        $content = file_get_contents($_FILES['article_file']['tmp_name']);
    } else {
        $content = trim($_POST['content']);
    }

    if (empty($title) || empty($content)) {
        $uploadError = 'Title and content are required';
    } else {
        // insert article
        $stmt = $conn->prepare("INSERT INTO articles (user_id, Title, Content, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('isss', $_SESSION['user_id'], $title, $content, $status);

        if ($stmt->execute()) {
            $articleId = $conn->insert_id;  // get the new article id

            // insert categories
            foreach ($categories as $categoryId) {
                $stmt = $conn->prepare("INSERT INTO article_categories (article_id, category_id) VALUES (?, ?)");
                $stmt->bind_param('ii', $articleId, $categoryId);
                $stmt->execute();
            }

            $uploadSuccess = 'Article published successfully';
        } else {
            $uploadError = 'Something went wrong';
        }
    }
}

?>