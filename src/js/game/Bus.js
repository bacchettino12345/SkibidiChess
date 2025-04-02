
class Bus{
    constructor()
    {
        this.listeners = {}
    }
    
    on(eventName, callback)
    {
        if(!this.listeners[eventName])
            this.listeners[eventName] = []

        this.listeners[eventName].push(callback)
    }

    emit(eventName, ...args)
    {
        if(this.listeners[eventName])
        {
            this.listeners[eventName].forEach(callback => callback(...args));
        }
    }

    once(eventName, callback)
    {
        this.listeners[eventName] = new Set()
        this.listeners[eventName].add(callback)
    }
}


export const bus = new Bus()