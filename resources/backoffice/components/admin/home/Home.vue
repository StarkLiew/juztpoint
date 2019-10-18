<template>
    <v-row>
        <v-col col="6" lg="6" md="6" sm="12">
            <v-card class="mx-auto" color="grey lighten-4" max-width="600">
                <v-card-title>
                    <v-icon :color="checking ? 'red lighten-2' : 'indigo'" class="mr-12" size="64" @click="takePulse">
                        mdi-heart-pulse
                    </v-icon>
                    <v-row align="start">
                        <div class="caption grey--text text-uppercase">
                            Heart rate
                        </div>
                        <div>
                            <span class="display-2 font-weight-black" v-text="avg || '—'"></span>
                            <strong v-if="avg">BPM</strong>
                        </div>
                    </v-row>
                    <v-spacer></v-spacer>
                    <v-btn icon class="align-self-start" size="28">
                        <v-icon>mdi-arrow-right-thick</v-icon>
                    </v-btn>
                </v-card-title>
                <v-sheet color="transparent">
                    <v-sparkline :key="String(avg)" :smooth="16" :gradient="['#f72047', '#ffd200', '#1feaea']" :line-width="3" :value="heartbeats" auto-draw stroke-linecap="round"></v-sparkline>
                </v-sheet>
            </v-card>
        </v-col>
        <v-col col="6" lg="6" md="6" sm="12">
        </v-col>
    </v-row>
</template>
<script>
import { mapGetters } from 'vuex'
const exhale = ms =>
    new Promise(resolve => setTimeout(resolve, ms))

export default {
    components: {

    },
    data() {
        return {
            checking: false,
            heartbeats: [],
            items: [],
        }
    },
    computed: {
        avg() {
            const sum = this.heartbeats.reduce((acc, cur) => acc + cur, 0)
            const length = this.heartbeats.length

            if (!sum && !length) return 0

            return Math.ceil(sum / length)
        },
        created() {
            this.takePulse(false)
        },
    },
    async mounted() {

    },
    methods: {

        heartbeat() {
            return Math.ceil(Math.random() * (120 - 80) + 80)
        },
        async takePulse(inhale = true) {
            this.checking = true

            inhale && await exhale(1000)

            this.heartbeats = Array.from({ length: 20 }, this.heartbeat)

            this.checking = false
        },

        initialize() {
               return []
        }

    }
}

</script>
