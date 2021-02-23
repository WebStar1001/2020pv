<template>
    <div>
        {{ row.user.payment_email }}
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
