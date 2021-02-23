<template>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-xl navbar-dark fixed-top" :class="navbarClass">

            <!-- Brand -->
            <router-link :to="{name: 'home.home'}" class="navbar-brand">
                <img src="/images/logo-white.png" height="67" alt="white-logo">
            </router-link>
            <router-link :to="{name: 'home.home'}" class="navbar-brand2">
                <img src="/images/logo.png" alt="logoblue">
            </router-link>

            <!-- Toggler/collapsibe Button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar links -->
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <router-link :to="{name: 'public.category.index', params: {slug: 'psychic-readings'}}" class="nav-link">
                            psychic reading
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{name: 'public.category.index', params: {slug: 'love-and-relationships'}}" class="nav-link">
                            love & relationships
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{name: 'public.category.index', params: {slug: 'tarot-Readings'}}" class="nav-link">
                            tarot readings
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{name: 'public.category.index', params: {slug: 'astrology-readings'}}" class="nav-link">
                            astrology-readings
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{name: 'public.advisors.index'}" class="nav-link">
                            more
                        </router-link>
                    </li>
                </ul>
                <div class="nav navbar-nav navbar-right">
                    <div class="d-flex align-items-center">
                        <div class="nav-item">
                            <div class="searching">
                                <a @click.prevent="showSearch = true" href="#" class="nav-link">
                                    <i class="fa fa-search"></i>
                                </a>

                                <div class="search-inline" :class="{'search-visible': showSearch}">
                                    <input
                                            v-model="searchKeywords"
                                            @keyup.enter="search"
                                            placeholder="Search..."
                                            class="form-control"
                                            autocomplete="off"
                                            spellcheck="false"
                                            dir="auto"
                                            type="text">
                                    <button @click="search" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <a @click.prevent="showSearch = false" class="search-close">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div v-if="loggedIn" class="nav-item">
                            <router-link :to="{ name: 'admin.dashboard.index' }" class="nav-link text-capitalize">
                                <svg width="26" height="26" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                     viewBox="0 0 96 96" style="enable-background:new 0 0 96 96;" xml:space="preserve">
                                    <g id="XMLID_5_">
                                        <path :fill="dashboardIconColor" id="XMLID_11_" d="M18,51.3h26.7V18H18V51.3z M18,78h26.7V58H18V78z M51.3,78H78V44.7H51.3V78z M51.3,18v20H78V18
                                            H51.3z"/>
                                    </g>
                                </svg>
                                Dashboard
                            </router-link>
                        </div>
                        <div v-if="loggedIn" class="nav-item">
                            <router-link :to="{ name: 'admin.messages.index' }" class="nav-link msg-notification">
                                <svg width="22" height="22" viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg">
                                    <path :fill="messagesIconColor" d="M17.3871 11.9204V4.16446C17.3871 2.97098 16.432 2 15.2581 2H2.12903C0.955092 2 0 2.97098 0 4.16446V11.9204C0 13.1138 0.955092 14.0848 2.12903 14.0848H4.04006L4.08082 16.0391C4.09302 16.6262 4.75621 16.9536 5.21757 16.5999L8.49905 14.0849H15.2581C16.432 14.0848 17.3871 13.1138 17.3871 11.9204ZM8.26152 12.6418C8.10734 12.6418 7.95743 12.6929 7.83432 12.7872L5.47048 14.599L5.4444 13.3481C5.43619 12.9557 5.12093 12.6419 4.7349 12.6419H2.12903C1.73773 12.6419 1.41936 12.3182 1.41936 11.9204V4.1645C1.41936 3.76668 1.73773 3.44301 2.12903 3.44301H15.2581C15.6493 3.44301 15.9677 3.76668 15.9677 4.1645V11.9204C15.9677 12.3182 15.6493 12.6419 15.2581 12.6419H8.26152V12.6418ZM22 8.67372V14.6259C22 15.8194 21.0449 16.7904 19.871 16.7904H17.9569L17.9191 18.2968C17.9093 18.6888 17.5933 18.9999 17.2097 18.9999C16.9265 18.9999 17.0842 19.0498 13.5346 16.7904H10.2903C9.89837 16.7904 9.58066 16.4674 9.58066 16.0689C9.58066 15.6704 9.89837 15.3474 10.2903 15.3474C14.0545 15.3474 13.8787 15.3069 14.1151 15.4573L16.5319 16.9958L16.5556 16.0505C16.5655 15.6593 16.8801 15.3474 17.2651 15.3474H19.871C20.2623 15.3474 20.5807 15.0237 20.5807 14.6259V8.67363C20.5807 8.27585 20.2623 7.95216 19.871 7.95216C19.479 7.95216 19.1613 7.62916 19.1613 7.23068C19.1613 6.8322 19.479 6.50921 19.871 6.50921C21.0449 6.50925 22 7.4802 22 8.67372Z" />
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
                                Sign in
                            </router-link>
                        </div>
                        <div v-if="!loggedIn" class="nav-item">
                            <router-link :to="{ name: 'auth.customer_register' }" class="text-white nav-link btn-success">
                                Sign up
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>

        </nav>
    </div>
</template>


<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data() {
        return {
            loggedIn: false,
            searchKeywords: '',
            navbarClass: '',
            dashboardIconColor: '#ffffff',
            messagesIconColor: '#ffffff',
            showSearch: false,
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

        window.addEventListener('scroll', this.handleScroll);
    },
    destroyed () {
        window.removeEventListener('scroll', this.handleScroll);
    },
    methods: {
        ...mapActions('AdvisorsIndex', [
            'setQuery',
            'fetchData'
        ]),
        handleScroll(e) {
            if (window.scrollY > 86) {
                this.navbarClass = 'shrink';
                this.dashboardIconColor = '#00bff0';
                this.messagesIconColor = '#8397ab';
            } else {
                this.navbarClass = '';
                this.dashboardIconColor = '#ffffff';
                this.messagesIconColor = '#ffffff';
            }
        },
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
    }
}
</script>


<style lang="scss" scoped>
    .fixed-top {
        padding: .2em 40px;
        @media (max-width: 1199.98px) {
            div#collapsibleNavbar {
                background-color: rgba(0, 0, 0, .6);
                padding: 10px;
            }
        }
        a.navbar-brand2 {
            display: none;
            img {
                height: 45px;
            }
        }
        &.shrink {
            padding: 0 40px;
            @media (max-width: 767.98px) {
                padding: 5px 10px;
            }
            @media (max-width: 1199.98px) {
                div#collapsibleNavbar {
                    background-color: transparent;
                }
            }
            a.navbar-brand {
                display: none;
            }
            a.navbar-brand2 {
                display: block;
            }
        }
        @media (max-width: 767.98px) {
            position: relative;
            padding: 5px 10px;
            a.navbar-brand {
                display: none;
            }
            a.navbar-brand2 {
                display: block;
            }
        }
    }
    .shrink {
        box-shadow: 0 0 2px #eee;
        background-color: #fff;
        .navbar-nav .nav-item .nav-link {
            color: #404f54
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
    .navbar-nav .nav-item .nav-link {
        padding: 10px 12px;
        font-size: 14px;
        color: rgba(256,256,256,.80);
        text-transform: uppercase;
        line-height: 1;
        @media (max-width: 1199.98px) {
            display: inline-block;
            line-height: 15px !important;
            margin: 3px 5px !important;
        }
        &.btn-light {
            background-color: transparent;
            border: 1px solid #fff;
            border-radius: 22px;
            margin: 0 14px;
        }
    }
    ul.navbar-nav.ml-auto .nav-link {
        line-height: 34px !important;
        border-bottom: 2px solid transparent;
        margin: 3px 0 !important;
        @media (max-width: 1199.98px) {
            line-height: normal !important;
        }
        &:hover {
            border-bottom: 2px solid #86cd03;
        }
    }
    .search-inline {
        width: 100%;
        left: 0;
        top: 0;
        padding: 20px;
        height: 10%;
        position: absolute;
        opacity: 0;
        visibility: hidden;
        background-color: #fff;
        z-index: 9;
        transition: all .5s ease-in-out;
        &.search-visible {
            opacity: 1;
            padding: 20px;
            visibility: visible;
            animation: fadeInDown 0.4s ease-in-out;
        }
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translate3d(0, -20%, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        .form-control {
            border: 0;
            font-size: 16px;
            position: absolute;
            left: 1%;
            top: 0;
            height: 100%;
            width: 99%;
            font-weight: 700;
            outline: none;
        }
        button[type="submit"] {
            position: absolute;
            top: 0;
            border: 0;
            right: 80px;
            padding: 0;
            cursor: pointer;
            width: 80px;
            height: 99%;
            background: #222;
            color: #fff;
        }
        .search-close {
            position: absolute;
            top: 0;
            right: 0;
            color: #86cd03;
            width: 80px;
            height: 100%;
            text-align: center;
            display: table;
            background: #fff;
            text-decoration: none;
            cursor: pointer;
            i {
                display: table-cell;
                vertical-align: middle;
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
