<template>
    <section class="block Explore">
        <div class="Explore2 text-center">
            <h2 class="block-title">Explore advisors categories</h2>
        </div>
        <div class="container">
            <ul class="nav nav-pills nav-fill navtop2">
                <li class="nav-item">

                    <a class="nav-link active" href="#menu5" data-toggle="tab"><img class="pa1" src="/images/homepage/p1.png" alt="p1"> <img class="pa2" src="/images/homepage/Ball.svg" alt="Ball"><br>PSYCHIC</a>
                </li>
                <li class="nav-item">

                    <a class="nav-link" href="#menu6" data-toggle="tab"><img class="pa2" src="/images/homepage/p2.png" alt="p2"> <img class="pa1" src="/images/homepage/card.svg" alt="card"> <br>TARROT</a>
                </li>
                <li class="nav-item">

                    <a class="nav-link" href="#menu7" data-toggle="tab">	<img class="pa2" src="/images/homepage/p3.png" alt="p3"><img class="pa1" src="/images/homepage/lovr.svg" alt="lovr"><br>LOVE</a>
                </li>
                <li class="nav-item">

                    <a class="nav-link" href="#menu8" data-toggle="tab"> <img class="pa2" src="/images/homepage/p4.png" alt="p4"><img class="pa1" src="/images/homepage/star.svg" alt="star"><br>ASTROLOGY</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" role="tabpanel" id="menu5">
                    <div class="col-md-12 col-lg-10 offset-lg-1">
                        <div class="row">

                            <div class="col-md-8 col-lg-7">
                                <div class="box2">
                                    <img src="/images/homepage/reading.jpg" alt="reading" class="img-fluid">

                                </div>
                            </div>
                            <div class="col-md-4 col-lg-5">
                                <div class="box3">
                                    <h3>Psychic Reading</h3>
                                    <p>Psychic Insights Await Guide your journey with psychic advice.</p>
                                    <ul class="Lorem">
                                        <li>
                                            <router-link :to="{name: 'public.category.index', params: {slug: 'rune-casting'}}">
                                                <img src="/images/homepage/check.png" alt="check">
                                                Rune casting
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{name: 'public.category.index', params: {slug: 'remote-viewing'}}">
                                                <img src="/images/homepage/check.png" alt="check">
                                                Remote viewing
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{name: 'public.category.index', params: {slug: 'angel-card-reading'}}">
                                                <img src="/images/homepage/check.png" alt="check">
                                                Angel card reading
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{name: 'public.category.index', params: {slug: 'aura-reading'}}">
                                                <img src="/images/homepage/check.png" alt="check">
                                                Aura reading
                                            </router-link>
                                        </li>
                                    </ul>
                                    <div class="viewall">
                                        <router-link :to="{name: 'public.category.index', params: {slug: 'psychic-readings'}}">
                                            VIEW ALL <img src="/images/homepage/arrow.png" alt="arrow">
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" role="tabpanel" id="menu6">
                    <div class="col-md-12 col-lg-10 offset-lg-1">
                        <div class="row">

                            <div class="col-md-8 col-lg-7">
                                <div class="box2">
                                    <img src="/images/homepage/reading2.jpg" alt="reading2" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-5">
                                <div class="box3">
                                    <h3>TARROT Reading</h3>
                                    <p>The cards will reveal your next adventure.</p>
                                    <ul class="Lorem">
                                        <li>
                                            <router-link :to="{name: 'public.category.index', params: {slug: 'western-astrology'}}">
                                                <img src="/images/homepage/check.png" alt="check">
                                                Western Astrology
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{name: 'public.category.index', params: {slug: 'chinese-astrology'}}">
                                                <img src="/images/homepage/check.png" alt="check">
                                                Chineese Astrology
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{name: 'public.category.index', params: {slug: 'vedic-astrology'}}">
                                                <img src="/images/homepage/check.png" alt="check">
                                                Indian Astrology
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{name: 'public.category.index', params: {slug: 'dream-analysis'}}">
                                                <img src="/images/homepage/check.png" alt="check">
                                                Dream Analysis
                                            </router-link>
                                        </li>
                                    </ul>
                                    <div class="viewall">
                                        <router-link :to="{name: 'public.category.index', params: {slug: 'tarot-Readings'}}">
                                            VIEW ALL <img src="/images/homepage/arrow.png" alt="arrow">
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" role="tabpanel" id="menu7">
                    <div class="col-md-12 col-lg-10 offset-lg-1">
                        <div class="row">

                            <div class="col-md-8 col-lg-7">
                                <div class="box2">
                                    <img src="/images/homepage/reading3.jpg" alt="reading3" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-5">
                                <div class="box3">
                                    <h3>LOVE Reading</h3>
                                    <p>Psychic Love Readings Find out if summer love lies ahead.</p>
                                    <ul class="Lorem">
                                        <li>
                                            <router-link :to="{name: 'public.category.index', params: {slug: 'intimacy'}}">
                                                <img src="/images/homepage/check.png" alt="check">
                                                Romanace And Intimacy
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{name: 'public.category.index', params: {slug: 'soulmate-connections'}}">
                                                <img src="/images/homepage/check.png" alt="check">
                                                Soulmates
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{name: 'public.category.index', params: {slug: 'gay-and-lesbian-friendly'}}">
                                                <img src="/images/homepage/check.png" alt="check">
                                                Gay & Lesbian Friendly
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{name: 'public.category.index', params: {slug: 'marital-life'}}">
                                                <img src="/images/homepage/check.png" alt="check">
                                                Marital Life
                                            </router-link>
                                        </li>
                                    </ul>
                                    <div class="viewall">
                                        <router-link :to="{name: 'public.category.index', params: {slug: 'love-and-relationships'}}">
                                            VIEW ALL <img src="/images/homepage/arrow.png" alt="arrow">
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" role="tabpanel" id="menu8">
                    <div class="col-md-12 col-lg-10 offset-lg-1">
                        <div class="row">

                            <div class="col-md-8 col-lg-7">
                                <div class="box2">
                                    <img src="/images/homepage/reading4.jpg" alt="reading4" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-5">
                                <div class="box3">
                                    <h3>ASTROLOGY Reading</h3>
                                    <p>Unlock hidden zodiac wisdom. Receive answers today.</p>
                                    <ul class="Lorem">
                                        <li>
                                            <router-link :to="{name: 'public.category.index', params: {slug: 'astrology-readings'}}">
                                                <img src="/images/homepage/check.png" alt="check">
                                                Astrology Readings
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{name: 'public.category.index', params: {slug: 'astrology-readings'}}">
                                                <img src="/images/homepage/check.png" alt="check">
                                                Vedic Astrology
                                            </router-link>
                                        </li>
                                    </ul>
                                    <div class="viewall">
                                        <router-link :to="{name: 'public.category.index', params: {slug: 'vedic-astrology'}}">
                                            VIEW ALL <img src="/images/homepage/arrow.png" alt="arrow">
                                        </router-link>
                                    </div>
                                </div>
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
    .Explore {
        background-color: #edf1f2;
        ul.nav.nav-pills.nav-fill.navtop2 {
            margin: 0 160px;
            @media (max-width: 991.98px) and (min-width: 768px) {
                margin: 0 130px;
            }
            @media (max-width: 767.98px) {
                margin: 0 12px;
            }
        }
        .nav-fill {
            .nav-item {
                -ms-flex: 1 1 auto;
                flex: 1 1 auto;
                text-align: center;
                @media (max-width: 767.98px) {
                    width: 50%;
                    margin-bottom: 20px;
                }
                .nav-link {
                    display: block;
                    padding: .5rem 1rem;
                    color: #96a0a3;
                    font-weight: 600;
                    .pa1 {
                        display: none;
                    }
                    img {
                        display: inline-block;
                        height: 48px;
                        margin-bottom: 12px;
                    }
                    &.active {
                        color: #404f54;
                        background-color: transparent;
                        border-bottom: 2px solid #38cbf1;
                        border-radius: 0;
                        .pa1 {
                            display: inline-block;
                        }
                        .pa2 {
                            display: none;
                        }
                    }
                }
            }
        }
        .tab-content {
            margin: 40px 0;
        }
        .box3 {
            background-color: #fff;
            box-shadow: 0 16px 19px rgba(0,0,0,.11);
            padding: 50px 60px;
            margin: 30px 0 0 -70px;
            @media (max-width: 991.98px) and (min-width: 768px) {
                padding: 20px 20px;
                margin: 25px 0 0 -70px;
            }
            @media (max-width: 767.98px) {
                padding: 30px 20px;
                margin: 30px 0 0 0;
            }
            h3 {
                font-size: 22px;
                font-weight: 600;
            }
            p {
                color: #8d9599;
                font-size: 16px;
            }
            ul.Lorem {
                padding: 0;
                list-style-type: none;
                li {
                    margin-top: 20px;
                    color: #8d9599;
                    font-size: 16px;
                    a {
                        color: #8d9599;
                    }
                }
            }
            .viewall {
                a {
                    padding: 10px;
                    display: inline-block;
                    color: #86cd03;
                    margin-top: 30px;
                }
                img {
                    margin: -6px 0px 0 12px;
                }
            }
        }
    }
</style>
