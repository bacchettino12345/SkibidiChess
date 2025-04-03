import { Game } from "../Game.js"
import {AI, Local} from "../Player.js"
import { callAPI } from "../../../../Backend/game/js/Stockfish.js"
import { gameMode } from "./GameMode.js"

export class SinglePlayer extends gameMode
{
    constructor(localrandom = 1, difficulty = 3)
    {
        super(localrandom)

        this.p1 = new Local(this.localColor)
        this.p2 = new AI(this.otherColor)

        this.game = new Game(this.p1, this.p2)

        this.virtualBoard.AIstrength = difficulty * 3
    }

    suggestion()
    {

        callAPI(this.virtualBoard.CurrentFEN + " " + this.p1.color[0] + " - - 0 1", 15).then(response =>{
    
            let moves = response.split("")

    
            let fileFrom = moves[0].charCodeAt(0) - 97
            let fileTo = moves[2].charCodeAt(0) - 97
            
            let rankFrom = 8 - parseInt(moves[1])
            let rankTo = 8 - parseInt(moves[3])
    
    
            this.physicalBoard.squareFrom = [rankFrom, fileFrom]
            this.physicalBoard.squareTo = [rankTo, fileTo]
            this.physicalBoard.renderSuggestion = true
    
            this.physicalBoard.RenderBoard()
        })
    }
}