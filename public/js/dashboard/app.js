document.addEventListener("DOMContentLoaded", function () {
    const menuBar = document.getElementById("menu-bar");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");

    menuBar.addEventListener("click", function () {
        const isClosed = sidebar.classList.contains("-translate-x-full");

        if (isClosed) {
            sidebar.classList.remove("-translate-x-full");
            overlay.classList.remove("opacity-0", "pointer-events-none");
        } else {
            sidebar.classList.add("-translate-x-full");
            overlay.classList.add("opacity-0", "pointer-events-none");
        }
    });

    overlay.addEventListener("click", function () {
        sidebar.classList.add("-translate-x-full");
        overlay.classList.add("opacity-0", "pointer-events-none");
    });

    window.addEventListener("resize", function () {
        if (window.innerWidth >= 768) {
            sidebar.classList.remove("-translate-x-full");
            overlay.classList.add("opacity-0", "pointer-events-none");
        } else {
            sidebar.classList.add("-translate-x-full");
        }
    });
});
