my-php-site/
├── config/                     # 全局配置文件
│   └── app.php                 # 站点基础配置（名称、路径、密钥等）
├── content/                    # 存储所有文章与内容数据
│   ├── articles/               # 文章目录
│   │   ├── 2026-08-01-welcome.md
│   │   └── 2026-08-15-php-guide.md
│   ├── index.json              # 文章元数据索引文件（实现快速搜索的关键）
│   └── site_info.json          # 站点全局可视化配置数据
├── public/                     # Web 服务器根目录（唯一的公开目录）
│   ├── index.php               # 单点入口文件与主路由
│   ├── .htaccess               # Apache 伪静态重写配置（Nginx 需配置对应 rules）
│   ├── css/                    # 已编写好的 CSS 样式文件
│   │   └── style.css
│   ├── js/                     # 已编写好的 JS 脚本
│   │   ├── main.js             # 前台交互逻辑（搜索等）
│   │   └── admin.js            # 后端编辑器交互逻辑
│   └── uploads/                # 后台可视化编辑时上传的媒体资源
├── src/                        # 核心 PHP 业务逻辑代码
│   ├── Auth.php                # 管理员身份验证
│   ├── ContentManager.php      # MD/JSON 文件的增删改查
│   ├── MarkdownParser.php      # Parsing 逻辑 (如集成 Parsedown)
│   ├── Router.php              # 轻量级路由解析器
│   └── SearchEngine.php        # JSON 索引搜索逻辑
├── templates/                  # 视图模板文件（HTML 结构与 UI）
│   ├── admin/                  # 后台可视化编辑界面模板
│   │   ├── dashboard.php
│   │   ├── edit.php
│   │   └── login.php
│   ├── components/             # 可复用组件
│   │   ├── footer.php
│   │   ├── header.php
│   │   └── search-bar.php
│   ├── 404.php                 # 错误页面
│   ├── article.php             # 单篇文章展示页
│   └── home.php                # 首页
└── vendor/                     # Composer 第三方依赖（如 Parsedown）
    └── autoload.php
