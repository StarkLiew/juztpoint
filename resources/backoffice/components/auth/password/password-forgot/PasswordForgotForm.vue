<template>
    <v-form ref="form" @submit.prevent="submit" v-model="valid">
        <v-text-field :label="labels.email" v-model="form.email" type="email" :error-messages="errors.email" :rules="[rules.required('email')]" :disabled="loading"></v-text-field>
        <v-layout class="mt-4 mx-0">
       
            <v-slider v-model="bpm" :loading="loading" :disabled="loading || !valid" tick-size="32" thumb-size="32" label="Slide to sumbit" thumb-label="always" :color="color" track-color="grey" always-dirty min="0" max="100">
            </v-slider>
        </v-layout>
        <v-layout class="mt-4 mx-0">
            <v-btn text :disabled="loading" :to="{ name: 'login', query: {email: form.email} }" color="grey darken-2" exact>
                Back to login
            </v-btn>
        </v-layout>
    </v-form>
</template>
<script>
import axios from 'axios'
import { api } from '~~/config'
import Form from '~~/mixins/form'

export default {
    mixins: [Form],
    computed: {
        color() {
            if (this.bpm < 20) return 'indigo'
            if (this.bpm < 40) return 'teal'
            if (this.bpm < 60) return 'green'
            if (this.bpm < 80) return 'orange'
            return 'red'
        },
    },
    data: () => ({
        bpm: 0,
        form: {
            email: null
        }
    }),
    watch: {
        bpm(val) {
            if (val === 100) {
                this.submit()

            }
        },

    },
    created() {
        this.form.email = this.$route.query.email || null
    },

    methods: {
        submit() {
              
            if (this.$refs.form.validate()) {
                this.loading = true

                axios.post(api.path('forgot'), this.form)
                    .then((res) => {
                        this.$toast.info('An email with password reset instructions has been sent to your email address.')
                        this.$emit('success')
                    })
                    .catch(err => {
                        this.handleErrors(err.response.data.errors)
                    })
                    .then(() => {
                        this.loading = false
                        this.bpm = 0
                    })
            }
        }
    }
}

</script>
