import { Game } from "../Game.js"
import {AI, Local} from "../Player.js"
import { callAPI } from "../../../../Backend/game/js/Stockfish.js"
import { gameMode } from "./GameMode.js"

export class AIWager extends gameMode
{
    constructor(localrandom = 1)
    {
        super(localrandom)

        this.p1 = new AI(this.localColor)
        this.p2 = new AI(this.otherColor)

        this.game = new Game(this.p1, this.p2)
    }

}