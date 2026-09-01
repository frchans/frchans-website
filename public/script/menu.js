const button = document.getElementById("menuButton");
const menu = document.getElementById("popupMenu");

// 1. 汉堡按钮自身的点击事件：同时切换菜单和按钮的状态
button.addEventListener("click", function (e) {
    e.stopPropagation(); // 阻止冒泡，防止触发下面的 document 点击事件
    menu.classList.toggle("show");
    button.classList.toggle("is-active");
});

// 2. 点击页面任意空白处：一键关闭菜单并重置按钮
document.addEventListener("click", function () {
    menu.classList.remove("show");
    button.classList.remove("is-active"); /* 修复：改用 remove，防止误触发变色 */
});

// 3. 点击菜单内部的空白处或常规链接
menu.addEventListener("click", function (e) {
    // 如果点击的是普通的导航链接（不是特殊的 closeMenu），点击后也应该优雅关闭
    if (e.target.tagName === 'A' && !e.target.classList.contains('closeMenu')) {
        menu.classList.remove("show");
        button.classList.remove("is-active");
    } else {
        // 如果点击的是菜单里的空白处，阻止冒泡，让菜单保持打开状态（符合 Apple 体验）
        e.stopPropagation();
    }
});

// 4. “回到顶部”等特殊关闭按钮的逻辑
const closeMenuBtn = document.querySelector('.closeMenu');

if (closeMenuBtn && menu) {
    const handleClose = function(e) {
        // 同时重置菜单和按钮状态
        menu.classList.remove('show');
        button.classList.remove('is-active');

        // 平滑滚动回顶部
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    };

    closeMenuBtn.addEventListener('touchstart', function(e) {
        handleClose(e);
    }, { passive: true });

    closeMenuBtn.addEventListener('click', function(e) {
        e.preventDefault();
        handleClose(e);
    });
}