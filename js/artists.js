import Template from "./modules/Template.js";

const BASE_URL = 'https://ws.audioscrobbler.com/2.0/?method=chart.gettopartists&api_key=c623d3d530a2be8700e5fde8192a8967&limit=999&format=json'

const container = document.querySelector('#artist-cards-container');
export const searchInput = document.querySelector('#search-artist');
export const prevBtn = document.querySelector('#prev-btn');
export const nextBtn = document.querySelector('#next-btn');
export const accessBtn = document.querySelector('#access-btn');
const accessPage = document.querySelector('#access-page');
let currentPage = 1;
const limitCards = 20;
const limitData = 999;
export let artists = [];
export let template = new Template(BASE_URL, currentPage, limitCards, container);


/* get a new promise object containing 20 artists cards and return it in array*/
export async function getResponseTemplate(instance) {
    artists = await instance.renderHtml();
    return artists;
}

/* clear html and call function which get promise object */
function newTemplate() {
    container.innerHTML = '';
    template = new Template(BASE_URL, currentPage, limitCards, container);
    getResponseTemplate(template);
    window.scrollTo(0, 0);
}

/* go one page backwards */
export function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        newTemplate();
    }
}

/* go one page forwards */
export function nextPage() {
    if ((currentPage * limitCards) < limitData) {
        currentPage++;
        newTemplate();
    }
}

/* go on user picked page */
export function getToPage() {
    let value = parseInt(accessPage.value);
    if (value !== NaN && value >= 1 && value <= 50 && value !== currentPage) {
        currentPage = value;
        accessPage.value = '';
        newTemplate();
    }
}
