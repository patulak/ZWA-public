function toggleMenu() {
    const menuUl = document.querySelector(".top_menu ul");
    menuUl.classList.toggle("show");

    const menu = document.querySelector(".top_menu");
    if (menu.style.flexDirection ==="column") {
        menu.style.flexDirection = "row-reverse";
    } else {
        menu.style.flexDirection = "column";
    }
}