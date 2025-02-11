import { Knight } from "./pieces/Knight.js";
import { Bishop } from "./pieces/Bishop.js"
import { Rook } from "./pieces/Rook.js"
import { Queen } from "./pieces/Queen.js"
import { King } from "./pieces/King.js"
import { Pawn } from "./pieces/Pawn.js"


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

    constructor()
    {
        this.pieces = new Array(64).fill(null);
        this.isWhiteTurn = true;
    }

    GenerateBoardFromFEN(FEN)
    {
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
            this.pieces[targetSquare] = this.pieces[startSquare];
            this.pieces[startSquare] = null;
            this.pieces[targetSquare].position = targetSquare;
            
            for(let piece of this.pieces)
            {
                if(piece !== null)
                    piece.calculateLegalMoves(this.pieces)
            }
            
            return true;
        }
            
        return false;
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
         
}