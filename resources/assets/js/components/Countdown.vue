<template>
    <div>
        {{ time }}
    </div>
</template>


<script>
    export default {
        props: ['startSeconds', 'startCountdown', 'timeFormat'],
        data() {
            return {
                secondsLeft: null,
                time: '00.00.00',
                timeBegan: null,
                timeStopped: null,
                stoppedDuration: 0,
                started: null,
                running: false,
            }
        },
        created() {
            this.secondsLeft = this.startSeconds;
            this.time = this.getDurationTime(this.secondsLeft);
            this.startCountdown ? this.start() : this.stop();
        },
        watch: {
            startCountdown(e) {
                e ? this.start() : this.stop();
            },
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

                this.started = setInterval(this.clockRunning, 1000);
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
                if (this.secondsLeft > 0) {
                    this.secondsLeft--;

                    if (this.secondsLeft === 0) {
                        this.$emit('onCountdownEnded', true)
                    }

                    this.time = this.getDurationTime(this.secondsLeft);
                }
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
