<template>
    <div>
        <footer class="myfooter">
            <div class="container">
                <div class="row">
                    <div class="col-md-5 col-lg-5">
                        <div class="Lorem3">
                            <img src="/images/logo-white.png" height="57" alt="" class="white-logo"/>
                        </div>
                        <p class="text-white">
                            A psychic reading by GoPsys can, simply put, change in your life., a source of trusted guidance and advice from the best psychics. Our goal is to offer you the top psychics, mediums, astrologers, fortune tellers and more.
                        </p>
                    </div>
                    <div class="col-6 col-md-2 col-lg-2 footer2">
                        <h3 class="text-white">About</h3>
                        <ul class="footnav">
                            <li><router-link :to="{name: 'public.about_us'}">About us</router-link></li>
                            <li><router-link :to="{name: 'public.terms_and_conditions'}">Terms and conditions</router-link></li>
                            <li><router-link :to="{name: 'public.privacy_policy'}">Privacy Policy</router-link></li>
                        </ul>
                    </div>
                    <div class="col-6 col-md-2 col-lg-2 footer2">
                        <h3 class="text-white">Support</h3>
                        <ul class="footnav">
                            <li><router-link :to="{name: 'public.faq'}">FAQ</router-link></li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-lg-2 footer2">
                        <h3 class="text-white">Contact</h3>
                        <ul class="footnav">
                            <li><a href="mailto:support@gopsys.com">support@gopsys.com</a></li>
                        </ul>
                        <ul class="footnav2">
                            <li>
                                <a href="https://www.facebook.com/Gopsys-100320288681147/" target="_blank">
                                    <img src="/images/social/fb.png" alt="" class="fb"/>
                                </a>
                            </li>
                            <!--<li>-->
                                <!--<a href="https://twitter.com/PsychicsVoice" target="_blank">-->
                                    <!--<img src="/images/social/tw.png" alt="" class="tw"/>-->
                                <!--</a>-->
                            <!--</li>-->
                            <!--<li>-->
                                <!--<a href="https://www.instagram.com/psychicsvoicecom/" target="_blank">-->
                                    <!--<img src="/images/social/ins.png" alt="" class="ins" />-->
                                <!--</a>-->
                            <!--</li>-->
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        <section class="copyright text-center">
            <p>©2020 - GoPsys. All right reserved.</p>
        </section>
    </div>
</template>


<script>

export default {
    data() {
        return {
        }
    },
    computed: {

    },
    created() {
        this.loggedIn = !!this.$gate;
    },
    destroyed() {
    },
    methods: {
    }
}
</script>


<style lang="scss" scoped>
    footer.myfooter {
        padding: 80px 0 120px;
        background-color: #404f54;
        @media (max-width: 767.98px) {
            padding: 40px 0 0 0;
        }
    }

    .Lorem3 img {
        margin-bottom: 20px;
    }

    .footer2 h3 {
        margin-bottom: 30px;
        font-size: 16px;
        font-weight: 700;
        @media (max-width: 767.98px) {
            margin-top: 15px;
            margin-bottom: 15px;
        }
    }

    ul.footnav, ul.footnav2, .footnav22 {
        padding: 0;
        list-style-type: none;
    }

    ul.footnav li {
        margin-bottom: 25px;
        @media (max-width: 767.98px) {
            margin-bottom: 15px;
        }
        a {
            color: #e8eff7;
            text-decoration: none;
        }
    }

    ul.footnav2 li {
        display: inline-block;
        margin-right: 10px;
    }

    .copyright {
        padding: 14px 0;
        background-color: #3a474c;
        p {
            color: #e8eff7;
            font-size: 12px;
            font-weight: 600;
            margin: 0;
        }
    }
</style>
