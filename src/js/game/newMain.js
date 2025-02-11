import { Knight } from "./pieces/Knight.js";

let Board = document.getElementById("CheckerBoard");
let startingFEN = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1";
let VirtualBoard = new Array(64).fill(null);
let pgnMoves = [];
let PGN = "";
let whiteTurn = true;



//-------------------PIECES UTILITIES--------------------
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

class pezzo{
    constructor(type, isWhite, position, firstMove){
        this.type = type;
        this.isWhite = isWhite;
        this.position = position;
        this.legalMoves = [];
        this.firstMove = firstMove;

        this.src = "PiecesIMG/" + FENtoIMGmap[this.type];
    }
}
//-------------------------------------------------------

function ReadFEN(){
    
    let FEN = document.getElementById("txtFEN").value

    let child = Board.lastElementChild
    while(child)
    {
        Board.removeChild(child);
        child = Board.lastElementChild
    }

    buildBoard()
    clearMovePanel()
    
    
    PositionPiecesFromFEN(FEN);
    calculateLegalMoves();          
}

function isValidPosition(pos)
{
    return pos >= 0 && pos < 64
}

function drawCircles(event)
{
    if(event.currentTarget.id !== null)
    {
        const buttonIndex = event.currentTarget.id.slice(4);
    
        let circles = document.querySelectorAll(".circle")
    
        circles.forEach(element => {
            element.remove();
        });
    
        for(let i = 0; i < VirtualBoard[buttonIndex].legalMoves.length; i++)
        {

            const circle = document.createElement("img")
            circle.src = "PiecesIMG/Circle.svg";
            circle.style.position = "absolute"
            circle.className = "circle"
        
            circle.style.top = "50%";
            circle.style.left = "50%";
            circle.style.transform = "translate(-50%, -50%)";
            
            circle.style.maxWidth = "55%"
            circle.style.maxHeight = "55%"
            circle.style.opacity = "85%"
            circle.style.zIndex = 1
            let elem = document.getElementById("cell" + VirtualBoard[buttonIndex].legalMoves[i]);
            elem.append(circle)
            
            
        }

    }

}

function addMoveToMovesPanel(move, counter)
{
    let moveBoard = document.getElementById("MovesPanel");
    
    let div = document.createElement("div");
    div.style.width = "100%";


    if(counter % 2 === 0)
        div.style.backgroundColor = "#2A2926";
    else
        div.style.backgroundColor = "#262522"


    let text = document.createElement("p");
    text.id = "movesText";
    text.style.fontSize = "13px";
    let MoveNumber = document.createElement("p");
    MoveNumber.id = "movesText";
    MoveNumber.style.fontSize = "10px";
    MoveNumber.style.color = "#7B7A78";
    MoveNumber.style.width = "10px"


    text.textContent = move[1];
    MoveNumber.textContent = counter + ".";
    

    div.append(MoveNumber);
    div.append(text)

    moveBoard.append(div)
}

function clearMovePanel()
{
    let MovePanel = document.getElementById("MovesPanel");
    let moves = MovePanel.querySelectorAll("h5");

    moves.forEach(element => {
        element.remove()
    });

    pgnMoves = []
}

//Build Board, just cells and cell numbers 
function buildBoard()
{
    for(let i = 0; i < 8; i++){
        for(let j = 0; j < 8; j++){
    
            let cellNumber = i * 8 + j;
    
            const cell = document.createElement("button");
            
            if(j === 0)
            {
                const number = document.createElement("h2");
                number.id = "NumberCoords"
                number.textContent = 8 - i;
                
                cell.append(number);
            }
            if(i === 7)
            {
                const letter = document.createElement("h2");
                letter.id = "LetterCoords"
                letter.textContent = String.fromCharCode(j + 97) ;
                
                cell.append(letter);
            }
            
            
            cell.className = "cell";
            cell.id = "cell" + cellNumber;
            cell.style.backgroundColor = "black";
            cell.style.position = "relative"
            
            cell.addEventListener('click', drawCircles);
            
    
            if((i + j) % 2 === 0)
            {
                cell.style.backgroundColor = "#908F8D";
                cell.style.boxShadow = "0px 0px 10px  rgb(39, 39, 39)"
                cell.style.zIndex = 1
            }
            else
            {
                cell.style.backgroundColor = "#6E6D6B";
            }

            cell.style.borderStyle = "none"
                
    
            Board.append(cell)
        }
    }
}

//Position pieces from a FEN 
function createBoardPiece(pieceType)
{
    let image = document.createElement("img");

    image.src = "PiecesIMG/" + FENtoIMGmap[pieceType];
    image.style.position = "absolute"
    image.style.transform = "translate(-50%, -50%)";
    image.style.maxWidth = "100%"
    image.style.maxHeight = "100%"
    image.style.zIndex = 2;
    image.style.pointerEvents = "none"

    return image;
}

function PositionPiecesFromFEN(FEN)
{
    VirtualBoard = new Array(64).fill(null);
    

    let PositionsFEN = FEN.split(" ")[0].split("/");
    let counter = 0;

    for(let i = 0; i < 8; i++)
    {
        let Row = PositionsFEN[i];

        for(let j = 0; j < Row.length; j++)
        {   
            if(!isNaN(parseInt(Row[j]))){
                counter += parseInt(Row[j]);
            }
            else
            {
                let cell = document.getElementById("cell" + counter)
                VirtualBoard[counter] = new pezzo(Row[j], /^[A-Z]$/.test(Row[j]), counter, true);
                
                let piece = createBoardPiece(Row[j]);
                
                
                cell.append(piece)
    
                counter++;
            }
        }
    }
    
    console.log(VirtualBoard);
}

//--------------CALCULATING PIECES POSSIBLE MOVES--------------------
function calculatePawnMoves(i)
{
    let isWhite = VirtualBoard[i].isWhite
    let firstMove = VirtualBoard[i].firstMove

    let columnPos = i % 8;


    if(isWhite === false)
    {
        if(firstMove === true && VirtualBoard[i+16] === null && VirtualBoard[i + 8] === null)
            VirtualBoard[i].legalMoves.push(VirtualBoard[i].position + 16)

        if(VirtualBoard[i+8] === null)
            VirtualBoard[i].legalMoves.push(VirtualBoard[i].position + 8)

        //capture
        if(VirtualBoard[i + 9] !== null  && isValidPosition(i + 9) && columnPos !== 7)
            if(VirtualBoard[i + 9].isWhite !== isWhite)
                VirtualBoard[i].legalMoves.push(VirtualBoard[i].position + 9)

        if(VirtualBoard[i + 7] !== null  && isValidPosition(i + 7) && columnPos !== 0)
            if(VirtualBoard[i + 7].isWhite !== isWhite)
                VirtualBoard[i].legalMoves.push(VirtualBoard[i].position + 7)
    }
    else
    {
        if(firstMove === true && VirtualBoard[i-16] === null && VirtualBoard[i - 8] === null)
            VirtualBoard[i].legalMoves.push(VirtualBoard[i].position - 16)

        if(VirtualBoard[i-8] === null)
            VirtualBoard[i].legalMoves.push(VirtualBoard[i].position - 8)


        //capture
        if(VirtualBoard[i - 9] !== null && isValidPosition(i - 9) && columnPos !== 0)
        {
            if(VirtualBoard[i - 9].isWhite !== isWhite)
                VirtualBoard[i].legalMoves.push(VirtualBoard[i].position - 9)
        }

        if(VirtualBoard[i - 7] !== null && isValidPosition(i - 7) && columnPos !== 7)
        {
            if(VirtualBoard[i - 7].isWhite !== isWhite)
                VirtualBoard[i].legalMoves.push(VirtualBoard[i].position - 7)
        }
    }

    
}

function calculateKnightMoves(i)
{
    let isWhite = VirtualBoard[i].isWhite
    const columnPosition = i % 8;
    const rowPosition = Math.floor(i / 8);


    const Positions = [[2, 1], [1, 2], [-2, 1], [1, -2],[-1, 2], [2, -1],[-2, -1],[-1, -2]];

    for(let [rPos, cPos] of Positions)
    {
        let nextRow = rowPosition + rPos;
        let nextColoumn = columnPosition + cPos;

        if(nextRow >= 0 && nextRow < 8 && nextColoumn >= 0 && nextColoumn < 8)
        {
            let index = nextRow * 8 + nextColoumn;

            if(VirtualBoard[index] === null)
            {
                VirtualBoard[i].legalMoves.push(index);
            }
            else
            {
                if(VirtualBoard[index].isWhite !== isWhite)
                    VirtualBoard[i].legalMoves.push(index);
            }
        }
    }

   
}

function calculateBishopMoves(i)
{
    let isWhite = VirtualBoard[i].isWhite
    const columnPosition = i % 8;
    const rowPosition = Math.floor(i / 8);

    let Directions = [[1, 1], [1, -1], [-1, 1], [-1, -1]];

    for(let [DirectionRow, directionColoumn] of Directions)
    {
        let nextRow = rowPosition + DirectionRow;
        let nextColoumn = columnPosition + directionColoumn;

        while(nextRow >= 0 && nextRow < 8 && nextColoumn >= 0 && nextColoumn < 8)
        {
            let index = nextRow * 8 + nextColoumn;

            if(VirtualBoard[index] === null)
            {
                VirtualBoard[i].legalMoves.push(index);
            }
            else
            {
                if(VirtualBoard[index].isWhite !== isWhite)
                    VirtualBoard[i].legalMoves.push(index);
                
                break;
            }

            nextColoumn += directionColoumn;
            nextRow += DirectionRow;
        }
    } 
}

function calculateRookMoves(i)
{
    let isWhite = VirtualBoard[i].isWhite
    const columnPosition = i % 8;
    const rowPosition = Math.floor(i / 8);


    for(let [DirectionRow, directionColoumn] of Directions)
    {
        let nextRow = rowPosition + DirectionRow;
        let nextColoumn = columnPosition + directionColoumn;

        while(nextRow >= 0 && nextRow < 8 && nextColoumn >= 0 && nextColoumn < 8)
        {
            let index = nextRow * 8 + nextColoumn;

            if(VirtualBoard[index] === null)
            {
                VirtualBoard[i].legalMoves.push(index);
            }
            else
            {
                if(VirtualBoard[index].isWhite !== isWhite)
                    VirtualBoard[i].legalMoves.push(index);
                
                break;
            }

            nextColoumn += directionColoumn;
            nextRow += DirectionRow;
        }
    } 
}

function calculateQueenMoves(i)
{
    let isWhite = VirtualBoard[i].isWhite
    const columnPosition = i % 8;
    const rowPosition = Math.floor(i / 8);

    let Directions = [[1, 0], [-1, 0], [0, 1], [0, -1], [1, 1], [1, -1], [-1, 1], [-1, -1]];

    for(let [DirectionRow, directionColoumn] of Directions)
    {
        let nextRow = rowPosition + DirectionRow;
        let nextColoumn = columnPosition + directionColoumn;

        while(nextRow >= 0 && nextRow < 8 && nextColoumn >= 0 && nextColoumn < 8)
        {
            let index = nextRow * 8 + nextColoumn;

            if(VirtualBoard[index] === null)
            {
                VirtualBoard[i].legalMoves.push(index);
            }
            else
            {
                if(VirtualBoard[index].isWhite !== isWhite)
                    VirtualBoard[i].legalMoves.push(index);
                
                break;
            }

            nextColoumn += directionColoumn;
            nextRow += DirectionRow;
        }
    } 
}

function calculateKingMoves(i)
{
    let isWhite = VirtualBoard[i].isWhite
    const columnPosition = i % 8;
    const rowPosition = Math.floor(i / 8);

    let Directions = [[1, 0], [-1, 0], [0, 1], [0, -1], [1, 1], [1, -1], [-1, 1], [-1, -1]];

    for(let [DirectionRow, directionColoumn] of Directions)
    {
        let nextRow = rowPosition + DirectionRow;
        let nextColoumn = columnPosition + directionColoumn;

        if(nextRow >= 0 && nextRow < 8 && nextColoumn >= 0 && nextColoumn < 8)
        {
            let index = nextRow * 8 + nextColoumn;

            if(VirtualBoard[index] === null)
            {
                VirtualBoard[i].legalMoves.push(index);
            }
            else
            {
                if(VirtualBoard[index].isWhite !== isWhite)
                    VirtualBoard[i].legalMoves.push(index);
            }
        }
    }
}
//------------------------------------------------------------------------

function calculateLegalMoves()
{
    for(let i = 0; i < 64; i++)
    {
        if(VirtualBoard[i] !== null)
        {
            VirtualBoard[i].legalMoves = []

            switch (VirtualBoard[i].type.toLowerCase()) {
                case 'p':
                        calculatePawnMoves(i);
                        break;
                case 'n':
                        calculateKnightMoves(i);
                        break;
                case 'b':
                        calculateBishopMoves(i);
                        break;
                case 'r':
                        calculateRookMoves(i);
                        break;
                case 'q':
                        calculateQueenMoves(i);
                        break;
                case 'k':
                        calculateKingMoves(i);

                default:
                    break;
            }
        }
    }
}

function ReadPGN(){
    pgn = document.getElementById("txtPGN").value
    parsePGN(pgn)
}

function removeIndex(str, index) {
    return str.slice(0, index) + str.slice(index + 1);
}

function split_at_index(str, index)
{
    return str.substring(0, index) + str.substring(index + 1);
}

function splitStringAt(str, index) {
    const part1 = str.slice(0, index);
    const part2 = str.slice(index);
    return [part1, part2];
}

function parsePGN(PGN)
{
    let MovesPGN = PGN.split("\n").filter(line => !line.startsWith('[') && line.trim() !== '').join(" ")
    pgnMoves = MovesPGN.split(" ").filter(str => !parseInt(str));
    pgnMoves.pop()

    console.log(pgnMoves)
}

let counter = 0;

function MoveForward()
{
    //finds if an X is in the board
    let Xindex = pgnMoves[counter].indexOf('x');
    
    if(Xindex !== -1)
        CaptureMove = removeIndex(pgnMoves[counter], Xindex);
    else
        CaptureMove = pgnMoves[counter];

    //Find and tells the position of the first number encountered which is the row number
    let firstDigit = CaptureMove.match(/\d/);
    let IndexDigit = CaptureMove.indexOf(firstDigit);


    //composed of pieceType, Position
    let splittedMove = splitStringAt(CaptureMove, IndexDigit - 1);

    
    addMoveToMovesPanel(splittedMove, counter)
    

    MovePiece(splittedMove[1], splittedMove[0]);

    counter++;
}

function MovePiece(PieceTarget, pieceType) {
   
    if (pieceType === "")  pieceType = "p";

    //console.log("the piece type is: " + pieceType);
    let UnicodeStart = 97;
  
    let targetCol = PieceTarget[0].charCodeAt(0) - UnicodeStart; 
    let targetRow = 8 - PieceTarget[1];

    let TargetToLinear = targetRow * 8 + targetCol;
  
    
    let pieceToMove = null;

    for(virtualPiece of VirtualBoard)
    {
        if(virtualPiece !== null)
        {
            let isCorrectPiece = pieceType.toLowerCase() === virtualPiece.type.toLowerCase();
            let isCorrectPosition = pieceType === "" || virtualPiece.position % 8 === pieceType.charCodeAt(0) - UnicodeStart; 

            if((FENtoIMGmap[pieceType] && isCorrectPiece || !FENtoIMGmap[pieceType] && isCorrectPosition) && virtualPiece.legalMoves.includes(TargetToLinear))
            {
                pieceToMove = virtualPiece;
                break;
            }
        }
    }

    let CurrentCell = document.getElementById("cell" + pieceToMove.position);
    let TargetCell = document.getElementById("cell" + TargetToLinear);

    let ElementToMove = CurrentCell.querySelector("img");
    let ElementToRemove = TargetCell.querySelector("img");

    if(ElementToRemove !== null)
        ElementToRemove.remove()

    TargetCell.append(ElementToMove)
   
    VirtualBoard[TargetToLinear] = VirtualBoard[pieceToMove.position]
    VirtualBoard[pieceToMove.position] = null;
    
    pieceToMove.position = TargetToLinear;
    
    calculateLegalMoves();

    whiteTurn = !whiteTurn;
}
  


buildBoard();
PositionPiecesFromFEN(startingFEN);
calculateLegalMoves();