<template>
    <div>
        {{ row.user.email }}
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
