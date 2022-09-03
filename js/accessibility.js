export const accessibilityBtn = document.querySelector('#accessibility-link');
export const accessibilityContainer = document.querySelector('#accessibility-container');
export const increaseCharSizeBtn = document.querySelector('#increaseCharSizeBtn');
export const decreaseCharSizeBtn = document.querySelector('#decreaseCharSizeBtn');
export const defaultCharSizeBtn = document.querySelector('#defaultCharSizeBtn');
export const darkModeBtn = document.querySelector('#darkModeBtn');
const body = document.body;
let charSize = 18;

export function toggleDarkMode() {
    body.classList.toggle('dark-mode')
}

export function incrementCharSize() {
    charSize += 0.5;
    body.style.fontSize = charSize + "px";
}

export function decrementCharSize() {
    charSize -= 0.5;
    body.style.fontSize = charSize + "px";
}

export function defaultCharSize() {
    charSize = 18;
    body.style.fontSize = charSize + "px";
}
