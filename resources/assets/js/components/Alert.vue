<template>
    <div v-if="message" class="alert-container">
        <b-alert :variant="color" show dismissible>
            <span v-if="!errors" v-html="message"></span>

            <ul v-if="errors" style="display: flex;flex-direction: column; margin: 0">
                <li v-for="(value, key) in errors">
                    {{ value[0] }}
                </li>
            </ul>
        </b-alert>
    </div>
</template>


<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data() {
        return {
            // Code...
        }
    },
    computed: {
        ...mapGetters('Alert', ['message', 'errors', 'color'])
    },
    created() {
        // Code...
    },
    destroyed() {
        this.resetState()
    },
    watch: {
        "$route.params.id": function() {
            this.resetState()
        }
    },
    methods: {
        ...mapActions('Alert', ['resetState'])
    }
}
</script>


<style lang="scss" scoped>
    ul {
        margin: 0;
        padding: 0;
        li {
            list-style: none;
        }
    }

    .alert-container {
        margin: 12px 15px 0 15px
    }
</style>
