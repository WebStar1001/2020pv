<template>
    <div>
        <homepage-banner></homepage-banner>

        <flower-section></flower-section>

        <!--<how-it-works-section></how-it-works-section>-->

        <featured-advisors></featured-advisors>

        <categories-section></categories-section>

        <why-section></why-section>

        <!--<testimonials-section></testimonials-section>-->

        <become-user-section v-if="!this.$gate"></become-user-section>
    </div>
</template>

<script>
    export default {
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

<style lang="scss" scoped>

</style>
