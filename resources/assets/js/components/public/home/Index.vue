<template>
    <div>
        <div class="d-none d-sm-block">
            <homepage-banner></homepage-banner>

<!--            <flower-section></flower-section>-->

            <!--<how-it-works-section></how-it-works-section>-->
        </div>

        <featured-advisors></featured-advisors>

        <div class="d-none d-sm-block">
            <categories-section></categories-section>

            <why-section></why-section>

            <!--<testimonials-section></testimonials-section>-->

            <become-user-section v-if="!this.$gate"></become-user-section>
        </div>
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
