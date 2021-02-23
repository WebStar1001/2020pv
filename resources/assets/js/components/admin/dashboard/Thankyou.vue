<template>
    <section class="block Become">
        <div class="Become text-center">
            <h2 class="block-title">Thank you </h2>
            <p>You added ${{$route.params.amount}}</p>
            <div class="Started">
                <router-link :to="{name: 'home.home'}" class="btn btn-success text-uppercase">
                    Get Your Advisor
                </router-link>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                discount: 0
            }
        },
        created() {
            if (this.$gate || sessionStorage.getItem('specialoffer')) {
                this.discount = this.$store.getters.discount;
            }
        },
        methods: {
        }
    }
</script>

<style lang="scss" scoped>
    .Become {
        padding-left: 7px;
        padding-right: 7px;
        background-color: #edf1f2;
        h2 {
            font-weight: 600;
            font-size: 38px;
            margin-bottom: 40px;
            @media (max-width: 991.98px) and (min-width: 768px) {
                font-size: 30px;
            }
            @media (max-width: 767.98px) {
                font-size: 22px;
            }
        }
        p {
            font-size: 16px;
            color: #404f54;
            width: 50%;
            margin: 0 auto 40px;
            @media (max-width: 767.98px) {
                width: 100%;
            }
            @media(max-width: 991.98px) and (min-width: 768px) {
                width: 70%;
            }
        }
        .btn-success {
            padding: 10px 90px;
        }
    }
</style>
