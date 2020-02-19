<template>
    <v-form ref="form" @submit.prevent="submit" lazy-validation v-model="valid">
        <v-text-field :label="labels.email" v-model="form.email" type="email" :error-messages="errors.email" :rules="[rules.required('email')]" :disabled="loading" prepend-icon="mdi-account"></v-text-field>
        <v-text-field :label="labels.password" v-model="form.password" :append-icon="passwordHidden ? 'mdi-eye-off' : 'mdi-eye'" @click:append="() => (passwordHidden = !passwordHidden)" :type="passwordHidden ? 'password' : 'text'" :error-messages="errors.password" :disabled="loading" :rules="[rules.required('password')]" prepend-icon="mdi-lock"></v-text-field>
        <v-text-field :label="labels.device_id" v-model="form.device_id" @click:append="() => (scannerShow = !scannerShow)" :error-messages="errors.device_id" :disabled="loading" :rules="[rules.required('device_id')]" :append-icon="scannerShow ? 'mdi-camera-off' : 'mdi-qrcode-scan'" prepend-icon="mdi-qrcode"></v-text-field>
        <v-dialog v-model="scannerShow" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-toolbar-title>Scan Terminal ID</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon dark @click="closeScanner">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card-text>
                    <StreamBarcodeReader v-if="!!scannerShow" @decode="onDecode" @error="onError"></StreamBarcodeReader>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-layout class="mt-4 mx-0">
            <v-spacer></v-spacer>
            <v-btn type="submit" :loading="loading" :disabled="loading || !valid" color="primary" class="ml-4">
                Login
            </v-btn>
        </v-layout>
    </v-form>
</template>
<script>
import axios from 'axios'
import { api } from '~/config'
import Form from '~/mixins/form'
import { StreamBarcodeReader } from "vue-barcode-reader"

export default {
    mixins: [Form],
    components: {
        StreamBarcodeReader,
    },
    data: () => ({
        passwordHidden: true,
        scannerShow: false,
        device_idHidden: true,
        form: {
            email: null,
            password: null,
            device_id: null,
            fingerprint: null,
        }
    }),

    created() {
        this.form.email = this.$route.query.email || null
    },

    methods: {
        repaint(location, ctx) {
            const {
                topLeftCorner,
                topRightCorner,
                bottomLeftCorner,
                bottomRightCorner,
                // topLeftFinderPattern,
                // topRightFinderPattern,
                // bottomLeftFinderPattern
            } = location

            ctx.strokeStyle = 'red' // instead of red

            ctx.beginPath()
            ctx.moveTo(topLeftCorner.x, topLeftCorner.y)
            ctx.lineTo(bottomLeftCorner.x, bottomLeftCorner.y)
            ctx.lineTo(bottomRightCorner.x, bottomRightCorner.y)
            ctx.lineTo(topRightCorner.x, topRightCorner.y)
            ctx.lineTo(topLeftCorner.x, topLeftCorner.y)
            ctx.closePath()

            ctx.stroke()
        },
        async submit() {
            if (this.$refs.form.validate()) {
                this.loading = true



                //Collect fingerprint
                this.form.fingerprint = JSON.stringify(await this.scanFingerprint())

                await axios.post(api.path('login'), this.form)
                    .then(async res => {

                        const data = res.data

                        await this.$store.dispatch('auth/saveToken', data)
                        // await this.$store.dispatch('auth/setUser', data)

                        /* Fetch latest data */
                        this.sync(this.$store)

                        this.$toast.success('Welcome back!')
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
        onDecode(decodedString) {
            this.form.device_id = decodedString
            this.scannerShow = false
        },
        onError() {
   
        },
        closeScanner() {
            this.scannerShow = false
        },
        scanFingerprint() {
            return new Promise((resolve, reject) => {
                setTimeout(function() {
                    Fingerprint2.get(function(components) {
                        resolve(components)
                    })
                }, 500)
            });

        }

    }
}

</script>
