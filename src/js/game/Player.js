import { bus } from "./Bus.js"


export class player{
    constructor(color)
    {
        this.color = color
    }   

    playTurn()
    {
        throw new Error("handle in subclass")
    }
}

export class Local extends player{

    constructor(color)
    {
        super(color)
        this.resolveMove = null
    }

    playTurn()
    {
        return new Promise((promise) =>{
            this.resolveMove = promise;

            bus.once("move", (start, target) => {
                if(this.resolveMove)    
                {
                    this.resolveMove({start, target})
                    this.resolveMove = null
                }
            })
        })
    }

}



export class AI extends player{

    constructor(color)
    {
        super(color)
        this.resolveMove = null
    }

    async playTurn()
    {
        return new Promise((promise) => {
            this.resolveMove = promise;

            bus.once("move", (start, target) => {

                if(this.resolveMove)    
                {
                    this.resolveMove({start, target})
                    this.resolveMove = null
                }
            })

            bus.emit("moveAI", this.color)
        })
    }

    

}