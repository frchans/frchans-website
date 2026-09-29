<link rel="stylesheet" href="/css/helloContainer.css">
<link rel="stylesheet" href="/css/MainLoopsTemp.css">


<!--<section class="hero-section">-->
<!--            <p>Meet your future here.</p>-->
<!--    <div class="hello-container">-->
<!--        <span class="greeting lang-1">We insist on Open-Source</span>-->
<!--        <span class="greeting lang-2">We insist on Seeking and Innovation</span>-->
<!--        <span class="greeting lang-3">We insist on Environment-Friendly</span>-->
<!--        <span class="greeting lang-4">We are Developer</span>-->
<!--        <span class="greeting lang-5">We are Designer</span>-->
<!--        <span class="greeting lang-6">We are Artist</span>-->
<!--        <span class="greeting lang-7">We are Software Engineer</span>-->
<!--        <span class="greeting lang-8">We are Hardware Engineer</span>-->
<!--    </div>-->
<!--</section>-->
<section class="text-mainLoopTemp">
    <div class="text-mainLoopTemp">
        <p class="text-mainLoopTemp">我们的内容正在建设中，敬请期待.</p>
    </div>
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
