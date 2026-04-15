import Alpine from "alpinejs";
import "preline";
import Chart from "chart.js/auto";

window.Alpine = Alpine;
window.Chart = Chart;
Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
    if (typeof window.HSStaticMethods?.autoInit === "function") {
        window.HSStaticMethods.autoInit();
        return;
    }

    if (typeof window.HSOverlay?.init === "function") {
        window.HSOverlay.init();
    }
});
