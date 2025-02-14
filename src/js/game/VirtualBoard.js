import { Knight } from "./pieces/Knight.js";
import { Bishop } from "./pieces/Bishop.js"
import { Rook } from "./pieces/Rook.js"
import { Queen } from "./pieces/Queen.js"
import { King } from "./pieces/King.js"
import { Pawn } from "./pieces/Pawn.js"
import { callAPI } from "../../../Backend/game/js/Stockfish.js"
import { Helper } from "./Helpers.js";


const piecesClasses = {
    p: Pawn,
    n: Knight,
    b: Bishop,
    r: Rook,
    q: Queen,
    k: King
}


export class VirtualBoard{

    pieces = []
    isWhiteTurn = null

    gameMode = null

    CurrentFEN = null;

    physicalBoard = null

    constructor()
    {
        this.pieces = new Array(64).fill(null);
        this.isWhiteTurn = true;
        this.gameMode = document.body.getAttribute("mode");
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
        console.log(this.pieces)
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

            if(this.pieces[startSquare].type == "P" || "p")
                this.pieces[startSquare].firstMove = false;


            this.pieces[targetSquare] = this.pieces[startSquare];
            this.pieces[startSquare] = null;
            this.pieces[targetSquare].position = targetSquare;


            
            for(let piece of this.pieces)
            {
                if(piece !== null)
                    piece.calculateLegalMoves(this.pieces)
            }

            this.rebuildFEN();

            this.isWhiteTurn = !this.isWhiteTurn

            this.requestNextMove();

            return true;
        }
            
        return false;
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

    requestNextMove()
    {
        if(this.gameMode === "local" && !this.isWhiteTurn)
        {
            
            
            callAPI(this.CurrentFEN + " b - - 0 1", 12).then(response =>{
                let moves = response.split("")

                console.log(moves)

                let fileFrom = moves[0].charCodeAt(0) - 97
                let fileTo = moves[2].charCodeAt(0) - 97

                
                let rankFrom = 8 - parseInt(moves[1])
                let rankTo = 8 - parseInt(moves[3])

                let piece = this.pieces[Helper.toLinear([rankFrom, fileFrom])]

                this.physicalBoard.startPieceMoveAnimation(piece, fileFrom, rankFrom, fileTo, rankTo, 200)
            })
        }
    }
         
}