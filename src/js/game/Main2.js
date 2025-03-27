import { VirtualBoard } from "../game/VirtualBoard.js"
import { PhysicalBoard } from "../game/PhysicalBoard.js";
import { callAPI } from "../../../Backend/game/js/Stockfish.js"

let startingFEN = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1";
let boardElement = document.getElementById("CheckerBoard");

const virtualBoard = new VirtualBoard();
virtualBoard.GenerateBoardFromFEN(startingFEN);

const physicalBoard = new PhysicalBoard(virtualBoard);
virtualBoard.physicalBoard = physicalBoard

window.addEventListener('resize', resizeCanvas, false);

let playerIsWhite = true

function resizeCanvas() {
    physicalBoard.resizeBoard();
}

window.promote = function(type) {
    virtualBoard.promotion(type)

    physicalBoard.RenderBoard()
}


window.Reverseboard = function () {
    physicalBoard.isFlipped = !physicalBoard.isFlipped;

    physicalBoard.RenderBoard();
};

window.MoveSuggestion = function () {


    if(virtualBoard.isWhiteTurn === playerIsWhite)
    {

        callAPI(virtualBoard.CurrentFEN + " w - - 0 1", 15).then(response =>{
    
            let moves = response.split("")
            console.log(moves)
    
            let fileFrom = moves[0].charCodeAt(0) - 97
            let fileTo = moves[2].charCodeAt(0) - 97
            
            let rankFrom = 8 - parseInt(moves[1])
            let rankTo = 8 - parseInt(moves[3])
    
    
            physicalBoard.squareFrom = [rankFrom, fileFrom]
            physicalBoard.squareTo = [rankTo, fileTo]
            physicalBoard.renderSuggestion = true
    
            physicalBoard.RenderBoard()
        })
    }
}


