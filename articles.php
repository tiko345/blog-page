<!DOCTYPE html>
<html lang="en" data-theme="light">
 <?php
    require_once "./templates/head.php";
 ?>
<body>
    <?php
        require_once "./templates/header.php";
        require "./sapmle.php";

        if (!empty($_GET['search'])) {
            $search = strtolower(trim($_GET['search']));
            
            $articles = array_filter($articles, function($article) use ($search) {
                return str_contains(strtolower($article['title']), $search)
                    || str_contains(strtolower($article['author']), $search);
            });
        }

        if (!empty($_GET['category'])) {
            $category = $_GET['category'];
            $articles = array_filter($articles, function($article) use ($category) {
                return in_array($category, $article['categories']);
            });
        }
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
                <button type="submit" class="search-btn">Search</button>
            </form>
        </section>
        <section class="articles-container">
            <div class="category-filters">
                <a href="articles.php" class="filter-btn <?php echo !isset($_GET['category']) ? 'active' : ''; ?>">
                    All
                </a>
                <?php 
                $allCategories = ['Technology', 'Design', 'Tutorial', 'Lifestyle'];
                foreach ($allCategories as $cat): ?>
                    <a href="articles.php?category=<?php echo $cat; ?>" 
                    class="filter-btn <?php echo isset($_GET['category']) && $_GET['category'] === $cat ? 'active' : ''; ?>">
                        <?php echo $cat; ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php //checks the input from the search and checks if its not found
            if (empty($articles)): ?>
                <p>No articles found <?php echo !empty($_GET['search']) ? 'for "' . ////handdles html special characters
                 htmlspecialchars($_GET['search']) . '"' : ''; ?></p>
            <?php else: ?>
            <?php foreach ($articles as $key => $article): //asignes each article key value so that we can open it ?>
                <a href="read.php?id=<?php echo $key; ?>" class="article-link">
                    <article>
                        <div class="article-card"></div>
                        <div class="article-text">
                            <h2><?php echo $article['title']; ?></h2>
                            <p class="content"><?php echo $article['content']; ?></p>
                            <div class="article-info">
                                <p class="author">By <?php echo $article['author']; ?></p>
                                <p class="date"><?php echo $article['date']; ?></p>
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