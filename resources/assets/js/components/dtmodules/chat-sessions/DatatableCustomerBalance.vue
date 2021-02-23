<template>
    <div class="text-right">
        Before: {{ toFixed(row.customer_balance_before, 2) }}<br>
        After: {{ toFixed(row.customer_balance_after, 2) }}
    </div>
</template>


<script>
    import MyMixins from '../../../mixins'

    export default {
        mixins: [MyMixins],
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
        }
    }
</script>


<style scoped>

</style>
