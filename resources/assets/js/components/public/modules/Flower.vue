<template>
    <section class="block flower">
        <div class="myflower text-center">
            <img src="/images/homepage/flower.png" alt="flower">
            <h2>Life, Love & Relationship Spiritual Guidance</h2>
            <p>We carefully screened and selected a range of astonishingly gifted psychic readers to provide clarity and new insights into your life.</p>
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
    .flower {
        background: #edf1f2;
        h2 {
            font-size: 30px;
            margin-top: 30px;
            @media (max-width: 767.98px) {
                font-size: 24px;
            }
        }
        p {
            font-size: 18px;
            color: #8d9599;
            width: 60%;
            margin: auto;
            @media (max-width: 767.98px) {
                width: 90%;
                font-size: 16px;
            }
        }
    }
</style>
