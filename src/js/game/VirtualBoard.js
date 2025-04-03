import { Knight } from "./pieces/Knight.js";
import { Bishop } from "./pieces/Bishop.js"
import { Rook } from "./pieces/Rook.js"
import { Queen } from "./pieces/Queen.js"
import { King } from "./pieces/King.js"
import { Pawn } from "./pieces/Pawn.js"
import { callAPI } from "../../../Backend/game/js/Stockfish.js"
import { Helper } from "./Helpers.js";
import { bus } from "./Bus.js";


const piecesClasses = {
    p: Pawn,
    n: Knight,
    b: Bishop,
    r: Rook,
    q: Queen,
    k: King
}

let eatenPieces = new Map();

const pieceValue = {
    p: 1,
    n: 3,
    b: 3,
    r: 5,
    q: 8,
    k: 15
}


export class VirtualBoard{

    pieces = []
    isWhiteTurn = null

    gameMode = null

    CurrentFEN = null;

    physicalBoard = null

    AIstrength = null


    promote = null

    constructor()
    {
        this.pieces = new Array(64).fill(null);
        this.isWhiteTurn = true;
        this.gameMode = document.body.getAttribute("mode");

        bus.on("moveAI", (color) => this.requestNextMove(color) )
    }

    GenerateBoardFromFEN(FEN)
    {
        this.CurrentFEN = FEN.split(" ")[0]

        this.pieces = new Array(64).fill(null);


        let PositionsFEN = FEN.split(" ")[0].split("/");

        let counter = 0;

        for(let Row of PositionsFEN)
        {
            for(let char of Row)
            {
                if(!isNaN(parseInt(char)))
                    counter += parseInt(char);
                else
                {
                    let isWhite = char === char.toUpperCase();
                    let pieceType = char.toLowerCase();

                    const pieceClass = piecesClasses[pieceType];

                    if(pieceClass)
                        this.pieces[counter] = new pieceClass(isWhite, counter);                        

                    counter++;
                }
            }
        }


        this.calculateLegalMoves()
    }


    calculateLegalMoves()
    {
        for(let piece of this.pieces)
        {
            if(piece !== null)
                piece.calculateLegalMoves(this.pieces);
        }
    }
    

    movePiece(startSquare, targetSquare)
    {
        if(startSquare !== null && this.getPieceAt(startSquare).legalMoves.includes(targetSquare))
        {


            const piece = this.getPieceAt(startSquare)


            if(this.pieces[startSquare].type.toLowerCase() == "p")
                this.pieces[startSquare].firstMove = false;


            if(this.pieces[targetSquare] !== null)
            {
                eatenPieces.set(this.pieces[targetSquare].type, (eatenPieces.get(this.pieces[targetSquare].type) || 0) + 1 ) 
                this.updateEat()
            }


            this.pieces[targetSquare] = piece;
            this.pieces[targetSquare].position = targetSquare;

            this.pieces[startSquare] = null;

            
            for(let piece of this.pieces)
            {
                if(piece !== null)
                    piece.calculateLegalMoves(this.pieces)
            }


            
            this.rebuildFEN();

            this.isWhiteTurn = !this.isWhiteTurn

            

            return true;
        }
            
        return false;
    }


    switchPiece(piecePos, promotionType)
    {
        
        const pieceClass = piecesClasses[promotionType]

        if(pieceClass)
            this.pieces[piecePos] = new pieceClass(this.pieces[piecePos].isWhite, piecePos)
        
        this.calculateLegalMoves()
    }

    async requestNextMove(color = "white")
    {
        const response = await callAPI(this.CurrentFEN + " " + color[0] +  " - - 0 1", this.AIstrength)
            
        let moves = response[0].split("")
        let fileFrom = moves[0].charCodeAt(0) - 97
        let fileTo = moves[2].charCodeAt(0) - 97
        
        let rankFrom = 8 - parseInt(moves[1])
        let rankTo = 8 - parseInt(moves[3])
        let startPos = Helper.toLinear([rankFrom, fileFrom])
        let piece = this.pieces[startPos]
        
        await this.physicalBoard.startPieceMoveAnimation(piece, fileFrom, rankFrom, fileTo, rankTo, 200)

        let finalPos = Helper.toLinear([rankTo, fileTo])

        if(response[1])
            this.switchPiece(finalPos, moves[4])

        bus.emit("move", startPos, finalPos)

        this.physicalBoard.RenderBoard()
        
   
    }

    promotion(type)
    {
        


        const pieceClass = piecesClasses[type];

        if(pieceClass)
            this.pieces[this.promote]  = new pieceClass(this.pieces[this.promote].isWhite, this.promote);  

        
        let promotePanel = document.getElementById("Promote")
        promotePanel.style.visibility = 'hidden'
        this.promote = null
        this.requestNextMove()
    }

    rebuildFEN()
    {
        let counter = 0;
        let newFEN = "";
    
        for (let i = 0; i < this.pieces.length; i++)
        {
            if (this.pieces[i]) 
            {
                if (counter > 0) 
                {
                    newFEN += counter; // Append empty square count
                    counter = 0;
                }
    
                newFEN += this.pieces[i].type; // Append piece symbol
            } 
            else 
            {
                counter++; // Count empty squares
            }
    
            // Handle row ending
            if ((i + 1) % 8 === 0) 
            {
                if (counter > 0) 
                {
                    newFEN += counter; // Append counter before row ends
                    counter = 0;
                }
    
                if (i !== this.pieces.length - 1) // Avoid extra "/" at the end
                    newFEN += "/";
            }
        }
 
        this.CurrentFEN = newFEN;
    }
    

    canPieceMove(startSquare, targetSquare)
    {
        if(startSquare !== null && this.getPieceAt(startSquare).legalMoves.includes(targetSquare))
        {
            return true;
        }

        return false;
    }

    getPieceAt(Index)
    {
        return this.pieces[Index]
    }
    

    
       
    isLowerCase(letter) {
        return letter === letter.toLowerCase();
    }

    
      
    getTotalCapturedValue(color) {
        let total = 0;

        if(color == 'b')
        {
            eatenPieces.forEach((value, key) =>{
                if(!this.isLowerCase(key))
                    total += pieceValue[key.toLowerCase()] * value
            })
        }
        else
        {
            eatenPieces.forEach((value, key) =>{
                if(this.isLowerCase(key))
                    total += pieceValue[key.toLowerCase()] * value
            })
        }


        return total;
    }


    updateEat()
    {
        let whiteContainer = document.getElementById("w-captured-pieces")
        whiteContainer.innerHTML = ''

        let blackContainer = document.getElementById("b-captured-pieces")
        blackContainer.innerHTML = ''

        
        let whiteCount = this.getTotalCapturedValue('w')
        let blackCount = this.getTotalCapturedValue('b')
        
        let wMaterialCount = document.getElementById("w-Material-count")
        let bMaterialCount = document.getElementById("b-Material-count")


        if(whiteCount > blackCount)
        {
            bMaterialCount.style.visibility = 'hidden'
            wMaterialCount.style.visibility = 'visible'
            wMaterialCount.textContent = "+" + (whiteCount - blackCount)
        }
        else if(blackCount > whiteCount)
        {
            wMaterialCount.style.visibility = 'hidden'
            bMaterialCount.style.visibility = 'visible'
            bMaterialCount.textContent = "+" + (blackCount - whiteCount)
        }
        


        eatenPieces.forEach((value, key) => {

            let color = this.isLowerCase(key) ? 'b' : 'w'

            let img = document.createElement("img")
            img.src = "../Assets/Images/CapturedPieces/" + key + value + color + ".png"
            
            this.isLowerCase(key) ? whiteContainer.appendChild(img) : blackContainer.appendChild(img)
            

        })
    }
}