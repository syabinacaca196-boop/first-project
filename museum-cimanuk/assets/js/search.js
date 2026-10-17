document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".nav-search").forEach(form => {
        const input = form.querySelector("input[name='q']");
        if (!input) return;

        form.addEventListener("submit", function (event) {
            const keyword = input.value.trim();
            if (!keyword) {
                event.preventDefault();
                input.focus();
            }
        });
    });
});