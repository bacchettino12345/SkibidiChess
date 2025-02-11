export class Piece
{
    type = null;
    isWhite = null;
    position  = null;
    src = null;
    legalMoves = [];
    isMoving = false;

    constructor(type, isWhite, position){
        this.type = type;
        this.isWhite = isWhite;
        this.position = position;
        this.legalMoves = [];
        this.src = "PiecesIMG/" + FENtoIMGmap[this.type];
    }
}

const FENtoIMGmap = {
    p: "BPawn.png",
    P: "WPawn.png",
    n: "BKnight.png",
    N: "WKnight.png",
    b: "BBishop.png",
    B: "WBishop.png",
    r: "BRook.png",
    R: "WRook.png",
    q: "BQueen.png",
    Q: "WQueen.png",
    k: "BKing.png",
    K: "WKing.png"
};