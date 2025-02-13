import {Helper} from "../../../src/js/game/Helpers.js"
  

export function callAPI(fen, depth) {
  const url = new URL('https://stockfish.online/api/s/v2.php');
  url.searchParams.append('fen', fen);
  url.searchParams.append('depth', depth);
  return fetch(url)
    .then(response => response.json())
    .then(data => data.bestmove.substring(9,13))
    .catch(error => {
      console.error('Errore:', error);
      throw error;
    });
}
