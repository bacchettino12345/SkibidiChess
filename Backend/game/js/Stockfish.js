export async function callAPI(fen, depth) {
  const url = new URL('https://stockfish.online/api/s/v2.php');
  url.searchParams.append('fen', fen);
  url.searchParams.append('depth', depth);

  try {
    const response = await fetch(url);
    const data = await response.json();
    return data.bestmove.substring(9, 13);
  } catch (error) {
    console.error('Errore durante la chiamata API:', error);
    throw error;
  }
}
