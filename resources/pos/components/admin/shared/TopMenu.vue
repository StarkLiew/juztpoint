<template>
    <v-app-bar dark :clipped-left="$vuetify.breakpoint.mdAndUp" fixed app color="primary">
        <v-app-bar-nav-icon @click.stop="navToggle"></v-app-bar-nav-icon>
        <v-toolbar-title class="white--text">{{ siteName }} of Sales</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-title class="white--text" v-if="auth">
            <v-icon>mdi-account-circle</v-icon>
            <span class="white--text caption">{{ auth.name }}</span>
        </v-toolbar-title>
    </v-app-bar>
</template>
<script>
import { settings } from '~/config'
import { mapGetters } from 'vuex'

export default {
    data: () => ({
        siteName: settings.siteName
    }),
    computed: mapGetters({
        auth: 'auth/user',
    }),

    methods: {
        navToggle() {
            this.$emit('nav-toggle')
        }
    }
}

</script>
