<template>
    <section class="block works text-center">
        <h2 class="block-title">How it works?</h2>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <div class="box login-form2 h-100">
                        <img src="/images/homepage/search.png" alt="search">
                        <h3>Find a Psychic Advisor</h3>
                        <p>
                            We carefully screened and selected a range of astonishingly gifted psychic readers to provide clarity and new insights into your life.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4">
                    <div class="box login-form2 h-100">
                        <img src="/images/homepage/user.png" alt="user">
                        <h3>Create Your Account</h3>
                        <p>
                            When you are ready to connect, create your account. The process is quick and simple.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4">
                    <div class="box login-form2 h-100">
                        <img src="/images/homepage/phone.png" alt="phone">
                        <h3>Chat or Email</h3>
                        <p>
                            Whether you prefer to talk. On the chat online or communication by email it is your choice , we offer the convenience to contact in the manner that works best of you.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
    .works {
        background-color: #edf1f2;
        @media (max-width: 767.98px) {
            .col-md-4 {
                margin-bottom: 30px;
            }
        }
        .login-form2 {
            background-color: #fff;
            padding: 50px 30px;
            box-shadow: 0 12px 14px rgba(0,0,0,.11);
        }
        img {
            padding-top: 20px;
            border-bottom: 2px solid #00bff0;
            padding-bottom: 30px;
        }
        h3 {
            font-weight: 700;
            font-size: 22px;
            margin: 30px 0;
        }
        p {
            font-size: 16px;
            color: #8d9599;
            @media (max-width: 991.98px) and (min-width: 768px) {
                font-size: 10px;
            }

        }
    }
</style>
