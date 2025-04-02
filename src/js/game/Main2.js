import { AIWager } from "./GameModes/AIvsAI.js";
import { SinglePlayer } from "./GameModes/SinglePlayer.js";


function getRandomInt(max) {
    return Math.floor(Math.random() * max);
}





let gameMode = new SinglePlayer(getRandomInt(2))

gameMode.runGame()


window.promote = function(type) {
    virtualBoard.promotion(type)

    physicalBoard.RenderBoard()
}


