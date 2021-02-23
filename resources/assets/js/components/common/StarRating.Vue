<!--https://vuejsexamples.com/a-highly-dynamic-vue-stars-rating-component/-->
<template>
    <div class="star-rating">
        <div v-for="(star, index) in stars" :key="index" class="star-container">
            <svg
                    class="star-svg"
                    :style="[
          { fill: `url(#gradient-${gradientId}-${star.raw})` },
          { width: style.starWidth },
          { height: style.starHeight }
        ]"
            >
                <polygon :points="getStarPoints" style="fill-rule:nonzero;" />
                <defs>
                    <!--
                      id has to be unique to each star fullness(dynamic offset) - it indicates fullness above
                    -->
                    <linearGradient :id="`gradient-${gradientId}-${star.raw}`">
                        <stop
                                id="stop1"
                                :offset="star.percent"
                                stop-opacity="1"
                                :stop-color="getFullFillColor(star)"
                        ></stop>
                        <stop
                                id="stop2"
                                :offset="star.percent"
                                stop-opacity="0"
                                :stop-color="getFullFillColor(star)"
                        ></stop>
                        <stop
                                id="stop3"
                                :offset="star.percent"
                                stop-opacity="1"
                                :stop-color="style.emptyStarColor"
                        ></stop>
                        <stop
                                id="stop4"
                                offset="100%"
                                stop-opacity="1"
                                :stop-color="style.emptyStarColor"
                        ></stop>
                    </linearGradient>
                </defs>
            </svg>
        </div>
        <div v-if="isIndicatorActive" class="indicator">{{ config.rating }}</div>
    </div>
</template>

<script>
    export default {
        name: "stars-rating",
        components: {},
        props: ["config"],
        data: function() {
            return {
                rating: 0,
                stars: [],
                emptyStar: 0,
                fullStar: 1,
                totalStars: 5,
                isIndicatorActive: false,
                style: {
                    fullStarColor: "#f5a623",
                    emptyStarColor: "#737373",
                    starWidth: 20,
                    starHeight: 20
                },
                gradientId: null,
            };
        },
        created() {
            this.rating = this.config.rating;
            this.totalStars = this.config.totalStars ? this.config.totalStars : this.totalStars;
            this.gradientId = Math.random();

            this.initStars();
            this.setStars();
            this.setConfigData();
        },
        directives: {},
        watch: {
            config: {
                handler(config) {
                    this.rating = config.rating;

                    this.initStars();
                    this.setStars();
                },
            },
        },
        computed: {
            getStarPoints: function() {
                let centerX = this.style.starWidth / 2;
                let centerY = this.style.starHeight / 2;

                let innerCircleArms = 5; // a 5 arms star

                let innerRadius = this.style.starWidth / innerCircleArms;
                let innerOuterRadiusRatio = 2.5; // Unique value - determines fatness/sharpness of star
                let outerRadius = innerRadius * innerOuterRadiusRatio;

                return this.calcStarPoints(
                    centerX,
                    centerY,
                    innerCircleArms,
                    innerRadius,
                    outerRadius
                );
            }
        },
        methods: {
            calcStarPoints(
                centerX,
                centerY,
                innerCircleArms,
                innerRadius,
                outerRadius
            ) {
                let angle = Math.PI / innerCircleArms;
                let angleOffsetToCenterStar = 60;

                let totalArms = innerCircleArms * 2;
                let points = "";
                for (let i = 0; i < totalArms; i++) {
                    let isEvenIndex = i % 2 == 0;
                    let r = isEvenIndex ? outerRadius : innerRadius;
                    let currX = centerX + Math.cos(i * angle + angleOffsetToCenterStar) * r;
                    let currY = centerY + Math.sin(i * angle + angleOffsetToCenterStar) * r;
                    points += currX + "," + currY + " ";
                }
                return points;
            },
            initStars() {
                this.stars = [];
                for (let i = 0; i < this.totalStars; i++) {
                    this.stars.push({
                        raw: this.emptyStar,
                        percent: this.emptyStar + "%"
                    });
                }
            },
            setStars() {
                let fullStarsCounter = Math.floor(this.rating);
                for (let i = 0; i < this.stars.length; i++) {
                    if (fullStarsCounter !== 0) {
                        this.stars[i].raw = this.fullStar;
                        this.stars[i].percent = this.calcStarFullness(this.stars[i]);
                        fullStarsCounter--;
                    } else {
                        let surplus = Math.round((this.rating % 1) * 10) / 10; // Support just one decimal
                        let roundedOneDecimalPoint = Math.round(surplus * 10) / 10;
                        this.stars[i].raw = roundedOneDecimalPoint;
                        return (this.stars[i].percent = this.calcStarFullness(this.stars[i]));
                    }
                }
            },
            setConfigData() {
                if (this.config) {
                    this.setBindedProp(this.style, this.config.style, "fullStarColor");
                    this.setBindedProp(this.style, this.config.style, "emptyStarColor");
                    this.setBindedProp(this.style, this.config.style, "starWidth");
                    this.setBindedProp(this.style, this.config.style, "starHeight");
                    if (this.config.isIndicatorActive) {
                        this.isIndicatorActive = this.config.isIndicatorActive;
                    }
                }
            },
            getFullFillColor(starData) {
                return starData.raw !== this.emptyStar
                    ? this.style.fullStarColor
                    : this.style.emptyStarColor;
            },
            calcStarFullness(starData) {
                let starFullnessPercent = starData.raw * 100 + "%";
                return starFullnessPercent;
            },
            setBindedProp(localProp, propParent, propToBind) {
                if (propParent[propToBind]) {
                    localProp[propToBind] = propParent[propToBind];
                }
            },
        },
    };
</script>

<style scoped lang="scss">
    .star-rating {
        display: flex;
        align-items: center;
        .star-container {
            display: flex;
            width: 16px;
            height: 16px;
        }
        .star-svg {
            margin-right: 3px;
            &:last-child {
                margin-right: 0;
            }
        }
    }
</style>
