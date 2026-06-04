<!DOCTYPE html>
<html lang="en" data-theme="light">
 <?php
    session_start();
    require_once "./templates/head.php";
 ?>
<body class="dashboard-page">
    <?php
        $currentPage='dashboard';
        require_once "./templates/header.php";
        require_once "./config/db.php";
        require_once "./templates/upload_file.php";
        
        //countinf the articles of the user
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM articles WHERE user_id = ?");
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $articleCount = $stmt->get_result()->fetch_assoc()['total'];

        //total likes count
        $stmt = $conn->prepare("
            SELECT COUNT(*) as total 
            FROM likes 
            JOIN articles ON likes.article_id = articles.Id 
            WHERE articles.user_id = ?
        ");
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $totalLikes = $stmt->get_result()->fetch_assoc()['total'];

        // total comments across all user's articles
        $stmt = $conn->prepare("
            SELECT COUNT(*) as total 
            FROM comments 
            JOIN articles ON comments.article_id = articles.Id 
            WHERE articles.user_id = ?
        ");
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $totalComments = $stmt->get_result()->fetch_assoc()['total'];

        //query for getting recent articles
        $stmt = $conn->prepare("
            SELECT 
                articles.Id,
                articles.Title,
                articles.createdat,
                COUNT(DISTINCT likes.user_id) as like_count,
                COUNT(DISTINCT comments.id) as comment_count
            FROM articles
            LEFT JOIN likes ON articles.Id = likes.article_id
            LEFT JOIN comments ON articles.Id = comments.article_id
            WHERE articles.user_id = ?
            GROUP BY articles.Id
            ORDER BY articles.createdat DESC
            LIMIT 5
        ");
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $recentArticles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        //get all the articles
        $stmt = $conn->prepare("
            SELECT articles.Id, articles.Title, articles.createdat, articles.Content, articles.status,
                COUNT(DISTINCT likes.user_id) as like_count,
                COUNT(DISTINCT comments.id) as comment_count
            FROM articles
            LEFT JOIN likes ON articles.Id = likes.article_id
            LEFT JOIN comments ON articles.Id = comments.article_id
            WHERE articles.user_id = ?
            GROUP BY articles.Id
            ORDER BY articles.createdat DESC
        ");
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $myArticles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        //saving the changes of the article
        if(isset($_POST['save_article_changes'])){
            $articleId = $_POST['edit_article_id'];
            $title = $_POST['edit_title'];
            $content = $_POST['edit_content'];

            $stmt = $conn->prepare("
                UPDATE articles
                SET Title=?, Content=?
                WHERE Id=? AND user_id=?
            ");

            $stmt->bind_param(
                "ssii",
                $title,
                $content,
                $articleId,
                $_SESSION['user_id']
            );

            $stmt->execute();

            header("Location: user_dashboard.php");
            exit;
        }

        if(isset($_POST['publish_article'])){
            $articleId = $_POST['article_id'];
            $stmt = $conn->prepare("
                UPDATE articles
                SET status = 'published'
                WHERE Id = ? AND user_id = ?
            ");
            $stmt->bind_param(
                "ii",
                $articleId,
                $_SESSION['user_id']
            );
            $stmt->execute();

            header("Location: user_dashboard.php");
            exit;
        }
    ?>
    <div class="dashboard-container">
        <aside class="dashboard-aside">
            <div class="dashboard-cont">
                <p class="user"> <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></p>
                <div>
                    <span class="author">Author <?php echo $_SESSION['username'];?></span>
                    <span class="article-count"><?php echo $articleCount ?> Articles</span>
                </div>
            </div>
            <nav>
                <span>NAVIGATION</span>
                <ul>
                    <li><a href="#" class="nav-item active" data-section="overview">overview</a></li>
                    <li><a href="#" class="nav-item" data-section="articles">My Articles</a></li>
                    <li><a href="#" class="nav-item" data-section="upload">Upload</a></li>
                </ul>   
            </nav>
        </aside>
        <main class="dashboard-content">
            <section class="section active" id="overview">
                <div class="overview-header">
                    <h2>Hello, <?php echo $_SESSION['username']; ?></h2>
                    <a href="#upload" class="new-article">+ New Article</a>
                </div>
                <article class="stat-grid">
                    <div class="stat-card">
                        <span class="stat-label">Total Articles</span>
                        <span class="stat-number"><?php echo $articleCount;?></span>
                    </div>
                     <div class="stat-card">
                        <span class="stat-label">Total Likes</span>
                        <span class="stat-number"><?php echo $totalLikes; ?></span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-label">Total Comments</span>
                        <span class="stat-number"><?php echo $totalComments; ?></span>
                    </div>
                </article>
                <section class="recent-articles-section" >
                    <h2>Recent Articles</h2>
                    <?php if (empty($recentArticles)): ?>
                        <p>No articles yet</p>
                    <?php else: ?>
                        <?php foreach ($recentArticles as $article): ?>
                            <article class="recent-article-row">
                                <div class="recent-article-info">
                                    <a href="read.php?id=<?php echo $article['Id']; ?>">
                                        <?php echo $article['Title']; ?>
                                    </a>
                                    <span class="date"><?php echo date('M d, Y', strtotime($article['createdat'])); ?></span>
                                </div>
                                <div class="recent-article-stats">
                                    <span><?php echo $article['like_count'];?> likes</span>
                                    <span><?php echo $article['comment_count']; ?> comments</span>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </section>
            </section>
            <section class="section my-articles-section" id="articles">
                <h2>My Articles</h2>

                <?php if (empty($myArticles)): ?>
                    <p>No articles yet</p>
                <?php else: ?>
                    <?php foreach ($myArticles as $article): ?>
                        <article class="my-article-row">
                            <div class="my-article-info">
                                <a href="read.php?id=<?php echo $article['Id']; ?>">
                                    <?php echo htmlspecialchars($article['Title']); ?>
                                </a>
                                <span class="date">
                                    <?php echo $article['createdat'] 
                                        ? date('M d, Y', strtotime($article['createdat'])) 
                                        : 'No date'; ?>
                                </span>
                            </div>
                            <div class="my-article-stats">
                                <span><?php echo $article['like_count']; ?> likes
                                <?php echo $article['comment_count']; ?> comments</span>
                                <div class="article-actions">
                                    <span class="status-badge <?php echo $article['status']; ?>">
                                        <?php echo ucfirst($article['status']); ?>
                                    </span>

                                    <?php if($article['status'] === 'draft'): ?>
                                        <form method="POST">
                                            <input type="hidden" name="article_id" value="<?php echo $article['Id']; ?>">
                                            <button type="submit" name="publish_article" class="publish-btn">
                                                Publish
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <button
                                        type="button" class="edit-btn"
                                        data-id="<?php echo $article['Id']; ?>"
                                        data-title="<?php echo htmlspecialchars($article['Title'], ENT_QUOTES); ?>"
                                        data-content="<?php echo htmlspecialchars($article['Content'], ENT_QUOTES); ?>">
                                        Edit
                                    </button>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
            <section class="section upload-file" id="upload">
                    <h2>New Article</h2>
    
                    <?php if (isset($uploadSuccess)): ?>
                        <p class="success"><?php echo $uploadSuccess; ?></p>
                    <?php endif; ?>
                    <?php if (isset($uploadError)): ?>
                        <p class="error"><?php echo $uploadError; ?></p>
                    <?php endif; ?>

                    <form action="" method="POST" enctype="multipart/form-data" id="upload-form">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" placeholder="Article title" required>

                        <label>Category</label>
                        <div class="category-checkboxes">
                            <label><input type="checkbox" name="categories[]" value="1"> Technology</label>
                            <label><input type="checkbox" name="categories[]" value="2"> Design</label>
                            <label><input type="checkbox" name="categories[]" value="3"> Tutorial</label>
                            <label><input type="checkbox" name="categories[]" value="4"> Lifestyle</label>
                        </div>

                        <label>Status</label>
                        <select name="status">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                                </select>

                            <label for="content">Write your article</label>
                            <textarea name="content" id="content" placeholder="Write your article here..."></textarea>

                            <label>Or upload a .txt file</label>
                            <input type="file" name="article_file" accept=".txt">

                            <button type="submit" name="upload_article">save</button>
                        </form>
            </section>
        </main>
    </div>
    <div id="editModal" class="edit-modal">
        <div class="edit-modal-content">
            <h2>Edit Article</h2>
            <form method="POST">
                <input type="hidden" name="edit_article_id" id="edit_article_id">

                <label>Title</label>
                <input type="text" name="edit_title" id="edit_title">

                <label>Content</label>
                <textarea
                    name="edit_content"
                    id="edit_content"
                    rows="12">
                </textarea>
                <div class="modal-buttons">
                    <button type="submit" name="save_article_changes">
                        Save Changes
                    </button>
                    <button
                        type="button"
                        class="cancel-btn">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
        <?php
        require_once "./templates/footer.php";
        ?>
    <script src="./js/main.js"></script>
</body>
</html>