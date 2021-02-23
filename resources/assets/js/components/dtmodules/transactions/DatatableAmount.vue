<template>
    <div class="btn-group btn-group-sm d-flex align-items-center">
        ${{ toFixed(row.amount / 100, 2) }}
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
        }
    }
</script>


<style scoped>

</style>
