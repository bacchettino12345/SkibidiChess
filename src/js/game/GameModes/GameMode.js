import { VirtualBoard } from "../VirtualBoard.js"
import { PhysicalBoard } from "../PhysicalBoard.js";

export class gameMode
{
    constructor(random)
    {
        this.startingFEN = "8/3p4/8/8/8/8/5P2/8 w - - 0 1";

        this.localColor = random === 0 ? "black" : "white"
        this.otherColor = random === 0 ? "white" : "black"

        

        this.virtualBoard = new VirtualBoard();
        this.virtualBoard.GenerateBoardFromFEN(this.startingFEN);
        
        
        this.physicalBoard = new PhysicalBoard( this.virtualBoard, this.localIsWhite() );
        this.virtualBoard.physicalBoard = this.physicalBoard

        this.setupEvents()
        this.buildDom()

        window.addEventListener('resize', () => this.physicalBoard.resizeBoard(), false);

    }

    buildDom()
    {
        const promotePanel = document.getElementById("Promote");
        
        const images = promotePanel.querySelectorAll("img");
        
        images.forEach(element => {

          const colorChar = this.localColor === "white" ? '1' : '0'; 
        
          const currentType = element.src.split("/").pop()[0];
        
          element.src = `../Assets/Images/${currentType}${colorChar}.svg`;
        });
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