
export class SimpleAnimation
{
    constructor({duration, onUpdate, onComplete, easing})
    {
        this.startTime = performance.now();
        this.duration = duration;
        this.onUpdate = onUpdate;
        this.onComplete = onComplete;
        this.easing = easing || ((t) => t);
    }

    update(timeStamp, delta)
    {
        const elapsed = timeStamp - this.startTime;

        let progress = elapsed / this.duration;

        if(progress > 1){
            progress = 1;
        }

        progress = this.easing(progress);

        this.onUpdate(progress);

        if(elapsed >= this.duration)
        {
            this.isFinished = true;
            
            if(this.onComplete)
                this.onComplete();
        }


    }
}