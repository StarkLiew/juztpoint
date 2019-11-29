<template>
    <div class="fill-height">
        <v-navigation-drawer app v-model="show" :mini-variant.sync="$vuetify.breakpoint.mdAndUp && mini" :permanent="$vuetify.breakpoint.mdAndUp" light :clipped="$vuetify.breakpoint.mdAndUp" width="300">
            <app-nav @overlay="onOverlay" :mini="mini" @navToggle="onMiniToggle"></app-nav>
        </v-navigation-drawer>
        <top-menu @navToggle="onNavToggle" @overlay="onOverlay"></top-menu>
        <v-content>
            <v-container fluid>
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

export default {
    data: () => ({
        mini: true,
        show: true,
        overlay: false
    }),

    components: {
        AppNav,
        TopMenu,
        AppFooter
    },

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
        }

    }
}

</script>
