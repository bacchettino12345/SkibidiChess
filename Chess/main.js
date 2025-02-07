
let Board = document.getElementById("CheckerBoard");

let startingFEN = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1";
let CurrentMoveIndex = 0;
let previousFEN = FEN;


let pgn = ""

function ReadText(){
    pgn = document.getElementById("txt").value
    parsePGN(pgn)
}

function buildBoard()
{
    for(let i = 0; i < 8; i++){
        for(let j = 0; j < 8; j++){
    
            let cellNumber = i * 8 + j;
    
            const cell = document.createElement("div");
    
            cell.className = "cell";
            cell.id = "cell" + cellNumber;
            cell.style.backgroundColor = "black";
            
    
            if((i + j) % 2 === 0)
                cell.style.backgroundColor = "green";
            else
                cell.style.backgroundColor = "white";
                
    
            Board.append(cell)
        }
    }
}


parseFEN(startingFEN)

function parseFEN(FEN){
    
    let PositionsFEN = FEN.split(" ")[0].split("/");
    let counter = 0;

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
                let image = document.createElement("img");
    
                image.src = "PiecesIMG/" + FENtoIMGmap[Row[j]];
                image.style.maxWidth = "100%"
                image.style.maxHeight = "100%"
                
                cell.append(image)
    
                counter++;
            }
        }
    }
}

let movesArray;

function parsePGN(PGN)
{
    let MovesPGN = PGN.split("\n").filter(line => !line.startsWith('[') && line.trim() !== '').join(" ")
    movesArray = MovesPGN.split(" ").filter(str => !parseInt(str));
    movesArray.pop()

    console.log(movesArray)
}




function MoveForward()
{
    
    if(parseInt(movesArray[CurrentMoveIndex][1]))
    {
        if(CurrentMoveIndex % 2 === 0)
        {
            
        }       
    }   
    
    CurrentMoveIndex++;
}


function MoveBackwards()
{
    console.log("backwards")
}