<template>
    <section class="block Psychicsvoice">
        <div class="Explore2 text-center">
            <h2 class="block-title">Why GoPsys?</h2>
        </div>
        <div class="row col-lg-12">
            <div class="col-md-6 col-lg-6 bodes amet">
                <div class="row">
                    <div class="col-9 col-lg-10 text-right">
                        <h3>Amazing Advisors</h3>
                        <p>
                            No matter what stage you are at in your career, love-life or other relationships, our psychics will help guide and inspire you through difficult and confusing times. Online psychics available 24/7.
                        </p>
                    </div>
                    <div class="col-3 col-lg-2 text-center">
                        <img src="/images/homepage/t1.png" alt="t1">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 bodes2 amet">
                <div class="row">
                    <div class="col-3 col-lg-2 text-center">
                        <img src="/images/homepage/t2.png" alt="t2">
                    </div>
                    <div class="col-9 col-lg-10 text-left">
                        <h3>Advisor Reviews</h3>
                        <p>
                            You can read real testimonials from our clients to help you select your psychic. These unedited positive reviews are a great way to feel confident in your choice of the best Psychic professional for you.
                        </p>
                    </div>

                </div>
            </div>
            <div class="col-md-6 col-lg-6 bodes amet2">
                <div class="row">
                    <div class="col-9 col-lg-10 text-right">
                        <h3>Hold The Key</h3>
                        <p>Find and speak to advisors that provide insights to help you unlock clarity amid uncertainty.</p>
                    </div>
                    <div class="col-3 col-lg-2 text-center">
                        <img src="/images/homepage/t3.png" alt="t3">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 bodes2">
                <div class="row">
                    <div class="col-3 col-lg-2 text-center">
                        <img src="/images/homepage/t4.png" alt="t4">
                    </div>
                    <div class="col-9 col-lg-10 text-left">
                        <h3>Private & Confidential</h3>
                        <p>
                            Every conversation is 100% risk-free. If you're ever unsatisfied, we're committed to making it right.
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
    .Psychicsvoice {
        .amet {
            padding-bottom: 60px;
        }
        .amet2 {
            @media (max-width: 767.98px) {
                padding-bottom: 60px;
            }
        }
        .bodes {
            border-right: 1px solid #e8eff7;
            @media (max-width: 767.98px) {
                border-right: none;
            }
        }
        .bodes,
        .bodes2 {
            h3 {
                font-size: 22px;
                font-weight: 700;
                margin-top: 14px;
            }
            p {
                color: #8d9599;
                display: inline-block;
                max-width: 400px
            }
        }
    }
</style>
