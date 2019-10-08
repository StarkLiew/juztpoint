<template>
    <div class="fill-height">
        <app-nav @overlay="onOverlay" :mini="mini" @nav-toggle="navToggle"></app-nav>
        <top-menu @nav-toggle="navToggle"></top-menu>
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
        mini: false,
        overlay: false
    }),

    components: {
        AppNav,
        TopMenu,
        AppFooter
    },

    methods: {
        navToggle() {
            this.mini = !this.mini
        },
        onOverlay(status) {
            this.overlay = status
        }

    }
}

</script>
