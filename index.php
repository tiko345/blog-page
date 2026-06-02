<!DOCTYPE html>
<html lang="en" data-theme="light">
    <?php
        session_start();
        require_once "./templates/head.php";
    ?>
<body>
    <?php
     $currentPage='home';
     require_once "./templates/header.php";
     require_once './config/db.php';

        
    // fetch 3 latest published articles for featured section
    $stmt = $conn->prepare("
        SELECT articles.Id, articles.Title, articles.Content, users.UserName
        FROM articles
        JOIN users ON articles.user_id = users.Id
        WHERE articles.status = 'published'
        ORDER BY articles.createdat DESC
        LIMIT 3
    ");
    $stmt->execute();
    $featuredArticles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // fetch articles by category for recent section
    $stmt = $conn->prepare("
        SELECT articles.Id, articles.Title, articles.Content, 
            users.UserName, categories.Name as category
        FROM articles
        JOIN users ON articles.user_id = users.Id
        LEFT JOIN article_categories ON articles.Id = article_categories.article_id
        LEFT JOIN categories ON article_categories.category_id = categories.Id
        WHERE articles.status = 'published'
        ORDER BY articles.createdat DESC
        LIMIT 3
    ");
    $stmt->execute();
    $recentArticles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // fetch categories with article counts
    $stmt = $conn->query("
        SELECT categories.Id, categories.Name, COUNT(article_categories.article_id) as count
        FROM categories
        LEFT JOIN article_categories ON categories.Id = article_categories.category_id
        GROUP BY categories.Id
    ");
    $categoryList = $stmt->fetch_all(MYSQLI_ASSOC);


    function articleLink(int $id): string {
        if (isset($_SESSION['user_id'])) {
            return "./read.php?id=$id";
        }
        return "./register.php";
    }
    ?>
    <main class="main-content">
        <section class="discover">
        <span>✦ Discover great writing</span>
        <h1>Ideas worth reading, <span>stories worth sharing</span></h1>
        <p>Chronicle brings together the best writers and readers.
             Explore thousands of articles across every topic.</p>
        <div class="home-buttons">
            <button class="btn1"  onclick="window.location.href='<?php echo isset($_SESSION['user_id']) ? './articles.php' : './register.php'; ?>'">Start reading</button>
            <button class="btn2" onclick="window.location.href='<?php echo isset($_SESSION['user_id']) ? './user_dashboard.php' : './register.php'; ?>'">Start writing</button>
        </div>
        <div class="stats-container">
            <span class="span-nums">12k+ <span class="span-stats">Articles</span></span>
            <span class="span-nums">3.4k <span class="span-stats">Writers</span></span>
            <span class="span-nums">48k <span class="span-stats">Readers</span></span>
        </div>
        </section>
        <section class="featured-stories">
            <div class="featured-header">
                <h2>Featured Stories</h2>
                <span><a href="#">View all →</a></span>
            </div>
            <div class="featured-stories-container">
                <?php if (!empty($featuredArticles)): ?>
                    <?php foreach ($featuredArticles as $i => $article): ?>
                        <?php if ($i === 0): ?>
                            <article class="featured-story1">
                                <img src="./assets/img/card1.jpg" alt="Card image">
                                <div class="featured-stories-article1-info">
                                    <h2>
                                        <a href="<?php echo articleLink($article['Id']); ?>">
                                            <?php echo $article['Title']; ?>
                                        </a>
                                    </h2>
                                    <p><?php echo substr($article['Content'], 0, 150) . '...'; ?></p>
                                    <span><?php echo $article['UserName']; ?></span>
                                </div>
                            </article>
                        <?php else: ?>
                            <article class="featured-story<?php echo $i + 1; ?>">
                                <img src="./assets/img/card<?php echo $i + 1; ?>.jpg" alt="Card image">
                                <div class="featured-stories-article<?php echo $i + 1; ?>-info">
                                    <h4>
                                        <a href="<?php echo articleLink($article['Id']); ?>">
                                            <?php echo $article['Title']; ?>
                                        </a>
                                    </h4>
                                    <span>By <?php echo $article['UserName']; ?></span>
                                </div>
                            </article>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        <section class="browse">
            <h2>Browse by Category</h2>
            <div class="browse-container">
                <?php if (!empty($categoryList)): ?>
                    <?php foreach ($categoryList as $cat): ?>
                        <article>
                            <div class="icon"><div></div></div>
                            <h4>
                                <a href="articles.php?category=<?php echo urlencode($cat['Name']); ?>">
                                    <?php echo $cat['Name']; ?>
                                </a>
                            </h4>
                            <span><?php echo $cat['count']; ?> articles</span>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        <section class="recent-articles">
            <?php if (!empty($recentArticles)): ?>
                <?php foreach ($recentArticles as $i => $article): ?>
                    <article>
                        <img src="./assets/img/card<?php echo ($i % 3) + 1; ?>.jpg" alt="Card image">
                        <div class="article-content">
                            <h4>
                                <a href="<?php echo articleLink($article['Id']); ?>">
                                    <?php echo $article['Title']; ?>
                                </a>
                            </h4>
                            <p><?php echo substr($article['Content'], 0, 80) . '...'; ?></p>
                            <span class="article-author">By <?php echo $article['UserName']; ?></span>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
        <section class="stay-in-the-loop">
            <h2>Stay in the loop</h2>
            <p>Get the best articles delivered straight to your inbox every week.</p>
            <form novalidate action="#">
                <input type="email" id="email" placeholder="Enter your email address">
                <button id="subbtn" type="submit">Subscribe</button>
            </form>
            <p id="errorMsg"></p>
        </section>
    </main>
    <?php
    require_once "./templates/footer.php"
    ?>

    <script src="./js/main.js"></script>
</body>
</html>