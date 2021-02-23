<template>
    <section class="block Testimonials">
        <div class="Testimonials2 text-center">
            <h2 class="block-title">Testimonials</h2>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-10 offset-lg-1">
                    <div class="card">
                        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" data-interval="100000">
                            <div class="w-100 carousel-inner" role="listbox">
                                <h3 class="text-center">Best Psychic platform ever, 100% satisfied!</h3>
                                <div class="carousel-item active">
                                    <div class="carousel-caption">
                                        <div class="row">
                                            <div class="col-sm-4 profiles">
                                                <img src="/images/homepage/john.jpg" alt="" class="john img-fluid">
                                                <div class="ratings">
                                                    <img src="/images/homepage/star2.png" alt="" class="star2"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-8 consectetur text-left">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud ed do eiusmod tempor incididunt ut labo.</p>
                                                <h4>John Doe</h4>
                                                <small>Customer since 2018</small>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="carousel-caption">
                                        <div class="row">
                                            <div class="col-sm-4 profiles">
                                                <img src="/images/homepage/john.jpg" alt="" class="john img-fluid">
                                                <div class="ratings">
                                                    <img src="/images/homepage/star2.png" alt="" class="star2"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-8 consectetur text-left">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud ed do eiusmod tempor incididunt ut labo.</p>
                                                <h4>John Doe</h4>
                                                <small>Customer since 2018</small>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="float-right navi">
                                <a class="" href="#carouselExampleControls" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon ico" aria-hidden="true"></span>
                                    <span class="sr-only2"><i class="fa fa-angle-left"></i></span>
                                </a>
                                <a class="" href="#carouselExampleControls" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon ico" aria-hidden="true"></span>
                                    <span class="sr-only2"><i class="fa fa-angle-right"></i></span>
                                </a>
                            </div>
                        </div>
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
    .Testimonials {
        background-color: #00bff0;
        h2.block-title {
            color: #fff;
        }
        .card {
            padding: 0 40px;
            overflow: hidden;
            @media (max-width: 767.98px) {
                padding: 0 20px;
            }
            .carousel-inner {
                overflow: visible;
                h3 {
                    padding-top: 20px;
                    font-size: 30px;
                    @media (max-width: 991.98px) and (min-width: 768px) {
                        font-size: 26px;
                    }
                    @media (max-width: 767.98px) {
                        font-size: 24px;
                    }
                }
            }
            .carousel-item {
                height: 260px;
                @media (max-width: 767.98px) {
                    height: 620px;
                }
            }
            .carousel-caption {
                padding: 0;
                right: 0;
                left: 0;
                color: #3d3d3d;
            }
            .profiles {
                position: relative;
                bottom: -80px;
            }
            .john {
                box-shadow: 0 12px 24px rgba(0,0,0,.19);
            }
            .ratings {
                position: absolute;
                top: 17px;
                background-color: #fff;
                padding: 17px;
                border-radius: 50%;
                box-shadow: 0 12px 14px rgba(0,0,0,.12);
                left: -11px;
                z-index: 999;
            }
            .consectetur {
                margin-top: 80px;
                @media (max-width: 767.98px) {
                    margin-top: 100px;
                }
                p {
                    color: #8d9599;
                    font-size: 16px;
                    padding-right: 40px;
                    @media (max-width: 767.98px) {
                        padding-right: 0;
                    }
                }
                h4 {
                    font-size: 22px;
                    font-weight: 600;
                    margin-top: 40px;
                }
                small {
                    font-size: 16px;
                    font-weight: 600;
                    color: #404f54;
                }
            }
            .float-right.navi {
                z-index: 999999;
                position: relative;
                top: -60px;
            }
            .carousel-control-prev-icon, .carousel-control-next-icon {
                background-image: none !important;
            }
            span.sr-only2 {
                color: #8d9599;
                font-size: 24px;
            }
        }
    }
</style>
