import { Helper } from "../Helpers.js";
import { Piece } from "./Piece.js";

export class Pawn extends Piece
{
    firstMove = null;
    direction = null

    constructor(isWhite, position)
    {
       super(isWhite ? 'P' : 'p', isWhite, position);
       this.firstMove = true;
       let Coords = Helper.to2D(this.position);
       this.direction = Coords[0] === 6 ? -1 : 1;
    }

    calculateLegalMoves(VirtualBoard)
    {
        this.legalMoves = [];

        let Coords = Helper.to2D(this.position);

        let startRow = this.isWhite ? 6 : 1;

        let forwardMove = [Coords[0] + this.direction, Coords[1]];

        if(Coords[0] !== 0 && Coords[0] !== 7)
        {
            if(VirtualBoard[Helper.toLinear(forwardMove)] === null)
            {
                this.legalMoves.push(Helper.toLinear(forwardMove))
    
                if(this.firstMove)
                {
                    let doubleMove = [Coords[0] + 2 * this.direction, Coords[1]];
                    if(VirtualBoard[Helper.toLinear(doubleMove)] === null)
                        this.legalMoves.push(Helper.toLinear(doubleMove))
                }
            }
    
            //Capture Logic
            let captureLeft = [Coords[0] + this.direction , Coords[1] - 1];
    
            if(this.isValidCapture(captureLeft, VirtualBoard))
                this.legalMoves.push(Helper.toLinear(captureLeft));
    
            let captureRight = [Coords[0] + this.direction, Coords[1] + 1];
    
            if(this.isValidCapture(captureRight, VirtualBoard))
                this.legalMoves.push(Helper.toLinear(captureRight));

        }


    }

    isValidCapture(Coords, VirtualBoard)
    {
        if(VirtualBoard[Helper.toLinear(Coords)] !== null)
            if((Coords[1] >= 0 && Coords[1] < 8) && VirtualBoard[Helper.toLinear(Coords)].isWhite !== this.isWhite)
                return true;

        return false;
    }
}