<template>
    <div class="fill-height">
        <v-navigation-drawer app v-model="show" :mini-variant.sync="$vuetify.breakpoint.mdAndUp && mini" :permanent="$vuetify.breakpoint.mdAndUp" light :clipped="$vuetify.breakpoint.mdAndUp" width="300">
            <app-nav @overlay="onOverlay" :mini="mini" @navToggle="onMiniToggle"></app-nav>
        </v-navigation-drawer>
        <top-menu @navToggle="onNavToggle" @overlay="onOverlay"></top-menu>
        <v-content>
            <v-container fluid>
                <v-alert prominent type="error" v-if="!verified">
                    <v-row align="center">
                        <v-col class="grow">Unlock more feature by verify your email now. </v-col>
                        <v-col class="shrink">
                            <v-btn :loading="sending" :disabled="sending" @click="resend">Resend Email Verification</v-btn>
                        </v-col>
                    </v-row>
                </v-alert>
                <transition name="fade" mode="out-in">
                    <router-view></router-view>
                </transition>
            </v-container>
        </v-content>
        <app-footer></app-footer>
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
    </div>
</template>
<script>
import AppNav from './shared/AppNav'
import TopMenu from './shared/TopMenu'
import AppFooter from './shared/AppFooter'
import { mapGetters } from 'vuex'
import axios from 'axios'
import { api } from '~~/config'

export default {
    data: () => ({
        mini: true,
        show: true,
        overlay: false,
        sending: false,
    }),

    components: {
        AppNav,
        TopMenu,
        AppFooter
    },
    computed: mapGetters({
        verified: 'auth/verified',
    }),
    methods: {
        onNavToggle() {
            this.show = !this.show


        },
        onMiniToggle() {

            this.mini = !this.mini

        },
        onOverlay(status) {
            this.overlay = status
        },
        onMinified(val) {
            this.mini = val
        },
        async resend() {
            this.sending = true
            await axios.post(api.path('resend'), {})
                .then((res) => {
                    this.$toast.success('Email verification have been sent to your inbox.')
                })
                .catch(err => {
                    this.handleErrors(err.response.data.errors)

                })
                .then(() => {
                    this.sending = false
                })
            this.sending = false

        }

    }
}

</script>
