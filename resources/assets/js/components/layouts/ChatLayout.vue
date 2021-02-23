<template>
    <div>
        <router-view v-bind="myProps" class="form-container"></router-view>
    </div>
</template>

<script>
    export default {
        props: ['error'],
        data() {
            return {

            }
        },
        created() {
        },
        computed: {
            myProps() {
                return { error: this.error }
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>