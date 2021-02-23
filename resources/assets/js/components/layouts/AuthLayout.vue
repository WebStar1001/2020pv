<template>
    <div class="login">
        <div class="goback">
            <router-link :to="{ name: 'home.home' }" class="text-white">
                <i class="fa fa-long-arrow-left"></i> Go back to home
            </router-link>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-sm-12 offset-md-3 col-md-6 col-lg-4 offset-lg-4">
                    <div class="myloginpage">
                        <img src="/images/login/flower.png" alt="logo2">
                    </div>
                    <div class="panel bg-white panel-login">
                        <router-view v-bind="myProps" class="form-container"></router-view>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: ['error'],
        data() {
            return {

            }
        },
        created() {
          if (this.$gate) {
              this.$router.push({ name: 'admin.dashboard.index' });
          }
        },
        computed: {
            myProps() {
                return { error: this.error }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .login {
        background-image: url(/images/login/login-bg.jpg);
        background-color: #10bfea;
        text-align: center;
        color: #fff;
        padding: 100px 0;
        background-repeat: no-repeat;
        background-attachment: scroll;
        background-position: top center;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        min-height: 100vh;
        width: 100%;
    }

    .goback {
        position: absolute;
        top: 30px;
        left: 30px;
        .fa {
            margin-right: 15px;
            font-size: 18px;
        }
    }

    .myloginpage {
        margin-bottom: 40px;
    }
</style>