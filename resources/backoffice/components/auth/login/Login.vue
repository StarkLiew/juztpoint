<template>
    <v-flex sm8 md6 lg4>
        <v-card v-if="!hasToken()">
            <v-toolbar dark color="primary" flat>
                <v-toolbar-title>Back Office Login</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
                <login-form @success="success"></login-form>
            </v-card-text>
        </v-card>
    </v-flex>
</template>
<script>
import { mapGetters } from 'vuex'
import LoginForm from './LoginForm'
import VueCookies from 'vue-cookies'


export default {
    components: {
        LoginForm,
    },
    data: () => ({
        showPin: false,

    }),

    methods: {
        hasToken() {

            const token = VueCookies.get('JXPT')
            if (token !== null) return true

            return false

        },
        async success(data) {

            this.$router.push({ name: 'pin' })

        }
    }
}

</script>
