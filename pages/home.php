<head>
    <link rel="stylesheet" href="/css/homeBackgroundImage.css">
</head>

<main class="container">
    <section class="hero-section">
        <p>Meet your future here.</p>
    </section>

    <section class="article-list">
        <?php
        if (empty($articles)): ?>
            <p class="no-articles"></p>
        <?php
        else: ?>
            <div class="grid">
                <?php
                foreach ($articles as $article): ?>
                    <article class="article-card">
                        <h3>
                            <a href="/article/<?= htmlspecialchars($article['slug']) ?>">
                                <?= htmlspecialchars($article['title']) ?>
                            </a>
                        </h3>
                        <time><?= htmlspecialchars($article['date'] ?? '') ?></time>
                        <p><?= htmlspecialchars($article['summary'] ?? '暂无摘要') ?></p>
                    </article>
                <?php
                endforeach; ?>
            </div>
        <?php
        endif; ?>
    </section>
</main>
