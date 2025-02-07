import { Helper } from "../Helpers.js";
import { Piece } from "./Piece.js";

export class Pawn extends Piece
{
    firstMove = null;

    constructor(isWhite, position)
    {
       super(isWhite ? 'P' : 'p', isWhite, position);
    }

    calculateLegalMoves(VirtualBoard)
    {
        this.legalMoves = [];

        let Coords = Helper.to2D(this.position);
        let direction = this.isWhite ? -1 : 1;
        let startRow = this.isWhite ? 6 : 1;
        this.firstMove = Coords[0] === startRow ? true : false;

        let forwardMove = [Coords[0] + direction, Coords[1]];


        if(VirtualBoard[Helper.toLinear(forwardMove)] === null)
        {
            this.legalMoves.push(Helper.toLinear(forwardMove))

            if(this.firstMove)
            {
                let doubleMove = [Coords[0] + 2 * direction, Coords[1]];
                if(VirtualBoard[Helper.toLinear(doubleMove)] === null)
                    this.legalMoves.push(Helper.toLinear(doubleMove))
            }
        }

        //Capture Logic
        let captureLeft = [Coords[0] + direction , Coords[1] - 1];

        if(this.isValidCapture(captureLeft, VirtualBoard))
            this.legalMoves.push(Helper.toLinear(captureLeft));

        let captureRight = [Coords[0] + direction, Coords[1] + 1];

        if(this.isValidCapture(captureRight, VirtualBoard))
            this.legalMoves.push(Helper.toLinear(captureRight));

    }

    isValidCapture(Coords, VirtualBoard)
    {
        if(VirtualBoard[Helper.toLinear(Coords)] !== null)
            if((Coords[1] >= 0 && Coords[1] < 8) && VirtualBoard[Helper.toLinear(Coords)].isWhite !== this.isWhite)
                return true;

        return false;
    }
}