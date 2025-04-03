export async function callAPI(fen, depth) {
  const url = new URL('https://stockfish.online/api/s/v2.php');
  url.searchParams.append('fen', fen);
  url.searchParams.append('depth', depth);

  try {
    const response = await fetch(url);
    const data = await response.json();
    console.log(data.bestmove)

    const bestmove = data.bestmove.split(" ")
    const promotion = bestmove[1].length === 5 ? true : false
    
    return [bestmove[1], promotion];

  } catch (error) {
    console.error('Errore durante la chiamata API:', error);
    throw error;
  }
}
