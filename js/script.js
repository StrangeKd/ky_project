import { searchInput, prevBtn, nextBtn, accessBtn, artists, template, previousPage, nextPage, getToPage, getResponseTemplate } from "./artists.js";
import { accessibilityBtn, accessibilityContainer, increaseCharSizeBtn, decreaseCharSizeBtn, defaultCharSizeBtn, darkModeBtn, toggleDarkMode, incrementCharSize, decrementCharSize, defaultCharSize } from "./accessibility.js";

window.addEventListener('DOMContentLoaded', () => {

    accessibilityBtn.addEventListener("click", function () {
        accessibilityContainer.classList.toggle("show")
    });

    darkModeBtn.addEventListener("click", toggleDarkMode);
    increaseCharSizeBtn.addEventListener("click", incrementCharSize);
    decreaseCharSizeBtn.addEventListener("click", decrementCharSize);
    defaultCharSizeBtn.addEventListener("click", defaultCharSize);

    getResponseTemplate(template);
    prevBtn.addEventListener("click", previousPage, false);
    nextBtn.addEventListener("click", nextPage, false);
    accessBtn.addEventListener("click", getToPage, false);


    /* hide cards that doesn't contain input typed letters to make a search feature */
    searchInput.addEventListener('input', (e) => {
        const value = e.target.value.toLowerCase();
        artists.forEach(artist => {
            const isVisible = artist.name.toLowerCase().includes(value);
            artist.element.classList.toggle("hide", !isVisible);
        })
    })
})