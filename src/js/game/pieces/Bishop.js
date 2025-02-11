import { Piece } from "./Piece.js";
import { Helper } from "../Helpers.js"

export class Bishop extends Piece
{
    constructor(isWhite, position)
    {
       super(isWhite ? 'B' : 'b', isWhite, position) ;
    }

    calculateLegalMoves(VirtualBoard)
    {
        this.legalMoves = []
        const Coords = Helper.to2D(this.position)

        let Directions = [[1, 1], [1, -1], [-1, 1], [-1, -1]];

        for(let [DirectionRow, directionColoumn] of Directions)
        {
            let nextRow = Coords[0] + DirectionRow;
            let nextColoumn = Coords[1] + directionColoumn;
        
            while(nextRow >= 0 && nextRow < 8 && nextColoumn >= 0 && nextColoumn < 8)
            {
                let index = Helper.toLinear([nextRow, nextColoumn]);
        
                if(VirtualBoard[index] === null)
                {
                    this.legalMoves.push(index)
                }
                else
                {
                    if(VirtualBoard[index].isWhite !== this.isWhite)
                        this.legalMoves.push(index)
                    
                    break;
                }
        
                nextColoumn += directionColoumn;
                nextRow += DirectionRow;
            }
        } 

        
    }
}