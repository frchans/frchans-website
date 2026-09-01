    <main class="container">
        <section class="hero-section">
            <p>求是，创新</p>
        </section>

        <section class="article-list">
            <h2>最新文章</h2>
            <?php if (empty($articles)): ?>
                <p class="no-articles">暂无发布的内容</p>
            <?php else: ?>
                <div class="grid">
                    <?php foreach ($articles as $article): ?>
                        <article class="article-card">
                            <h3>
                                <a href="/article/<?= htmlspecialchars($article['slug']) ?>">
                                    <?= htmlspecialchars($article['title']) ?>
                                </a>
                            </h3>
                            <time><?= htmlspecialchars($article['date'] ?? '') ?></time>
                            <p><?= htmlspecialchars($article['summary'] ?? '暂无摘要') ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>