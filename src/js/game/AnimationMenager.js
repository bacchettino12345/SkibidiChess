export class AnimationMenager 
{
    constructor() 
    {
        this.animations = [];
        this.lastTimeStamp = 0;
        this.rafID = null;
    }

    addAnimation(animation) 
    {
        this.animations.push(animation);

        if (!this.rafID) {
            this.lastTimeStamp = performance.now();
            this.loop();
        }
    }

    loop() {
        this.rafID = requestAnimationFrame((timeStamp) => this.loopCore(timeStamp));
    }

    loopCore(timeStamp) 
    {
        const delta = timeStamp - this.lastTimeStamp;
        this.lastTimeStamp = timeStamp;

        // Update and filter animations
        this.animations = this.animations.filter((anim) => {
            if (anim.isStopped) {
                if (anim.onStop) anim.onStop();
                return false;
            }
            
            anim.update(timeStamp, delta);

            return !anim.isFinished;
        });

        if (this.animations.length > 0) 
        {
            this.loop();
        } 
        else 
        {
            cancelAnimationFrame(this.rafID);
            this.rafID = null;
        }
    }

    // New method to cancel all animations
    cancelAll() {
        this.animations.forEach(anim => anim.stop());
        this.animations = [];
    }
}