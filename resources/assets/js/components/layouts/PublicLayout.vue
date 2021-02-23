<template>
    <div>
        <site-header  />

        <section class="banner2" v-if="!($route.fullPath.includes('thank-you'))">
            <div class="container">
                <div class="goback2 text-left">
                    <router-link :to="{ name: 'home.home' }" class="ui-link">
                        <i class="fa fa-long-arrow-left"></i>Home
                    </router-link>
                </div>
                <div class="row">
                    <div class="col-lg-12 abotss text-center">

                        <h2 class="text-capitalize" :class="{'no-description': !description}">{{ title }}</h2>
                        <p v-if="description">{{ description }}</p>
                    </div>
                </div>
            </div>
        </section>

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
    .abotss {
        position: relative;
        h2 {
            font-size: 42px;
            font-weight: 600;
            text-shadow: 0 4px 8px rgba(15,89,83,.51);
            /*color: #fff;*/
            /*margin-top: 26px;*/
            &.no-description {
                /*margin-top: 44px;*/
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