import { VirtualBoard } from "../game/VirtualBoard.js"
import { PhysicalBoard } from "../game/PhysicalBoard.js";

let startingFEN = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1";
let boardElement = document.getElementById("CheckerBoard");

const virtualBoard = new VirtualBoard();
virtualBoard.GenerateBoardFromFEN(startingFEN);

const physicalBoard = new PhysicalBoard(virtualBoard);



window.Reverseboard = function () {

    
    console.log(virtualBoard.CurrentFEN)
};







