<template>
    <div class="fill-height">
        <app-nav @nav-toggle-update="navToggleUpdate" @overlay="onOverlay" :minified="mini" @nav-toggle="navToggle"></app-nav>
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
        mini: true,
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
        navToggleUpdate(val) {
           this.mini = val
        },
        onOverlay(status) {
            this.overlay = status
        }

    }
}

</script>
