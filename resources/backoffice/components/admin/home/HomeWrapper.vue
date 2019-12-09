<template>
    <v-container  fluid grid-list-xl>
        <v-layout row justify-center wrap>
            <v-flex lg10 sm12 xs12>
                <transition name="fade" mode="out-in">
                    <router-view></router-view>
                </transition>
            </v-flex>
        </v-layout>
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate color="amber"></v-progress-circular>
        </v-overlay>
    </v-container>
</template>
<script>
export default {
    components: {

    },

    data: () => ({
        overlay: false
    }),

    created() {
        this.initialize()
    },

    methods: {
        async initialize() {

        }
    }
}

</script>