export default class Connection {
    constructor() {}

    /* return 999 elements promise object from API endpoint */
    async fetchData(url) {
        const response = fetch(url);
        const result = await response;
        const data = await result.json();
        let artists = await data.artists.artist;
        return artists;
    }
}