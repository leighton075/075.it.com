document.addEventListener("DOMContentLoaded", () => {
    const toggleButton = document.getElementById("theme-toggle");

    // Check the saved theme preference from localStorage
    if (localStorage.getItem("theme") === "light") {
        document.body.classList.add("light-mode");
        document.body.classList.remove("dark-mode");
    } else {
        document.body.classList.add("dark-mode");
        document.body.classList.remove("light-mode");
    }

    // Toggle theme when the button is clicked
    toggleButton.addEventListener("click", () => {
        if (document.body.classList.contains("dark-mode")) {
            document.body.classList.remove("dark-mode");
            document.body.classList.add("light-mode");
            localStorage.setItem("theme", "light"); // Save to localStorage
        } else {
            document.body.classList.remove("light-mode");
            document.body.classList.add("dark-mode");
            localStorage.setItem("theme", "dark"); // Save to localStorage
        }
    });
});
