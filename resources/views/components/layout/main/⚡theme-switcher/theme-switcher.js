const toggle = document.getElementById("theme-toggle");

toggle.addEventListener("change", () => {

    const theme = toggle.checked ? "dark" : "light";

    document.documentElement.setAttribute("data-theme", theme);

    localStorage.setItem("theme", theme);

});

const savedTheme = localStorage.getItem("theme") || "light";

document.documentElement.setAttribute("data-theme", savedTheme);

toggle.checked = savedTheme === "dark";
