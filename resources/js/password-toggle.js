document.addEventListener("DOMContentLoaded", () => {
    const password = document.getElementById("password");
    const toggle = document.getElementById("togglePassword");
    const eyeOpen = document.getElementById("eyeOpen");
    const eyeClose = document.getElementById("eyeClose");

    if (!password || !toggle) return;

    toggle.addEventListener("click", () => {
        const isHidden = password.type === "password";

        password.type = isHidden ? "text" : "password";

        eyeOpen.classList.toggle("hidden");
        eyeClose.classList.toggle("hidden");
    });
});
