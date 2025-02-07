import { Piece } from "./Piece.js";
import { Helper } from "../Helpers.js"

export class Knight extends Piece
{
    constructor(isWhite, position)
    {
       super(isWhite ? 'N' : 'n', isWhite, position);
    }

    calculateLegalMoves(VirtualBoard)
    {
        this.legalMoves = []
        const Coords = Helper.to2D(this.position)

        const Positions = [ [2, 1], [1, 2], [-2, 1], [1, -2], [-1, 2], [2, -1], [-2, -1], [-1, -2] ] ;

        for(let [rPos, cPos] of Positions)
        {
            let nextRow = Coords[0] + rPos;
            let nextColoumn = Coords[1] + cPos;
    
            if(nextRow >= 0 && nextRow < 8 && nextColoumn >= 0 && nextColoumn < 8)
            {
                let index = Helper.toLinear([nextRow, nextColoumn]);
    
                if(VirtualBoard[index] === null || VirtualBoard[index].isWhite !== this.isWhite)
                    this.legalMoves.push(index);
            }
        }
    }
}