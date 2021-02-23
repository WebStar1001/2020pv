<template>
    <div>
        {{ time }}
    </div>
</template>


<script>
export default {
    props: ['startSeconds', 'startWatch'],
    data() {
        return {
            time: '00.00.00',
            timeBegan: null,
            timeStopped: null,
            stoppedDuration: 0,
            started: null,
            running: false,
        }
    },
    created() {
        this.time = this.getDurationTime(this.startSeconds);
        this.startWatch ? this.start() : this.stop();
    },
    watch: {
        startWatch(e) {
            e ? this.start() : this.stop();
        },
        startSeconds(e) {
            if (this.running) {
                this.reset();
                this.start();
            }
        }
    },
    methods: {
        start() {
            if(this.running) return;

            if (this.timeBegan === null) {
                this.reset();
                this.timeBegan = new Date();
            }

            if (this.timeStopped !== null) {
                this.stoppedDuration += (new Date() - this.timeStopped);
            }

            this.started = setInterval(this.clockRunning, 10);
            this.running = true;
        },
        stop() {
            this.running = false;
            this.timeStopped = new Date();
            clearInterval(this.started);
        },
        reset() {
            this.running = false;
            clearInterval(this.started);
            this.stoppedDuration = 0;
            this.timeBegan = null;
            this.timeStopped = null;
            this.time = this.getDurationTime(this.startSeconds);
        },
        clockRunning(){
            let currentTime = new Date()
                , startTime = currentTime.setSeconds(currentTime.getSeconds() + this.startSeconds)
                , timeElapsed = new Date(startTime - this.timeBegan - this.stoppedDuration)
                , hour = timeElapsed.getUTCHours()
                , min = timeElapsed.getUTCMinutes()
                , sec = timeElapsed.getUTCSeconds()
                , ms = timeElapsed.getUTCMilliseconds();

            this.time =
                this.zeroPrefix(hour, 2) + ":" +
                this.zeroPrefix(min, 2) + ":" +
                this.zeroPrefix(sec, 2);
        },
        zeroPrefix(num, digit) {
            let zero = '';

            for (let i = 0; i < digit; i++) {
                zero += '0';
            }

            return (zero + num).slice(-digit);
        },
        getDurationTime(duration) {
            let sec_num = parseInt(duration, 10);
            let hours   = Math.floor(sec_num / 3600);
            let minutes = Math.floor((sec_num - (hours * 3600)) / 60);
            let seconds = sec_num - (hours * 3600) - (minutes * 60);

            if (hours   < 10) {hours   = "0" + hours;}
            if (minutes < 10) {minutes = "0" + minutes;}
            if (seconds < 10) {seconds = "0" + seconds;}

            return hours + ':' + minutes + ':' + seconds;
        }
    }
}
</script>


<style scoped>

</style>
