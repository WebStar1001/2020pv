<template>
    <div class="btn-group btn-group-sm d-flex align-items-center">
        <span :class="getClassName()">
            {{ getAmountEarnedFormatted() }}
        </span>
    </div>
</template>


<script>
    import MyMixins from '../../../mixins';

    export default {
        mixins: [MyMixins],
        props: [
            'row',
            'xprops',
        ],
        data() {
            return {
                // Code...
            }
        },
        created() {
        },
        methods: {
            getClassName() {
                if (this.row.amount_earned < 0) {
                    return 'text-danger'
                }

                return 'text-success';
            },
            getAmountEarnedFormatted() {
                if (this.row.amount_earned < 0) {
                    return '-$' + this.toFixed(Math.abs(this.row.amount_earned), 2);
                }

                return '+$' + this.toFixed(this.row.amount_earned, 2);
            }
        }
    }
</script>


<style scoped>

</style>
