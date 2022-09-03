import Connection from "./Connection.js";

export default class Template extends Connection {
    constructor(url, currentPage, limitCards, html) {
        super();
        this.url = url;
        this.currentPage = currentPage;
        this.limitCards = limitCards;
        this.html = html;
        this.sizedData = [];
        this.artistsCards = [];
        this.data = [];
        this.template = document.querySelector('[data-artist-template]');
        this.htmlPage = document.querySelector('#current-page');
    }

    /* get the API response and return a 20 elements promise object */
    async getSizedData() {
        this.data = await super.fetchData(this.url);
        this.sizedData = this.data.filter((row, index) => {
            let start = (this.currentPage - 1) * this.limitCards;
            let end = this.currentPage * this.limitCards;

            if (index >= start && index < end) {
                return true;
            }
        })
        return this.sizedData;
    }

    /* get the 20 elements promise object and generate html with it then return html cards */
    async renderHtml() {
        await this.getSizedData();
        this.htmlPage.innerHTML = `${this.currentPage}`;
        this.artistsCards = this.sizedData.map(artist => {
            const card = this.template.content.cloneNode(true).children[0];
            const name = card.querySelector('[data-name]');
            const img = card.querySelector('[data-img]');
            const link = card.querySelector('[data-link]');
            name.textContent = artist.name;
            img.children[0].src = artist.image[2]['#text'];
            link.href = artist.url;
            link.target = "_blank";
            this.html.append(card);
            return { name: artist.name, img: artist.image[2]['#text'], element: card };
        })
        return this.artistsCards;
    }
}
