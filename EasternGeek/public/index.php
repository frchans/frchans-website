<?php

declare(strict_types=1);

session_start();

define('ROOT_PATH', dirname(__DIR__));
const CONTENT_PATH = ROOT_PATH . '/content';
const TEMPLATES_PATH = ROOT_PATH . '/pages';

// 简单自动加载机制
spl_autoload_register(function ($class)
{
    // 兼容 namespace App\Router;
    $classPath = str_replace('App\\', '', $class);
    $file = ROOT_PATH . '/src/' . str_replace('\\', '/', $classPath) . '.php';
    if (file_exists($file))
    {
        require_once $file;
    }
});

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

$staticFilePath = __DIR__ . $requestUri;
if ($requestUri !== '/' && file_exists($staticFilePath) && !is_dir($staticFilePath))
{
    return false;
}

function render(string $template, array $data = []): void
{
    extract($data);
    $templateFile = TEMPLATES_PATH . '/' . ltrim($template, '/');

    if (!file_exists($templateFile))
    {
        http_response_code(404);
        $templateFile = TEMPLATES_PATH . '/404.php';
    }

    $viewPath = $templateFile;
    require TEMPLATES_PATH . '/main.php';
    exit;
}

function jsonResponse(array $data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

$router = new App\Router();

$router->get('/', function ()
{
    $indexPath = CONTENT_PATH . '/index.json';
    $articles = file_exists($indexPath) ? json_decode(file_get_contents($indexPath), true) : [];

    usort($articles, function ($a, $b)
    {
        return strtotime($b['date'] ?? '0') <=> strtotime($a['date'] ?? '0');
    });

    render('home.php', [
        'pageTitle' => '首页 - EasternGeek',
        'articles' => $articles
    ]);
});

$router->get('/article/{slug}', function ($slug)
{
    $filePath = CONTENT_PATH . "/articles/{$slug}.md";

    if (!file_exists($filePath))
    {
        render('404.php', ['pageTitle' => '页面未找到 - 404']);
    }

    $rawContent = file_get_contents($filePath);
    $meta = [];
    $content = $rawContent;

    if (preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)/s', $rawContent, $metaMatches))
    {
        $meta = json_decode($metaMatches[1], true) ?? [];
        $content = $metaMatches[2];
    }

    render('article.php', [
        'pageTitle' => ($meta['title'] ?? '文章详情') . ' - FRChans Tech ++',
        'slug' => $slug,
        'meta' => $meta,
        'content' => $content
    ]);
});

$router->get('/api/search', function ()
{
    $query = mb_strtolower(trim($_GET['q'] ?? ''));
    if (empty($query))
    {
        jsonResponse([]);
    }

    $indexPath = CONTENT_PATH . '/index.json';
    $articles = file_exists($indexPath) ? json_decode(file_get_contents($indexPath), true) : [];

    $results = array_filter($articles, function ($article) use ($query)
    {
        $titleMatch = mb_strpos(mb_strtolower($article['title'] ?? ''), $query) !== false;
        $summaryMatch = mb_strpos(mb_strtolower($article['summary'] ?? ''), $query) !== false;
        return $titleMatch || $summaryMatch;
    });

    jsonResponse(array_values($results));
});

$router->get('/admin', function ()
{
    if (!($_SESSION['is_admin'] ?? false))
    {
        render('admin/login.php', ['pageTitle' => '管理员登录']);
    }
    $indexPath = CONTENT_PATH . '/index.json';
    $articles = file_exists($indexPath) ? json_decode(file_get_contents($indexPath), true) : [];

    render('admin/dashboard.php', [
        'pageTitle' => '后台管理',
        'articles' => $articles
    ]);
});

$router->get('/admin/edit', function ()
{
    if (!($_SESSION['is_admin'] ?? false))
    {
        header('Location: /admin');
        exit;
    }

    $slug = $_GET['slug'] ?? '';
    $articleData = ['title' => '', 'summary' => '', 'content' => ''];

    if ($slug && file_exists(CONTENT_PATH . "/articles/{$slug}.md"))
    {
        $raw = file_get_contents(CONTENT_PATH . "/articles/{$slug}.md");
        if (preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)/s', $raw, $matches))
        {
            $meta = json_decode($matches[1], true) ?? [];
            $articleData = array_merge($meta, ['content' => $matches[2]]);
        }
    }

    render('admin/edit.php', [
        'pageTitle' => '编辑文章',
        'slug' => $slug,
        'article' => $articleData
    ]);
});

$router->post('/api/admin/save', function ()
{
    if (!($_SESSION['is_admin'] ?? false))
    {
        jsonResponse(['error' => 'Unauthenticated'], 401);
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $slug = $input['slug'] ?? preg_replace('/[^a-z0-9\-]/', '', strtolower($input['title']));

    if (!$slug || empty($input['title']))
    {
        jsonResponse(['error' => 'Title and slug are required'], 400);
    }

    $metaJson = json_encode([
                                'title' => $input['title'],
                                'summary' => $input['summary'] ?? '',
                                'date' => $input['date'] ?? date('Y-m-d H:i:s'),
                                'slug' => $slug
                            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    $fileContent = "---\n{$metaJson}\n---\n\n" . ($input['content'] ?? '');
    file_put_contents(CONTENT_PATH . "/articles/{$slug}.md", $fileContent);

    $indexPath = CONTENT_PATH . '/index.json';
    $articles = file_exists($indexPath) ? json_decode(file_get_contents($indexPath), true) : [];

    $articles[$slug] = [
        'slug' => $slug,
        'title' => $input['title'],
        'summary' => $input['summary'] ?? '',
        'date' => $input['date'] ?? date('Y-m-d H:i:s')
    ];
    file_put_contents($indexPath, json_encode($articles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    jsonResponse(['success' => true, 'slug' => $slug]);
});

$router->dispatch($requestUri, $requestMethod);