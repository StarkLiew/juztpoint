<template>
    <v-flex sm8 md6 lg4>
        <v-layout justify-center mb-5>
            <h1 class="display-3">{{ title }}</h1>
        </v-layout>
        <v-card v-if="!hasToken()">
            <v-toolbar dark color="primary" flat>
                <v-toolbar-title>Register Terminal</v-toolbar-title>
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
    props: {
        title: { default: 'JuxtPoint' },
    },
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
