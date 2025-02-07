
export class AnimationMenager
{
    constructor()
    {
        this.animations = [ ]
        this.lastTimeStamp = 0;
        this.rafID = null;
    }

    addAnimation(animation)
    {
        this.animations.push(animation);

        if(!this.rafID){
            this.lastTimeStamp = performance.now();
            this.loop();
        }
    }

    loop(){
        this.rafID = requestAnimationFrame((TimeStamp) => this.loopCore(TimeStamp));
    }

    loopCore(TimeStamp)
    {
        const delta = TimeStamp - this.lastTimeStamp;
        this.lastTimeStamp = TimeStamp;

        this.animations = this.animations.filter((anim) => {
            anim.update(TimeStamp, delta);
            return !anim.isFinished;
        })

        if(this.animations.length > 0){
            this.loop();
        }
        else{
            cancelAnimationFrame(this.rafID);
            this.rafID = null;
        }
    }
}