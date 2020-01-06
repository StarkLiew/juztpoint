<template>
    <v-app-bar dark :clipped-left="$vuetify.breakpoint.mdAndUp" fixed app color="primary">
        <v-app-bar-nav-icon v-if="$vuetify.breakpoint.smAndDown" @click.stop="navToggle"></v-app-bar-nav-icon>
        <v-toolbar-title class="white--text">{{ siteName }} BackOffice</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-title class="white--text" v-if="auth">
            <v-badge color="green" overlap>
                <template v-slot:badge>
                    <span v-if="messages > 0"></span>
                </template>
                <v-icon>mdi-bell</v-icon>
            </v-badge>
        </v-toolbar-title>
        <v-toolbar-title class="white--text" v-if="auth">
            <v-menu bottom left>
                <template v-slot:activator="{ on }">
                    <v-btn dark icon v-on="on">
                        <v-icon>mdi-account-box</v-icon>
                    </v-btn>
                </template>
                <v-list>
                     <v-list-item @click="logout">
                        <v-list-item-title>Log Out</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-toolbar-title>
    </v-app-bar>
</template>
<script>
import { settings } from '~~/config'
import { mapGetters } from 'vuex'

export default {
    data: () => ({
        siteName: settings.siteName,
        messages: []
    }),
    computed: mapGetters({
        auth: 'auth/user',
    }),
    methods: {
        async logout() {

            await this.$store.dispatch('auth/logout')
            this.$toast.success('Logged out!')
            this.$router.push({ name: 'login' })

        },
        navToggle() {
            this.$emit('navToggle')
        }
    }
}

</script>
