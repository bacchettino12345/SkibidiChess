import { Helper } from "../game/Helpers.js";
import { AnimationMenager } from "../game/AnimationMenager.js";
import { SimpleAnimation } from "../game/simpleAnimation.js";
import { Piece } from "../game/pieces/Piece.js";

export class PhysicalBoard
{
    animatingCircles = new Map(); // Track radius for each square
    activeAnimations = new Map(); // Track active animations per square


    boardElement = null;
    ctx = null;

    boardWidth = null;
    boardHeight = null;

    squareWidth = null;
    squareHeight = null;

    virtualBoard = null;

    lastHoveredSquare = null;
    selectedSquare = null;

    HighlightedSquares = []

    SpriteSheet = null;



    circleAnimating = null;

    primaryColor = "#EBECD0";
    secondaryColor = "#739552";
    highlightLegalMovesColor = "rgba(0, 0, 0, 0.4)";


    animationMenager = null;


    constructor(virtualBoard)
    {
        this.boardElement = document.getElementById("ChessBoard");
        this.ctx = this.boardElement.getContext("2d");
        
        this.boardWidth = 800;
        this.boardHeight = 800;

        this.boardElement.width = this.boardWidth;
        this.boardElement.height = this.boardHeight;
        
        this.squareWidth = this.boardWidth / 8;
        this.squareHeight = this.boardHeight / 8;
        
        this.virtualBoard = virtualBoard;

        this.animationMenager = new AnimationMenager();
        
        this.LoadSprites();
        
        this.boardElement.addEventListener("click", (event) => this.handleClick(event));
        this.boardElement.addEventListener("mousemove", (event) => this.handleMove(event));
    }
    
    handleClick(event) 
    {
        const rect = this.boardElement.getBoundingClientRect();

        const x = event.clientX - rect.left ;2
        const y = event.clientY - rect.top ;

        let clickedSquare = Helper.coordsToIndex(x, y, this.squareWidth, this.squareHeight);
        
        //Checks how to highlight the squares and whether to move a piece or not

        if(this.selectedSquare === null && this.virtualBoard.getPieceAt(clickedSquare) !== null)
        {
            this.HighlightedSquares = [];
            this.HighlightedSquares.push(clickedSquare);

            this.selectSquare( clickedSquare );
        }
        else
        {
            if(this.virtualBoard.canPieceMove(this.selectedSquare, clickedSquare))
            {
                let startCoords = Helper.to2D(this.selectedSquare);
                let endCoords = Helper.to2D(clickedSquare);


                this.startPieceMoveAnimation(this.virtualBoard.pieces[this.selectedSquare], startCoords[1], startCoords[0], endCoords[1], endCoords[0], 100 );


                this.HighlightedSquares.push(clickedSquare);
                this.selectedSquare = null;
            }
            else if(this.virtualBoard.getPieceAt(clickedSquare) !== null)
            {
                this.HighlightedSquares = [];
                this.HighlightedSquares.push(clickedSquare);
                this.selectSquare( clickedSquare )
            }
        }
        
        this.RenderBoard();

    }

    handleMove(event)
    {
        const rect = this.boardElement.getBoundingClientRect();

        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;

        this.hoverSquare(Helper.coordsToIndex(x, y, this.squareWidth, this.squareHeight));        
    }

    LoadSprites()
    {
        this.SpriteSheet = new Image();

        this.SpriteSheet.src = "../Assets/Images/SpriteSheet.svg";
        
        this.SpriteSheet.onload = () => {
            this.RenderBoard();
        }
    }
    

    RenderBoard(radius, legalMove)
    {
        this.drawBoard();
        this.drawBoardCoords();
        this.drawPossibleMoves(radius, legalMove);
        this.HighlightSquare();
        this.drawPieces();
    }

    drawBoard()
    {
        for(let i = 0; i < 8; i++)
        {
            for(let j = 0; j < 8; j++)
            {
                this.ctx.fillStyle = (j + i) % 2 === 0 ? this.primaryColor : this.secondaryColor;

                this.ctx.fillRect( this.squareWidth * j, this.squareHeight * i, this.squareWidth, this.squareHeight);
            }
        }
    }

    drawBoardCoords()
    {
        this.ctx.font = "bold 20px arial";

        for(let i = 0; i <= 8; i++)
        {
            this.ctx.fillStyle = i % 2 === 0 ? this.primaryColor : this.secondaryColor;
            this.ctx.fillText(9 - i, 5, (i * this.squareHeight)- 75 );
            this.ctx.fillText(String.fromCharCode(97 + i), (i * this.squareWidth) + 80 , 8 * this.squareHeight - 5);
        }
    }

    drawPossibleMoves() 
    {
        if (this.selectedSquare !== null) 
        {
            const piece = this.virtualBoard.getPieceAt(this.selectedSquare);

            if (!piece) return;
    
            for (const legalMove of piece.legalMoves) {

                const coords = Helper.to2D(legalMove);

                const hasPiece = this.virtualBoard.getPieceAt(legalMove) !== null;
                const radius = this.animatingCircles.get(legalMove) || 15;
    
                this.ctx.beginPath();
                this.ctx.arc( coords[1] * this.squareWidth + this.squareWidth/2, coords[0] * this.squareHeight + this.squareHeight/ 2, radius, 0, 2 * Math.PI );
    
                if (hasPiece) 
                {
                    this.ctx.strokeStyle = this.highlightLegalMovesColor;
                    this.ctx.lineWidth = 10;
                    this.ctx.stroke();

                } else {

                    this.ctx.fillStyle = this.highlightLegalMovesColor;
                    this.ctx.fill();
                }
            }
        }
    }

    drawPieces()
    {
        for(let i = 0; i < 8; i++)
        {
            for(let j = 0; j < 8; j++)
            {
                const PieceIndex = Helper.toLinear([i, j]);

                if(this.virtualBoard.pieces[PieceIndex] !== null && !this.virtualBoard.pieces[PieceIndex].isMoving)
                {
                    let piece = this.virtualBoard.getPieceAt(PieceIndex);

                    const pieceOrder = ["p", "n", "b", "r", "q", "k"];
                    const pieceIndex = pieceOrder.indexOf(piece.type.toLowerCase());
            
                    const color = piece.isWhite ? 270 : 0;
            
                    const spriteX = pieceIndex * 45 + color;
                    const spriteY = 0; 
            
                    this.ctx.drawImage(
                        this.SpriteSheet,
                        spriteX, spriteY,
                        45, 45,
                        j * this.squareWidth, i * this.squareHeight,
                        this.squareWidth, this.squareHeight
                    );
                }
            }
        }
    }

    hoverSquare(squareIndex) 
    {
        const prevHovered = this.lastHoveredSquare;

        const isLegalMove = this.selectedSquare && this.virtualBoard.getPieceAt(this.selectedSquare).legalMoves.includes(squareIndex);
    
        // Only trigger animations if we entered a new square
        if (prevHovered !== squareIndex) 
        {
            // Handle previous hovered square (reverse animation)
            if (prevHovered !== null && this.selectedSquare) 
            {
                const wasLegalMove = this.virtualBoard.getPieceAt(this.selectedSquare).legalMoves.includes(prevHovered);
                
                if (wasLegalMove) {
                    this.startLegalMovesAnimation(prevHovered, 30, 15, 100);
                }
            }
    
            // Handle new hovered square (forward animation)
            if (isLegalMove) {
                this.startLegalMovesAnimation(squareIndex, 15, 30, 100);
            }
    
            this.RenderBoard();
        }
    
        // Handle piece highlighting
        const position = Helper.to2D(squareIndex);

        if (this.virtualBoard.getPieceAt(squareIndex) !== null && prevHovered !== squareIndex) 
        {
            this.ctx.strokeStyle = "rgb(255, 255, 255)";
            this.ctx.lineWidth = 3;
            this.ctx.strokeRect( position[1] * this.squareWidth, position[0] * this.squareHeight, this.squareWidth, this.squareHeight );
        }
    
        this.lastHoveredSquare = squareIndex;
    }

    HighlightSquare()
    {
        for(let square of this.HighlightedSquares)
        {
            let position = Helper.to2D(square)

            this.ctx.fillStyle = "rgba(255, 255, 0, 0.4)";
            this.ctx.fillRect(position[1] * this.squareWidth, position[0] * this.squareHeight, this.squareWidth, this.squareHeight);
        }
    }

    selectSquare(SquareIndex)
    {
        
        if(this.virtualBoard.getPieceAt(SquareIndex) !== null)
        {
            this.selectedSquare = SquareIndex;
        }
        else
        {
            this.selectedSquare = null;
        }
    }

    startPieceMoveAnimation(piece, startX, startY, endX, endY, duration = 300)
    {
        const anim = new SimpleAnimation({
            duration, 

            onUpdate: (progress) => {

                piece.isMoving = true;

                anim.isPlaying = true;

                let currentX = startX + (endX - startX) * progress;
                let currentY = startY + (endY - startY) * progress;

                this.RenderBoard();

                this.drawPieceAtCoords(piece, currentX, currentY);
            },

            onComplete: () => {

                piece.isMoving = false;

                anim.isPlaying = false;
                let posIndex = Helper.toLinear([endY, endX])
                this.virtualBoard.movePiece(piece.position, posIndex);
                this.animatingCircles.delete(piece.position)
                this.RenderBoard();
            },


            easing: this.easeInOutQuad
        });

        this.animationMenager.addAnimation(anim);
    }   

    startLegalMovesAnimation(squareIndex, startRadius, endRadius, duration) 
    {
        // Only start animation if not already animating to the target radius
        const currentRadius = this.animatingCircles.get(squareIndex);

        if (currentRadius === endRadius) return;
    
        // Cancel existing animation for this square
        if (this.activeAnimations.has(squareIndex)) 
        {
            this.activeAnimations.get(squareIndex).stop();
        }
    
        const anim = new SimpleAnimation
        ({
            duration,

            onUpdate: (progress) => {

                const currentRadius = startRadius + (endRadius - startRadius) * progress;
                this.animatingCircles.set(squareIndex, currentRadius);
                this.RenderBoard();
            },

            onComplete: () => {

                this.activeAnimations.delete(squareIndex);
                // Only keep final state if it's the expanded version
                if (endRadius === 15) {
                    this.animatingCircles.delete(squareIndex);
                }
            },

            easing: this.easeInOutQuad

        });
    
        this.activeAnimations.set(squareIndex, anim);

        this.animationMenager.addAnimation(anim);
    }

    easeInOutQuad(t) {
        return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t ) * t;
    }

    drawPieceAtCoords(piece, currentX, currentY)
    {
        const pieceOrder = ["p", "n", "b", "r", "q", "k"];
        const pieceIndex = pieceOrder.indexOf(piece.type.toLowerCase());

        const color = piece.isWhite ? 270 : 0;

        const spriteX = pieceIndex * 45 + color;
        const spriteY = 0; 

        this.ctx.drawImage(
            this.SpriteSheet,
            spriteX, spriteY,
            45, 45,
            currentX * this.squareWidth, currentY * this.squareHeight,
            this.squareWidth, this.squareHeight
        );
    }


    Reverseboard(){
        
    }
}