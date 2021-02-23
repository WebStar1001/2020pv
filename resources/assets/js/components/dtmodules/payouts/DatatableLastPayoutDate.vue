<template>
    <div>
        {{ date }}
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
                date: null
            }
        },
        created() {
            if (this.row.payout_items.length) {
                this.date = this.row.payout_items[this.row.payout_items.length - 1].updated_at
            }

        },
        methods: {
        }
    }
</script>


<style scoped>

</style>
