<template>
    <div id="layout-container">
        <div @click.prevent="closeNav()" id="sidebar-overflow" style="display: none;"></div>

        <!-- start mobile sidenav -->
        <div id="sidenav" class="sidenav">
            <div class="sidenav-content">
                <sidebar></sidebar>
            </div>
        </div>
        <!-- end mobile sidenav-->

        <div id="main-container">
            <site-header />

            <div class="d-flex flex-column flex-lg-row header-padding">

                <!-- start desktop sidebar -->
                <div class="desktop-sidebar d-none d-lg-block">
                    <sidebar></sidebar>
                </div>
                <!-- end desktop sidebar -->

                <!-- start content -->
                <div class="main-content">
                    <bootstrap-alert />
                    <router-view></router-view>
                </div>
                <!-- end content -->

            </div>

        </div>
    </div>
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
            closeNav() {
                document.getElementById("sidenav").style.width = "0";
                document.getElementById("main-container").style.left = "0";
                document.getElementById("sidebar-overflow").style.display = "none";
                document.getElementById('layout-container').classList.remove('no-overflow-scroll');
            }
        }
    }
</script>

<style lang="scss" scoped>
    .main-content {
        width: 100%;
        padding-left: 240px;
        @media (max-width: 991.98px) {
            padding-left: 0;
        }
        .content-box {
            padding: 25px 15px 55px 15px;
            overflow: visible;
            @media (max-width: 767.98px) {
                padding: 15px;
            }
        }
    }

    .sidenav {
        height: calc(100% - 50px);
        width: 0;
        position: fixed;
        z-index: 1000;
        top: 50px;
        left: 0;
        background-color: #404f54;
        overflow-x: hidden;
        transition: 0.5s;
        .sidenav-content {
            width: 240px;
        }
    }

    .desktop-sidebar {
        position: fixed;
        left: 0;
        top: 62px;
        z-index: 1;
        height: calc(100% - 62px);
        background: #404f54;
    }

    #main-container {
        position: relative;
        transition: left .5s;
        left: 0;
    }

    #sidebar-overflow {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 999;
    }

    .no-overflow-scroll {
        width: 100%;
        overflow-y: hidden;
    }

    .header-padding {
        padding-top: 62px;
    }
</style>