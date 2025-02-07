export class Helper{

    static toLinear(Coords)
    {
        return Coords[0] * 8 + Coords[1];
    }

    static to2D(index)
    {
        const row = Math.floor(index / 8);
        const col = index % 8; 
        
        return [row, col]
    }

    static coordsToIndex(x, y, cellWidth, cellHeight)
    {
        let xPos = x / cellWidth;
        let yPos = y / cellHeight;

        xPos = Math.trunc(xPos)
        yPos = Math.trunc(yPos);

        return this.toLinear([yPos, xPos]);
    }
}