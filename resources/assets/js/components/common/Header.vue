<template>
    <div>
        <div class="container-fluid">
            <nav class="navbar navbar-expand-xl navbar-dark fixed-top">

                <div class="d-flex align-items-center">
                    <!-- Brand -->
                    <router-link :to="{name: 'home.home'}" class="navbar-brand2">
                        <img src="/images/logo.png" alt="GoPsys">
                    </router-link>

                    <div v-if="$store.getters.layout === 'app-layout'" class="d-block d-lg-none mobile-menu-btn ml-3">
                        <button @click="openNav()" class="navbar-toggler" type="button">
                            <span class="navbar-toggler-icon"></span> MENU
                        </button>
                    </div>
                </div>

                <!-- Toggler/collapsibe Button -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar links -->
                <div class="collapse navbar-collapse justify-content-center justify-content-lg-end" id="collapsibleNavbar">
                    <div class="d-flex align-items-center">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="/images/header/sidebar.png"/><span>ADVISORS</span>
                                </a>
                                <ul class="dropdown-menu category-menu" aria-labelledby="navbarDropdown">
                                    <li>
                                        <router-link :to="{name: 'public.category.index', params: {slug: 'psychic-readings'}}" class="dropdown-item">
                                            Psychic Reading
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'rune-casting'}}" class="dropdown-item">
                                                        Rune Casting
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'angel-card-reading'}}" class="dropdown-item">
                                                        Angel Card Reading
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'psychic-mediums'}}" class="dropdown-item">
                                                        Psychic Mediums
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'aura-reading'}}" class="dropdown-item">
                                                        Aura Reading
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'remote-viewing'}}" class="dropdown-item">
                                                        Remote Viewing
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'uk-psychics'}}" class="dropdown-item">
                                                        Uk Psychics
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'western-astrology'}}" class="dropdown-item">
                                                        Western Astrology
                                                    </router-link>
                                                </li>
                                            </ul>
                                        </router-link>
                                    </li>
                                    <li>
                                        <router-link :to="{name: 'public.category.index', params: {slug: 'love-and-relationships'}}" class="dropdown-item">
                                            Love & Relationships
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'marital-life'}}" class="dropdown-item">
                                                        Marital Life
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'soulmate-connections'}}" class="dropdown-item">
                                                        Soulmate Connections
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'cheating-affairs'}}" class="dropdown-item">
                                                        Cheating Affairs
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'parents-and-children'}}" class="dropdown-item">
                                                        Parents & Children
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'gay-and-lesbian-friendly'}}" class="dropdown-item">
                                                        Gay & Lesbian Friendly
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'breakup-divorce'}}" class="dropdown-item">
                                                        Breakup Divorce
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'single-and-dating'}}" class="dropdown-item">
                                                        Single & Dating
                                                    </router-link>
                                                </li>
                                            </ul>
                                        </router-link>
                                    </li>
                                    <li>
                                        <router-link :to="{name: 'public.category.index', params: {slug: 'tarot-Readings'}}" class="dropdown-item">
                                            Tarot Reading
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'cartomancy'}}" class="dropdown-item">
                                                        Cartomancy
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'crystal-healing'}}" class="dropdown-item">
                                                        Crystal Healing
                                                    </router-link>
                                                </li>
                                            </ul>
                                        </router-link>
                                    </li>
                                    <li>
                                        <router-link :to="{name: 'public.category.index', params: {slug: 'astrology-readings'}}" class="dropdown-item">
                                            Astrology Readings
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'vedic-astrology'}}" class="dropdown-item">
                                                        Vedic Astrology
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'chinese-astrology'}}" class="dropdown-item">
                                                        Chinese Astrology
                                                    </router-link>
                                                </li>
                                            </ul>
                                        </router-link>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-item">
                                            More
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'fortune-telling'}}" class="dropdown-item">
                                                        Fortune Telling
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'career-advice'}}" class="dropdown-item">
                                                        Career Advice
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'eastern-philosophy'}}" class="dropdown-item">
                                                        Eastern Philosophy
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'palm-readings'}}" class="dropdown-item">
                                                        Palm Readings
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'picture-reading'}}" class="dropdown-item">
                                                        Picture Reading
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'universal-laws'}}" class="dropdown-item">
                                                        Universal Laws
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'ghosts'}}" class="dropdown-item">
                                                        Ghosts
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'new-age-spirituality'}}" class="dropdown-item">
                                                        New Age Spirituality
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'kabbalah'}}" class="dropdown-item">
                                                        Kabbalah
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'dream-analysis'}}" class="dropdown-item">
                                                        Dream Analysis
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'occult'}}" class="dropdown-item">
                                                        Occult
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'intimacy'}}" class="dropdown-item">
                                                        Intimacy
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'religion'}}" class="dropdown-item">
                                                        Religion
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'numberology'}}" class="dropdown-item">
                                                        Numerology
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'paranormal'}}" class="dropdown-item">
                                                        Paranormal
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'financial-outlook'}}" class="dropdown-item">
                                                        Financial Outlook
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'graphology'}}" class="dropdown-item">
                                                        Graphology
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name: 'public.category.index', params: {slug: 'past-life-readings'}}" class="dropdown-item">
                                                        Past Life Readings
                                                    </router-link>
                                                </li>
                                            </ul>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--<li class="nav-item dropdown">-->
                                <!--<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
                                    <!--HOROSCOPE-->
                                <!--</a>-->
                                <!--<div class="dropdown-menu" aria-labelledby="navbarDropdown2">-->
                                    <!--<br>-->
                                    <!--<a class="dropdown-item item-cs" href="#"><img src="/images/drop-main.png"> <span>Aquarius</span></a>-->
                                    <!--<a class="dropdown-item item-cs" href="#"><img src="/images/drop-main.png"> Pices</a>-->
                                    <!--<a class="dropdown-item item-cs" href="#"><img src="/images/drop-main.png"> Aries </a>-->
                                    <!--<a class="dropdown-item item-cs" href="#"><img src="/images/drop-main.png"> Tourus</a>-->
                                    <!--<a class="dropdown-item item-cs" href="#"><img src="/images/drop-main.png"> Gemini</a>-->
                                    <!--<a class="dropdown-item item-cs" href="#"><img src="/images/drop-main.png"> Cancer </a>-->
                                    <!--<a class="dropdown-item item-cs" href="#"><img src="/images/drop-item-2.png"> &nbsp;Virgo</a>-->
                                    <!--<a class="dropdown-item item-cs" href="#"><img src="/images/drop-3.png"> Scorpio</a>-->
                                    <!--<a class="dropdown-item item-cs" href="#"><img src="/images/drop-4.png"> Capricom</a>-->
                                <!--</div>-->
                            <!--</li>-->
                        </ul>

                        <div id="custom-search-input">
                            <div class="input-group search-query2">
                                <input
                                        v-model="searchKeywords"
                                        @keyup.enter="search"
                                        placeholder="Search for an advisor"
                                        class="search-query form-control"
                                        autocomplete="off"
                                        spellcheck="false"
                                        dir="auto"
                                        type="text">

                                <span @click="search" class="input-group-btn">
                                            <button class="btn" type="button">
                                              <i class="fa fa-search"></i>
                                            </button>
                                        </span>
                            </div>
                        </div>
                    </div>

                    <div class="nav navbar-nav navbar-right">
                        <div class="d-flex align-items-center">
                            <div v-if="loggedIn" class="nav-item">
                                <router-link :to="{ name: 'admin.dashboard.index' }" class="nav-link">
                                    <svg width="26" height="26" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                     viewBox="0 0 96 96" style="enable-background:new 0 0 96 96;" xml:space="preserve">
                                        <g id="XMLID_5_">
                                            <path fill="#00bff0" id="XMLID_11_" d="M18,51.3h26.7V18H18V51.3z M18,78h26.7V58H18V78z M51.3,78H78V44.7H51.3V78z M51.3,18v20H78V18
                                                H51.3z"/>
                                        </g>
                                    </svg>
                                    Dashboard
                                </router-link>
                            </div>
                            <div v-if="loggedIn" class="nav-item">
                                <router-link :to="{ name: 'admin.messages.index' }" class="nav-link msg-notification">
                                    <svg width="22" height="22" viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg">
                                        <path fill="#8397ab" d="M17.3871 11.9204V4.16446C17.3871 2.97098 16.432 2 15.2581 2H2.12903C0.955092 2 0 2.97098 0 4.16446V11.9204C0 13.1138 0.955092 14.0848 2.12903 14.0848H4.04006L4.08082 16.0391C4.09302 16.6262 4.75621 16.9536 5.21757 16.5999L8.49905 14.0849H15.2581C16.432 14.0848 17.3871 13.1138 17.3871 11.9204ZM8.26152 12.6418C8.10734 12.6418 7.95743 12.6929 7.83432 12.7872L5.47048 14.599L5.4444 13.3481C5.43619 12.9557 5.12093 12.6419 4.7349 12.6419H2.12903C1.73773 12.6419 1.41936 12.3182 1.41936 11.9204V4.1645C1.41936 3.76668 1.73773 3.44301 2.12903 3.44301H15.2581C15.6493 3.44301 15.9677 3.76668 15.9677 4.1645V11.9204C15.9677 12.3182 15.6493 12.6419 15.2581 12.6419H8.26152V12.6418ZM22 8.67372V14.6259C22 15.8194 21.0449 16.7904 19.871 16.7904H17.9569L17.9191 18.2968C17.9093 18.6888 17.5933 18.9999 17.2097 18.9999C16.9265 18.9999 17.0842 19.0498 13.5346 16.7904H10.2903C9.89837 16.7904 9.58066 16.4674 9.58066 16.0689C9.58066 15.6704 9.89837 15.3474 10.2903 15.3474C14.0545 15.3474 13.8787 15.3069 14.1151 15.4573L16.5319 16.9958L16.5556 16.0505C16.5655 15.6593 16.8801 15.3474 17.2651 15.3474H19.871C20.2623 15.3474 20.5807 15.0237 20.5807 14.6259V8.67363C20.5807 8.27585 20.2623 7.95216 19.871 7.95216C19.479 7.95216 19.1613 7.62916 19.1613 7.23068C19.1613 6.8322 19.479 6.50921 19.871 6.50921C21.0449 6.50925 22 7.4802 22 8.67372Z" />
                                    </svg>
                                    <span v-if="$store.getters.unreadMessages">
                                        {{ $store.getters.unreadMessages }}
                                    </span>
                                </router-link>
                            </div>
                            <div v-if="loggedIn" class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown4" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="avatar" :style="'background-image: url(' + $gate.user.avatar + ')'"></div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right drop-profile" aria-labelledby="navbarDropdown4">
                                    <a class="dropdown-item custom-drop" href="#">
                                        <div class="d-flex">
                                            <div class="avatar" :style="'background-image: url(' + $gate.user.avatar + ')'" style="width: 45px; height: 45px"></div>

                                            <div class="pl-2 pt-1">
                                                <strong>{{ $gate.user.display_name }}</strong><br>
                                                <span>{{ $gate.user.email }}</span>
                                            </div>
                                        </div>
                                    </a>

                                    <router-link v-if="$gate.user.role_id === $roles.CUSTOMER" :to="{ name: 'admin.my_psychics.index' }" class="dropdown-item drop-item">
                                        <img src="/images/header/profile.svg"> &nbsp; My Psychics
                                    </router-link>

                                    <router-link v-if="$gate.user.role_id === $roles.ADVISOR" :to="{ name: 'admin.my_clients.index' }" class="dropdown-item drop-item">
                                        <img src="/images/header/profile.svg"> &nbsp; My Clients
                                    </router-link>

                                    <router-link :to="{ name: 'admin.settings.index' }" class="dropdown-item drop-item">
                                        <img src="/images/header/gear.svg"> &nbsp; Settings
                                    </router-link>

                                    <a @click.prevent="logout()" class="dropdown-item drop-item" href="#"><img src="/images/header/logout.svg"> &nbsp; Sign out</a>
                                </div>
                            </div>
                            <div v-if="!loggedIn" class="nav-item">
                                <router-link :to="{ name: 'auth.login' }" class="nav-link btn-light">
                                    SIGN IN
                                </router-link>
                            </div>
                            <div v-if="!loggedIn" class="nav-item">
                                <router-link :to="{ name: 'auth.customer_register' }" class="text-white nav-link btn-success btn-signup">
                                    SIGN UP
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>

            </nav>
        </div>
        
        <audio ref="soundPing" preload="auto" style="display: none">
            <source src="/sounds/ping.ogg" type="audio/ogg">
            <source src="/sounds/ping.webm" type="audio/webm">
            <source src="/sounds/ping.mp3" type="audio/mp3">
        </audio>
    </div>
</template>


<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data() {
        return {
            loggedIn: false,
            searchKeywords: '',
            query: {
                sort: 'id',
                order: 'desc',
                offset: 0,
                limit: 10,
                availability: '',
                minPrice: null,
                maxPrice: null,
                search: null
            },
        }
    },
    watch: {
    },
    created() {
        if (this.$route.name === 'public.advisors.index' && this.$route.query.search) {
            this.searchKeywords = this.$route.query.search;
        }

        this.loggedIn = !!this.$gate;

        if (this.$gate) {
            Echo.private('email_chat')
                .listen('UnreadMessagesUpdated', (e) => {
                    if (e.user_id === this.$gate.user.id) {
                        if (this.$route.name !== 'admin.messages.show' || +this.$route.params.subjectId !== e.email_subject_id) {
                            if (e.count > this.$store.getters.unreadMessages) {
                                this.$store.dispatch('setUnreadMessages', e.count);
                                this.$refs.soundPing.play();
                            }
                        } else {
                            if (this.$store.getters.unreadMessages > e.count) {
                                this.$store.dispatch('setUnreadMessages', e.count);
                            }
                        }
                    }
                });
        }
    },
    destroyed() {
        // this.resetState()
    },
    methods: {
        ...mapActions('AdvisorsIndex', [
            'setQuery',
            'fetchData'
        ]),
        logout() {
            axios.post('/logout').then(resp => {
                window.location.href = '/'
            });
        },
        search(e) {
            if (this.$route.name === 'public.advisors.index') {
                this.query.search = this.searchKeywords;
                this.setQuery({query: this.query});

                this.fetchData();
            } else {
                this.$router.push({ name: 'public.advisors.index', query: {search: this.searchKeywords} });
            }
        },
        openNav() {
            document.getElementById("sidenav").style.width = "240px";
            document.getElementById("main-container").style.left = "240px";
            document.getElementById("sidebar-overflow").style.display = "block";
            document.getElementById('layout-container').classList.add('no-overflow-scroll');
        },
    }
}
</script>


<style lang="scss" scoped>
    .fixed-top {
        padding: .2em 40px;
        background: #ffffff;
        @media (max-width: 767.98px) {
            /*position: relative;*/
            padding: 5px 10px;
        }
        @media (max-width: 1199.98px) {
            div#collapsibleNavbar {
                background-color: #ffffff;
                padding: 10px 0 0 0;
            }
        }
        a.navbar-brand2 {
            img {
                height: 45px;
            }
        }
    }
    .navbar-dark .navbar-toggler {
        border-color: #fff;
        padding: 0;
        &:focus {
            outline: none;
        }
        .navbar-toggler-icon {
            background: url("/images/header/toggler.svg") no-repeat center center;
            background-size: 20px 14px;
        }
    }
    .mobile-menu-btn {
        .navbar-toggler {
            padding: 0 5px !important;
            background: #86cd03;
            .navbar-toggler-icon {
                background: url("/images/header/toggler-white.svg") no-repeat center center !important;
            }
        }
    }
    .navbar-nav .nav-item .nav-link {
        padding: 10px 12px;
        font-size: 14px;
        color: #404f54;
        text-transform: uppercase;
        line-height: 1;
        @media (max-width: 1199.98px) {
            display: inline-block;
            line-height: 15px !important;
            margin: 3px 0 !important;
        }
        &.btn-light {
            background-color: transparent;
            border: 1px solid #fff;
            border-radius: 22px;
            margin: 0 14px;
        }
    }
    .navbar-right .nav-item .nav-link {
        text-transform: none;
        border-bottom: 2px solid transparent;
        &:hover {
            color: #2fcbf3;
        }
        &.router-link-active {
            border-bottom: 2px solid #2fcbf3;
        }
    }
    ul.navbar-nav a {
        line-height: 34px !important;
        border-bottom: 2px solid transparent;
        &:hover {
            border-bottom: 2px solid #86cd03;
        }
    }
    .search-query2 {
        width: 420px;
        margin-left: 10px;
        margin-right: 10px;
        @media (max-width: 767.98px) {
            width: auto;
            margin-right: 0;
        }
    }
    #custom-search-input {
        .search-query {
            -moz-border-radius: 3px;
            border-radius: 3px;
            -webkit-text-fill-color: #939393;
            background-color: #eefbfe;
            font-size: 14px;
            border: 0;
            font-weight: lighter;
            padding-right: 40px;
        }
        button {
            border: 0;
            background: none;
            padding: 3px 5px;
            margin-top: 4px;
            position: absolute;
            right: 6px;
            margin-bottom: 0;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            color: #00bff0;
            z-index: 999;
            width: 30px;
            box-shadow: 0 !important;
        }
    }
    .btn-signup {
        background: #ffffff;
        color: #86cd03 !important;
        border: 1px solid #86cd03;
        border-bottom: 1px solid #86cd03 !important;
        &:hover {
            color: #fff !important;
            border: 1px solid #86cd03;
            border-bottom: 1px solid #86cd03 !important;
            background-color: #86cd03;
        }
    }

    .category-menu {
        li {
            padding-left: 1px;
            padding-right: 1px;
        }
        .dropdown-item {
            border-bottom: 0 !important;
            font-size: 14px;
            padding: .05rem 1.5rem;
            min-width: 230px;
            &:hover {
                border-bottom: 0 !important;
                .dropdown-menu {
                    display: block;
                }
            }
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: -3px;
            left: 100%;
            min-height: 100%;
            ul {
                list-style: none;
                padding: 0;
            }
        }
    }

    .avatar {
        display: inline-block;
        vertical-align: middle;
        width: 30px;
        height: 30px;
        background-size: cover;
        background-position: center center;
        border-radius: 50%;
    }
</style>
