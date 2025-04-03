import { AIWager } from "./GameModes/AIvsAI.js";
import { SinglePlayer } from "./GameModes/SinglePlayer.js";


function getRandomInt(max) {
    return Math.floor(Math.random() * max);
}

const urlParams = new URLSearchParams(window.location.search);

const settings = {
  difficulty: urlParams.get('difficulty') || 3, // Default: 3
  color: urlParams.get('color') || 2 // Default: random
};

console.log(settings)


let Localcolor = +settings.color === 2 ? getRandomInt(2) : +settings.color



let gameMode = new SinglePlayer(Localcolor, settings.difficulty)

gameMode.runGame()


window.promote = function(type) {
    virtualBoard.promotion(type)

    physicalBoard.RenderBoard()
}


