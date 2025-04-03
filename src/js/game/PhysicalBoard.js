import { Helper } from "../game/Helpers.js";
import { AnimationMenager } from "../game/AnimationMenager.js";
import { SimpleAnimation } from "../game/simpleAnimation.js";
import { Piece } from "../game/pieces/Piece.js";
import { bus } from "./Bus.js";

export class PhysicalBoard
{
    animatingCircles = new Map(); // Track radius for each square
    activeAnimations = new Map(); // Track active animations per square

    renderSuggestion = null;

    boardElement = null;
    ctx = null;

    boardWidth = null;
    boardHeight = null;

    squareWidth = null;
    squareHeight = null;

    normalMoveCirleRadius;
    hoveredMoveCircleRadius;

    virtualBoard = null;

    lastHoveredSquare = null;
    selectedSquare = null;

    HighlightedSquares = []

    SpriteSheet = null;


    isFlipped = null;


    circleAnimating = null;

    primaryColor = "#EBECD0";
    secondaryColor = "#739552";
    highlightLegalMovesColor = "rgba(0, 0, 0, 0.4)";


    animationMenager = null;


    squareFrom = null;
    squareTo = null;

    constructor(virtualBoard, flipped)
    {
        this.boardElement = document.getElementById("ChessBoard");
        this.ctx = this.boardElement.getContext("2d");
        
        
        this.virtualBoard = virtualBoard;
        

        this.playerIsWhite = flipped;

        this.isFlipped = flipped;
        
        this.animationMenager = new AnimationMenager();
        
        this.LoadSprites();
        this.resizeBoard();
        
        this.boardElement.addEventListener("click", (event) => this.handleClick(event));
        this.boardElement.addEventListener("mousemove", (event) => this.handleMove(event));
    }
    
    async handleClick(event) 
    {
        const rect = this.boardElement.getBoundingClientRect();

        let x = event.clientX - rect.left ;
        let y = event.clientY - rect.top ;

        if(this.isFlipped)
        {
            x = this.boardWidth - x;
            y = this.boardHeight - y;
        }

        let clickedSquare = Helper.coordsToIndex(x, y, this.squareWidth, this.squareHeight);


        
        //Checks how to highlight the squares and whether to move a piece or not

        if(this.selectedSquare === null && this.virtualBoard.getPieceAt(clickedSquare) !== null  && this.virtualBoard.pieces[clickedSquare].isWhite !== this.playerIsWhite)
        {
            this.HighlightedSquares = [];
            this.HighlightedSquares.push(clickedSquare);

            this.selectSquare( clickedSquare );
        }
        else
        {
            if(this.virtualBoard.canPieceMove(this.selectedSquare, clickedSquare))
            {
                const piece = this.virtualBoard.getPieceAt(this.selectedSquare)

                let startCoords = Helper.to2D(this.selectedSquare);
                let endCoords =  Helper.to2D(clickedSquare);


                // Check for pawn promotion
                const isPawn = piece.type.toLowerCase() === 'p'; // Fix: Added parentheses
                const targetRank = Helper.to2D(clickedSquare)[0];
                const isPromotion = isPawn && (targetRank === 0 || targetRank === 7);
                
                let promotionType = 'q';
                if(isPromotion) {
                    promotionType = await this.showPromotionUI(piece.isWhite);
                    this.virtualBoard.switchPiece(this.selectedSquare, promotionType)
                }
                
                this.startPieceMoveAnimation(this.virtualBoard.pieces[this.selectedSquare], startCoords[1], startCoords[0], endCoords[1], endCoords[0], 100 );
                
                this.selectedSquare = null;

                
            }
            else if(this.virtualBoard.getPieceAt(clickedSquare) !== null && this.virtualBoard.pieces[clickedSquare].isWhite !== this.playerIsWhite)
            {
                this.HighlightedSquares = [];
                this.HighlightedSquares.push(clickedSquare);
                this.selectSquare( clickedSquare )
            }
        }
        
        this.RenderBoard();

    }


    showPromotionUI(isWhite) {
        return new Promise((resolve) => {
            const promotePanel = document.getElementById("Promote");
            const buttons = promotePanel.querySelectorAll("button");
    
            // Remove previous click handlers
            buttons.forEach(btn => btn.onclick = null);
    
            // Position panel near the promotion square
            const targetSquare = this.selectedSquare;
            const [y, x] = Helper.to2D(targetSquare);
            const squareX = x * this.squareWidth;
            const squareY = y * this.squareHeight;
            
            // Adjust position based on board flip
            promotePanel.style.left = `${squareX + this.squareWidth/2 - 60}px`;
            promotePanel.style.top = `${squareY + this.squareHeight/2 - 60}px`;
    
            // Handle button clicks
            const clickHandler = (type) => {
                promotePanel.style.visibility = 'hidden';
                resolve(type);
            };
    
            // Assign new handlers using dataset
            buttons.forEach(btn => {
                btn.onclick = () => clickHandler(btn.dataset.type);
            });
    
            promotePanel.style.visibility = 'visible';
        });
    }
    

    isPlayerMove(clickedSquare)
    {

        if(this.virtualBoard.pieces[clickedSquare].isWhite !== this.virtualBoard.isWhiteTurn)
        {
            return false
        }

        return true
    }

    handleMove(event)
    {
        const rect = this.boardElement.getBoundingClientRect();

        let x = event.clientX - rect.left;
        let y = event.clientY - rect.top;

        if(this.isFlipped)
        {
            x = this.boardWidth - x;
            y = this.boardHeight - y;
        }

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
        this.suggestionSquares();

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
        this.ctx.font = "bold +" + (this.squareWidth / 100 * 20) + "px arial";


        if(!this.isFlipped)
        {
            for(let i = 0; i <= 8; i++)
            {
                this.ctx.fillStyle = i % 2 === 0 ? this.primaryColor : this.secondaryColor;
                this.ctx.fillText(9 - i, 5, (i * this.squareHeight)- (this.squareWidth / 100 * 75) );
                this.ctx.fillText(String.fromCharCode(97 + i), (i * this.squareWidth) + (this.squareWidth / 100 * 80), 8 * this.squareHeight - 5);
            }
        }
        else
        {
            for(let i = 0; i <= 8; i++)
            {
                this.ctx.fillStyle = i % 2 === 0 ? this.primaryColor : this.secondaryColor;
                this.ctx.fillText(i, 5, (i * this.squareHeight)- (this.squareWidth / 100 * 75) );
                this.ctx.fillText(String.fromCharCode(104 - i), (i * this.squareWidth) + (this.squareWidth / 100 * 80), 8 * this.squareHeight - 5);
            }
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

                if(this.isFlipped)
                {
                    coords[1] = 7 - coords[1];
                    coords[0] = 7 - coords[0];
                }

                const hasPiece = this.virtualBoard.getPieceAt(legalMove) !== null;
                const radius = this.animatingCircles.get(legalMove) || this.hoveredMoveCircleRadius;
    
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

                    let drawX = this.isFlipped ? (7 - j) * this.squareWidth : j * this.squareWidth
                    let drawY = this.isFlipped ? (7 - i) * this.squareHeight : i * this.squareHeight
            
                    this.ctx.drawImage(
                        this.SpriteSheet,
                        spriteX, spriteY,
                        45, 45,
                        drawX, drawY,
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
                    this.startLegalMovesAnimation(prevHovered, this.normalMoveCirleRadius, this.hoveredMoveCircleRadius, 100);
                }
            }
    
            // Handle new hovered square (forward animation)
            if (isLegalMove) {
                this.startLegalMovesAnimation(squareIndex, this.hoveredMoveCircleRadius, this.normalMoveCirleRadius, 100);
            }
    
            this.RenderBoard();
        }
    
        // Handle piece highlighting
        const position = Helper.to2D(squareIndex);

        if(this.isFlipped)
        {
            position[1] = 7 - position[1];
            position[0] = 7 - position[0];
        }

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

            if(this.isFlipped)
            {
                position[1] = 7 - position[1];
                position[0] = 7 - position[0];
            }

            this.ctx.fillStyle = "rgba(255, 255, 0, 0.4)";
            this.ctx.fillRect(position[1] * this.squareWidth, position[0] * this.squareHeight, this.squareWidth, this.squareHeight);
        }
    }

    suggestionSquares() {
        if (this.renderSuggestion) {

            const getAdjustedCoords = (coords) => {
                return this.isFlipped ? [7 - coords[1], 7 - coords[0]] : [coords[1], coords[0]];
            };
    
            const [fromX, fromY] = getAdjustedCoords(this.squareFrom);
            const [toX, toY] = getAdjustedCoords(this.squareTo);
    
            this.ctx.fillStyle = "rgba(255, 0, 0, 0.4)";
            this.ctx.fillRect(fromX * this.squareWidth, fromY * this.squareHeight, this.squareWidth, this.squareHeight);
            this.ctx.fillRect(toX * this.squareWidth, toY * this.squareHeight, this.squareWidth, this.squareHeight);
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

            onStart: () => {

                let posIndex = Helper.toLinear([endY, endX])
                
                this.HighlightedSquares = [];
                this.HighlightedSquares.push(piece.position);
                this.HighlightedSquares.push(posIndex);
                console.log(this.HighlightedSquares)
            },

            onUpdate: (progress) => {

                piece.isMoving = true;

                anim.isPlaying = true;

                let currentX = startX + (endX - startX) * progress;
                let currentY = startY + (endY - startY) * progress;
                
                this.RenderBoard();

                this.isFlipped ? this.drawPieceAtCoords(piece,  currentX, currentY) : this.drawPieceAtCoords(piece, 7-  currentX, 7-  currentY) 
                    
            },

            onComplete: () => {

                piece.isMoving = false;

                anim.isPlaying = false;


                let posIndex = Helper.toLinear([endY, endX])

                this.virtualBoard.movePiece(piece.position, posIndex)
                
                this.animatingCircles.delete(piece.position)
                this.renderSuggestion = false;

                

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

            onStart: () => {

            },

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
        currentX = 7 - currentX;
        currentY = 7 - currentY;

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


    resizeBoard()
    {

        if(window.innerHeight < window.innerWidth)
        {
            this.boardWidth = window.innerHeight - 160;
            this.boardHeight = window.innerHeight - 160;

        }
        else if(window.innerWidth < window.innerHeight )
        {
            this.boardWidth = window.innerWidth - 160;
            this.boardHeight = window.innerWidth - 160;
        }

        const dpr = window.devicePixelRatio || 1;

        this.boardElement.style.width = `${this.boardWidth}px`;
        this.boardElement.style.height = `${this.boardHeight}px`;

        this.boardElement.width = this.boardWidth * dpr;
        this.boardElement.height = this.boardHeight * dpr;

        this.ctx.scale(dpr, dpr);
        
        this.squareWidth = this.boardWidth / 8;
        this.squareHeight = this.boardHeight / 8;

        this.normalMoveCirleRadius = (this.squareWidth / 100 * 30)
        this.hoveredMoveCircleRadius = (this.squareWidth / 100 * 15)

        this.recomputeMoveCirclesSizes();

        this.RenderBoard()
    }

    recomputeMoveCirclesSizes()
    {
        this.animatingCircles.forEach((value, key) => {
            this.animatingCircles.set(key, (this.squareWidth / 100 * value));
          });
    }
}