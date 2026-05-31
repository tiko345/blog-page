<!DOCTYPE html>
<html lang="en" data-theme="light">
    <?php
        require_once "./templates/head.php"
    ?>
<body>
    <?php
     require_once "./templates/header.php"
    ?>
    <main class="main-content">
        <section class="discover">
        <span>✦ Discover great writing</span>
        <h1>Ideas worth reading, <span>stories worth sharing</span></h1>
        <p>Chronicle brings together the best writers and readers.
             Explore thousands of articles across every topic.</p>
        <div class="home-buttons">
            <button class="btn1">Start reading</button>
            <button class="btn2">Start writing</button>
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
                <article class="featured-story1">
                    <img src="./assets/img/card1.jpg" alt="Card image">
                    <span class="image-span">Design</span>
                    <div class="featured-stories-article1-info">
                        <h2>The Art Of Mnimalist Design In Modern Web</h2>
                        <p>A deep dive into the principles that separate good design from great design — and how minimalism shapes user perception in the digital age.</p>
                        <span>Caroline F.</span>
                    </div>
                </article>
                <article class="featured-story2">
                    <img src="./assets/img/card2.jpg" alt="Card image">
                    <div class="featured-stories-article2-info">
                        <span class="image-span2">Code</span>
                        <h4>Understanding CSS Grid: A Complete Guide</h4>
                        <span>Marco T. · 8 min read</span>
                    </div>
                </article>
                <article class="featured-story3">
                    <img src="./assets/img/card3.jpg" alt="Card image">
                    <div class="featured-stories-article3-info">
                        <span class="image-span2">Code</span>
                        <h4>Why Every Developer Should Learn OOP Principles</h4>
                        <span>Sara K. · 6 min read</span>
                    </div>
                </article>
            </div>
        </section>
        <section class="browse">
            <h2>Browse by Category</h2>
            <div class="browse-container">
                <article><div class="icon1"><div></div></div><h4>Design</h4><span>42 articles</span></article>
                <article><div class="icon2"><div></div></div><h4>Code</h4><span>78 articles</span></article>
                <article><div class="icon3"><div></div></div><h4>Technology</h4><span>31 articles</span></article>
                <article><div class="icon4"><div></div></div><h4>Culture</h4><span>19 articles</span></article>
                <article><div class="icon5"><div></div></div><h4>Business</h4><span>25 articles</span></article>
                <article><div class="icon6"><div></div></div><h4>Science</h4><span>14 articles</span></article>
            </div>
        </section>
        <section class="recent-articles">
            <article>
                <img src="./assets/img/card1.jpg" alt="Card image">
                <div class="span-container">
                    <span class="image-span2">Design</span>
                    <span class="time-span">5 min read</span>
                </div>
                <h4>The Art Of Mnimalist Design In Modern Web</h4>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas, voluptate.</p>
                <span>user</span>
            </article>
            <article>
                <img src="./assets/img/card2.jpg" alt="Card image">
                <div class="span-container">
                    <span class="image-span2">Code</span>
                    <span class="time-span">5 min read</span>
                </div>
                <h4>The Art Of Mnimalist Design In Modern Web</h4>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas, voluptate.</p>
                <span>user</span>
            </article>
            <article>
                <img src="./assets/img/card3.jpg" alt="Card image">
                <div class="span-container">
                    <span class="image-span2">Code</span>
                    <span class="time-span">5 min read</span>
                </div>
                <h4>The Art Of Mnimalist Design In Modern Web</h4>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas, voluptate.</p>
                <span>user</span>
            </article>
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