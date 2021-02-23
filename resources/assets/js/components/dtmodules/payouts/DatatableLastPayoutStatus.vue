<template>
    <div>
        <span :class="getClass()">
            {{ transaction_status }}
        </span>
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
                transaction_status: null
            }
        },
        created() {
            if (this.row.payout_items.length) {
                this.transaction_status = this.row.payout_items[this.row.payout_items.length - 1].transaction_status
            }

        },
        methods: {
            getClass() {
                switch (this.transaction_status) {
                    case 'SUCCESS':
                        return 'text-success';
                    case 'PENDING':
                        return 'text-warning';
                    default:
                        return 'text-danger'
                }
            }
        }
    }
</script>


<style scoped>

</style>
