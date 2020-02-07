<template>
    <v-flex sm8 md6 lg4>
        <v-layout justify-center mb-5>
            <h1 class="display-3">NoahCall</h1>
        </v-layout>

    </v-flex>
</template>
<script>
import { mapGetters } from 'vuex'
import VueCookies from 'vue-cookies'


export default {
    components: {
 
    },
    data: () => ({
        showPin: false,
    }),
    props: {
        title: { default: 'JuztPoint' },
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
