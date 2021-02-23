<template>
    <div>
        {{ toFixed(row.price_per_minute / 100, 2) }} ({{ row.commission_percentage }}%)
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
