<template>
    <div>
        {{ getDurationTime(row.free_chat_duration) }}
    </div>
</template>


<script>
    export default {
        props: [
            'row',
            'xprops',
        ],
        data() {
            return {
            }
        },
        created() {
        },
        methods: {
            getDurationTime(duration) {
                let sec_num = parseInt(duration, 10);
                let hours   = Math.floor(sec_num / 3600);
                let minutes = Math.floor((sec_num - (hours * 3600)) / 60);
                let seconds = sec_num - (hours * 3600) - (minutes * 60);

                if (hours   < 10) {hours   = "0" + hours;}
                if (minutes < 10) {minutes = "0" + minutes;}
                if (seconds < 10) {seconds = "0" + seconds;}

                return hours + ':' + minutes + ':' + seconds;
            },
        }
    }
</script>


<style scoped>

</style>
