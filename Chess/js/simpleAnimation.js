export class SimpleAnimation 
{

    constructor(    { duration, onUpdate, onComplete, easing, onStop }  ) 
    {
        this.startTime = performance.now();
        this.duration = duration;

        this.onUpdate = onUpdate;
        this.onComplete = onComplete;
        this.onStop = onStop;

        this.easing = easing || ((t) => t);

        this.isFinished = false;
        this.isStopped = false;
    }

    update(timeStamp) 
    {
        if (this.isStopped || this.isFinished) return;

        const elapsed = timeStamp - this.startTime;
        let progress = elapsed / this.duration;

        if (progress > 1) progress = 1;

        progress = this.easing(progress);

        this.onUpdate(progress);

        if (elapsed >= this.duration) {
            this.isFinished = true;
            if (this.onComplete) this.onComplete();
        }
    }

    stop() 
    {
        if (!this.isFinished) 
        {
            this.isStopped = true;

            if (this.onStop) this.onStop();
        }
    }

    // Optional: Restart animation with new parameters
    restart(newParams) {
        this.isFinished = false;
        this.isStopped = false;
        this.startTime = performance.now();
        if (newParams) {
            Object.assign(this, newParams);
        }
    }
}