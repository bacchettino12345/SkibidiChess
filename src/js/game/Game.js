import { gameState } from "./GameInfo.js"

    export class Game
    {
        constructor(player1, player2)
        {
            this.currentTurn = "white"

            this.players = {
                white : player1,
                black : player2
            }

            this.gameOver = false
        }

        switchTurn()
        {
            this.currentTurn = this.currentTurn === "white" ? "black" : "white"
        }

        async runGame()
        {
            while(!this.gameOver)
            {
                const currentPlayer = await this.players[this.currentTurn]
                const move = await currentPlayer.playTurn()

                if(move)
                {
                    this.switchTurn()
                    gameState.turn = this.currentTurn
                }
            }
        }
        
    }