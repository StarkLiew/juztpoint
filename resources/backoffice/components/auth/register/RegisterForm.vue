<template>
    <v-form ref="form" @submit.prevent="submit" lazy-validation v-model="valid">
        <v-text-field :label="labels.name" v-model="form.name" :error-messages="errors.name" :rules="[rules.required('name')]" :disabled="loading"></v-text-field>
        <v-text-field :label="labels.company" v-model="form.companyname" :error-messages="errors.companyname" :rules="[rules.required('companyname')]" :disabled="loading"></v-text-field>
        <v-text-field :label="labels.email" v-model="form.email" type="email" :error-messages="errors.email" :rules="[rules.required('email')]" :disabled="loading"></v-text-field>
        <v-text-field :label="labels.password" v-model="form.password" :append-icon="passwordHidden ? 'mdi-eye-off' : 'mdi-eye'" @click:append="() => (passwordHidden = !passwordHidden)" :type="passwordHidden ? 'password' : 'text'" :error-messages="errors.password" :disabled="loading" :rules="[rules.required('password')]" hint="At least 6 characters"></v-text-field>
        <v-text-field :label="labels.password_confirmation" v-model="form.password_confirmation" :type="passwordHidden ? 'password' : 'text'" :error-messages="errors.password_confirmation" :disabled="loading" :rules="[rules.required('password_confirmation')]"></v-text-field>
 <div><strong>Change: color / mode (math) / resolve (only digit) </strong></div>

 
        <v-layout row class="mt-4 mx-0">
            <v-spacer></v-spacer>
            <v-btn text :disabled="loading" :to="{ name: 'login' }" color="grey darken-2" exact>
                I already have an account.
                Sign in
            </v-btn>
            <v-btn type="submit" :loading="loading" :disabled="loading || !valid" color="primary" class="ml-4">
                Create account
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
    components: {
   
    },
    data: () => ({
        passwordHidden: true,
        labels: {
            name: 'Name',
            companyname: 'Company name',
            email: 'Email',
            password: 'Password',
            password_confirmation: 'Confirm password'
        },
        form: {
            name: null,
            company: null,
            email: null,
            password: null,
            password_confirmation: null
        }
    }),

    methods: {
        submit() {
            if (this.$refs.form.validate()) {
                this.loading = true
                axios.post(api.path('register'), this.form)
                    .then(res => {
                        this.$toast.success('You have been successfully registered!')
                        this.$emit('success', res.data)
                    })
                    .catch(err => {
                        this.handleErrors(err.response.data.errors)
                    })
                    .then(() => {
                        this.loading = false
                    })
            }
        },
        captchaBtn() {

        }
    }
}

</script>
