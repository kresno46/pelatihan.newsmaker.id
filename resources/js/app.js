import Alpine from "alpinejs";
import "preline";

window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
    window.HSOverlay?.init();
});
