import { VirtualBoard } from "../VirtualBoard.js"
import { PhysicalBoard } from "../PhysicalBoard.js";

export class gameMode
{
    constructor(random)
    {
        this.startingFEN = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1";

        this.localColor = random === 0 ? "black" : "white"
        this.otherColor = random === 0 ? "white" : "black"

        this.virtualBoard = new VirtualBoard();
        this.virtualBoard.GenerateBoardFromFEN(this.startingFEN);
        
        
        this.physicalBoard = new PhysicalBoard(this.virtualBoard, this.localIsWhite());
        this.virtualBoard.physicalBoard = this.physicalBoard

        this.setupEvents()

        window.addEventListener('resize', () => this.physicalBoard.resizeBoard(), false);
        
    }


    setupEvents() {
        // Use arrow functions to preserve 'this' context
        window.Reverseboard = () => {
            this.physicalBoard.isFlipped = !this.physicalBoard.isFlipped;
            this.physicalBoard.RenderBoard();
        };

        window.MoveSuggestion = () => {
            this.suggestion();
        };
    }
    
    runGame()
    {
        this.game.runGame()
    }

    localIsWhite()
    {
        return this.localColor === "white" ? false : true
    }



}