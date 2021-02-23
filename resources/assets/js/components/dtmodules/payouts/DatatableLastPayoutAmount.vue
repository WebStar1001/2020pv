<template>
    <div>
        <span v-if="amount">${{ amount }}</span>
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
                amount: null
            }
        },
        created() {
            if (this.row.payout_items.length) {
                this.amount = this.row.payout_items[this.row.payout_items.length - 1].amount
            }

        },
        methods: {
        }
    }
</script>


<style scoped>

</style>
