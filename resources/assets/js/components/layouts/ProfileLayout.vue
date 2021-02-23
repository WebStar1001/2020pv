<template>
    <div>
        <site-header />

        <router-view></router-view>

        <site-footer />
    </div>
</template>

<script>
    export default {
        data() {
            return {
                title: '',
                description: ''
            }
        },
        created() {
            this.getPageTitle();
        },
        watch: {
            "$route": function () {
                console.log(this.$route);
                this.getPageTitle();
            }
        },
        methods: {
            getPageTitle() {
                this.title = '';
                this.description = '';

                switch (this.$route.name) {
                    case 'public.about_us':
                        this.title = 'About Us';
                        this.description = 'This tells about us and all the things about what';
                        break;
                    case 'public.terms_and_conditions':
                        this.title = 'Terms and Conditions';
                        break;
                    case 'public.privacy_policy':
                        this.title = 'Privacy Policy';
                        break;
                    case 'public.faq':
                        this.title = 'Need Help?';
                        this.description = 'Search for anything you are looking for and you might find help articles';
                        break;

                }

                if (this.$route.params.slug) {
                    this.title = this.$route.params.slug.replace('-and-', ' & ').split('-').join(' ')
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .banner2 {
        /*min-height: 300px;*/
        /*background-image: url(/images/header/about-bg.jpg);*/
        background-color: #10bfea;
        text-align: center;
        color: #fff;
        padding: 90px 0 0 0;
        background-repeat: no-repeat;
        background-attachment: scroll;
        background-position: center center;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
    .goback2 {
        a {
            font-size: 13px;
            font-weight: lighter;
        }
        .fa {
            margin-right: 15px;
            font-size: 18px;
        }
    }
    .abotss {
        position: relative;
        h2 {
            font-size: 42px;
            font-weight: 600;
            text-shadow: 0 4px 8px rgba(15,89,83,.51);
            color: #fff;
            margin-top: 26px;
            &.no-description {
                margin-top: 44px;
            }
            @media (max-width: 767.98px) {
                font-size: 30px;
            }
        }
        p {
            font-size: 24px;
            font-weight: lighter;
        }
    }
</style>