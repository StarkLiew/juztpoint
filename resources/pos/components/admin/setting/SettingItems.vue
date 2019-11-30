<template>
    <div>
        <v-list two-line subheader>
            <v-subheader inset>System</v-subheader>
            <v-list-item v-for="item in items" :key="item.title">
                <v-list-item-avatar>
                    <v-icon :class="[item.iconClass]" v-text="item.icon"></v-icon>
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title v-text="item.title"></v-list-item-title>
                    <v-list-item-subtitle v-text="item.subtitle"></v-list-item-subtitle>
                </v-list-item-content>
                <v-list-item-action>
                    <v-btn v-if="item.input.type === 'button'" @click="item.input.click">
                        <v-icon color="grey lighten-1">{{ item.icon }}</v-icon>
                    </v-btn>
                    <v-switch v-if="item.input.type === 'switch'" v-model="item.input.model" @change="item.input.change"></v-switch>
                </v-list-item-action>
            </v-list-item>
            <v-divider inset></v-divider>
            <v-subheader inset>Payment Option</v-subheader>
            <v-list-item v-for="item in payments" :key="item.title">
                <v-list-item-avatar>
                    <v-icon :class="[item.iconClass]" v-text="item.icon"></v-icon>
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title v-text="item.title"></v-list-item-title>
                    <v-list-item-subtitle v-text="item.subtitle"></v-list-item-subtitle>
                </v-list-item-content>
                <v-list-item-action>
                    <v-btn v-if="item.input.type === 'button'" @click="item.input.click">
                        <v-icon color="grey lighten-1">{{ item.icon }}</v-icon>
                    </v-btn>
                    <v-switch v-if="item.input.type === 'switch'" v-model="item.input.model" @change="item.input.change"></v-switch>
                </v-list-item-action>
            </v-list-item>
            <v-divider inset></v-divider>
            <v-list-item>
                <v-list-item-avatar>
                    <v-icon>phonelink_off</v-icon>
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title>Exit Terminal</v-list-item-title>
                    <v-list-item-subtitle>Deregister this device as terminal. Data will not be lost.</v-list-item-subtitle>
                </v-list-item-content>
                <v-list-item-action>
                    <v-dialog v-model="logoutDialogShow" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
                        <template v-slot:activator="{ on }">
                            <v-btn dark v-on="on">
                                <v-icon color="red lighten-1">exit_to_app</v-icon>
                            </v-btn>
                        </template>
                        <v-card tile>
                            <v-toolbar flat dark>
                                <v-toolbar-title>Confirm to exit terminal?</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-btn @click="logoutDialogShow=false">
                                    <v-icon>close</v-icon>
                                </v-btn>
                            </v-toolbar>
                            <v-card-text class="text-center">
                                <h2>Manager Pin require to proceed.</h2>
                                <v-layout justify-center>
                                    <pin title="" :supervisor="true" @verified="logout"></pin>
                                </v-layout>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                </v-list-item-action>
            </v-list-item>
        </v-list>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'
import pin from "../../auth/login/Pin"

export default {
    data: () => ({
        items: [],
        payments: [],
        logoutDialogShow: false,

    }),
    props: [],
    components: {
        pin,
    },
    mounted() {
        this.settings()
    },
    computed: mapGetters({
        offline: 'system/offline',
        payment_method: 'system/paymentMethod',
        shift: 'system/shift',
    }),
    watch: {


    },
    methods: {
        async setOffline() {
            const status = !this.offline
            await this.$store.dispatch('system/setOffline', { status })
            if (!status) {
                this.refresh()
            }

        },
        async setPaymentMethod(option) {
            return await this.$store.dispatch('system/setPaymentMethod', { option })
        },
        async setCash(value) {
            await this.setPaymentMethod({ name: 'cash', value })
        },
        async setCard(value) {
            await this.setPaymentMethod({ name: 'card', value })
        },

        async setTransfer(value) {
            await this.setPaymentMethod({ name: 'transfer', value })
        },
        async setBoost(value) {
            await this.setPaymentMethod({ name: 'boost', value })
        },
        async logout() {

            if (this.shift && this.shift.status === 'open') {
                this.$toast.error('Deregister Terminal fail due to Shift is Open.')
                this.logoutDialogShow = false
                return

            }
            await this.$store.dispatch('auth/deregister')
            this.$router.push({ name: 'login' })

            this.logoutDialogShow = false
        },

        settings() {
            this.items = [
                { icon: 'wifi_off', iconClass: 'grey lighten-1 white--text', title: 'Offline', subtitle: 'Allow receive cash as payment', input: { type: 'switch', model: this.offline, change: this.setOffline } },
                { icon: 'refresh', iconClass: 'primary lighten-1 white--text', title: 'Sync', subtitle: 'Sync to Backoffice', input: { type: 'button', click: this.refresh } },
            ]

            this.payments = [
                { icon: 'attach_money', iconClass: 'grey lighten-1 white--text', title: 'Cash', subtitle: 'Allow receive debit or credit card as payment', input: { type: 'switch', model: this.payment_method.cash, change: this.setCash } },
                { icon: 'credit_card', iconClass: 'grey lighten-1 white--text', title: 'Debit or Credit Card', subtitle: 'Allow payment with Debit/Credit card transaction', input: { type: 'switch', model: this.payment_method.card, change: this.setCard } },
                { icon: 'account_balance', iconClass: 'grey lighten-1 white--text', title: 'Transfer', subtitle: 'Allow payment via IBG/GIRO Transfer', input: { type: 'switch', model: this.payment_method.transfer, change: this.setTransfer } },
                { icon: 'account_balance_wallet', iconClass: 'grey lighten-1 white--text', title: 'Boost', subtitle: 'Allow use Boost e-Wallet as payment', input: { type: 'switch', model: this.payment_method.boost, change: this.setBoost } },


            ]

        },
        async refresh() {

            this.$emit('overlay', true)

            await setTimeout(async () => {

                this.sync(this.$store)


                this.$emit('overlay', false)
            }, 3000)



        },
    }
}

</script>
