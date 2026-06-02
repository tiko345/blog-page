<!DOCTYPE html>
<html lang="en" data-theme="light">
 <?php
    session_start();
    require_once "./templates/head.php";
 ?>
<body>
    <?php
        $currentPage='articles';
        require_once "./templates/header.php";
        require_once './config/db.php';

        $search   = !empty($_GET['search'])   ? trim($_GET['search'])       : '';
        $category = !empty($_GET['category']) ? (int)$_GET['category']      : 0;

        // fetch categories for filter buttons
        $allCategories = $conn->query("SELECT Id, Name FROM categories")->fetch_all(MYSQLI_ASSOC);

        // build query
        $sql = "SELECT a.Id, a.Title, a.Content, a.CreatedAt, u.username AS author
                FROM articles a
                JOIN users u ON a.User_id = u.Id
                WHERE a.status = 'published'";

        $params = [];
        $types  = '';

        if ($search) {
            $sql .= " AND (LOWER(a.Title) LIKE ? OR LOWER(u.username) LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $types .= 'ss';
        }

        if ($category) {
            $sql .= " AND a.Id IN (SELECT article_id FROM article_categories WHERE category_id = ?)";
            $params[] = $category;
            $types .= 'i';
        }

        $sql .= " ORDER BY a.CreatedAt DESC";

        $stmt = $conn->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $articles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

    ?>
    <main class="articles-main-content">
        <section class="sub-header">
            <h1>EXPLORE</h1>
            <form action="" method="GET">
                <input 
                    type="text" 
                    name="search"
                    placeholder="Search by title or author..."
                    id="search";
                    value="<?php //keeps the input value for the search so we can see what we searched for
                     echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                >
                 <?php if ($category): ?>
                    <input type="hidden" name="category" value="<?php echo $category; ?>">
                <?php endif; ?>
                <button type="submit" class="search-btn">Search</button>
            </form>
        </section>
        <section class="articles-container">
            <div class="category-filters">
                <a href="articles.php" class="filter-btn <?php echo !isset($_GET['category']) ? 'active' : ''; ?>">
                    All
                </a>
                <?php 
                foreach ($allCategories as $cat): ?>
                    <a href="articles.php?category=<?php echo $cat['Id']; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                    class="filter-btn <?php echo $category === $cat['Id'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($cat['Name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php //checks the input from the search and checks if its not found
            if (empty($articles)): ?>
                <p>No articles found <?php echo !empty($_GET['search']) ? 'for "' . ////handdles html special characters
                 htmlspecialchars($_GET['search']) . '"' : ''; ?></p>
            <?php else: ?>
            <?php foreach ($articles as $article):  //asignes each article key value so that we can open it ?>
                <a href="read.php?id=<?php echo $article['Id']; ?>" class="article-link">
                    <article>
                        <div class="article-card"></div>
                        <div class="article-text">
                            <h2><?php echo htmlspecialchars($article['Title']); ?></h2>
                            <p class="content"><?php echo htmlspecialchars(substr($article['Content'], 0, 40)) . '...'; ?></p>
                            <div class="article-info">
                                <p class="author">By <?php echo htmlspecialchars($article['author']); ?></p>
                                <p class="date"><?php echo date('M d, Y', strtotime($article['CreatedAt'])); ?></p>
                            </div>
                        </div>
                    </article>
                </a>
            <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <?php
        require_once "./templates/footer.php";
    ?>
    <script src="./js/main.js"></script>
</body>
</html>