<template>
    <v-expansion-panels accordion>
        <v-expansion-panel>
            <v-expansion-panel-header>General</v-expansion-panel-header>
            <v-expansion-panel-content>
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
                    <v-list-item>
                        <v-list-item-avatar>
                            <v-icon>mdi-cellphone-link</v-icon>
                        </v-list-item-avatar>
                        <v-list-item-content>
                            <v-list-item-title>Exit Terminal</v-list-item-title>
                            <v-list-item-subtitle>Deregister this device as terminal. Data will not be lost.</v-list-item-subtitle>
                        </v-list-item-content>
                        <v-list-item-action>
                            <v-dialog v-model="logoutDialogShow" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
                                <template v-slot:activator="{ on }">
                                    <v-btn dark v-on="on">
                                        <v-icon color="red lighten-1">mdi-exit-to-app</v-icon>
                                    </v-btn>
                                </template>
                                <v-card tile>
                                    <v-toolbar flat dark>
                                        <v-toolbar-title>Confirm to exit terminal?</v-toolbar-title>
                                        <v-spacer></v-spacer>
                                        <v-btn @click="logoutDialogShow=false">
                                            <v-icon>mdi-close</v-icon>
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
            </v-expansion-panel-content>
        </v-expansion-panel>
        <v-expansion-panel>
            <v-expansion-panel-header>Printer</v-expansion-panel-header>
            <v-expansion-panel-content>
                <v-list two-line subheader>
                    <v-list-item>
                        {{ getPrinter() }}
                    </v-list-item>
                    <v-divider></v-divider>
                    <v-list-item>
                        <v-list-item-avatar>
                            <v-icon>mdi-printer</v-icon>
                        </v-list-item-avatar>
                        <v-list-item-content>
                            <v-list-item-title>Always print receipt</v-list-item-title>
                        </v-list-item-content>
                        <v-list-item-action>
                            <v-switch></v-switch>
                        </v-list-item-action>
                    </v-list-item>
                </v-list>
            </v-expansion-panel-content>
        </v-expansion-panel>
        <v-expansion-panel>
            <v-expansion-panel-header>Payments</v-expansion-panel-header>
            <v-expansion-panel-content>
                <v-list two-line subheader>
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
                </v-list>
            </v-expansion-panel-content>
        </v-expansion-panel>
    </v-expansion-panels>
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
        scannerAlwayOn: 'system/scannerAlwayOn',
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
        async setScannerAlwayOn() {
            const status = !this.scannerAlwayOn
            await this.$store.dispatch('system/setScannerAlwayOn', { status })

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
                { icon: 'mdi-barcode-scan', iconClass: 'grey lighten-1 white--text', title: 'Barcode', subtitle: 'Barcode Scan Alway On', input: { type: 'switch', model: this.scannerAlwayOn, change: this.setScannerAlwayOn } },

                { icon: 'mdi-wifi-off', iconClass: 'grey lighten-1 white--text', title: 'Offline', subtitle: 'Allow receive cash as payment', input: { type: 'switch', model: this.offline, change: this.setOffline } },
                { icon: 'mdi-refresh', iconClass: 'primary lighten-1 white--text', title: 'Sync', subtitle: 'Sync to Backoffice', input: { type: 'button', click: this.refresh } },
            ]

            this.payments = [
                { icon: 'mdi-currency-usd', iconClass: 'grey lighten-1 white--text', title: 'Cash', subtitle: 'Allow receive debit or credit card as payment', input: { type: 'switch', model: this.payment_method.cash, change: this.setCash } },
                { icon: 'mdi-credit-card', iconClass: 'grey lighten-1 white--text', title: 'Debit or Credit Card', subtitle: 'Allow payment with Debit/Credit card transaction', input: { type: 'switch', model: this.payment_method.card, change: this.setCard } },
                { icon: 'mdi-bank-transfer', iconClass: 'grey lighten-1 white--text', title: 'Transfer', subtitle: 'Allow payment via IBG/GIRO Transfer', input: { type: 'switch', model: this.payment_method.transfer, change: this.setTransfer } },
                { icon: 'mdi-wallet', iconClass: 'grey lighten-1 white--text', title: 'Boost', subtitle: 'Allow use Boost e-Wallet as payment', input: { type: 'switch', model: this.payment_method.boost, change: this.setBoost } },


            ]

        },
        async refresh() {

            this.$emit('overlay', true)

            await setTimeout(async () => {

                this.sync(this.$store)


                this.$emit('overlay', false)
            }, 3000)



        },
        getPrinter() {
            if (!navigator.bluetooth) {
                return 'Bluetooth is offline'
            }

            navigator.bluetooth.requestDevice({
                    filters: [{
                        name: 'BlueTooth Printer',
                        services: ['000018f0-0000-1000-8000-00805f9b34fb']
                    }]
                }, {
                    optionalServices: ['00002af1-0000-1000-8000-00805f9b34fb']
                })
                .then(device => {
                    console.log('device', device)
                    return device
                })
                .catch(this.handleError)
        },
        print() {
            navigator.bluetooth
                .requestDevice({
                    filters: [{
                        name: 'BlueTooth Printer',
                        services: ['000018f0-0000-1000-8000-00805f9b34fb']
                    }]
                }, {
                    optionalServices: ['00002af1-0000-1000-8000-00805f9b34fb']
                })
                .then(device => {
                    console.log('device', device)
                    if (device.gatt.connected) {
                        device.gatt.disconnect()
                    }
                    console.log('connect')
                    return this.connect(device)
                })
                .catch(this.handleError)
        },
        connect(device) {
            const self = this
            device.addEventListener('gattserverdisconnected', this.onDisconnected)
            return device.gatt
                .connect()
                .then(server =>
                    server.getPrimaryService('000018f0-0000-1000-8000-00805f9b34fb')
                )
                .then(service =>
                    service.getCharacteristic('00002af1-0000-1000-8000-00805f9b34fb')
                )
                .then(characteristic => {
                    console.log('characteristic', characteristic)
                    self.printCharacteristic = characteristic
                    self.sendTextData(device)
                })
                .catch(error => {
                    this.handleError(error, device)
                })
        },
        handleError(error, device) {
            console.error('handleError => error', error)
            if (device != null) {
                device.gatt.disconnect()
            }
            let erro = JSON.stringify({
                code: error.code,
                message: error.message,
                name: error.name
            })
            console.log('handleError => erro', erro)
            if (error.code !== 8) {
                this.$q.notify('Could not connect with the printer. Try it again')
            }
        },
        getBytes(text) {
            console.log('text', text)
            let br = '\u000A\u000D'
            text = text === undefined ? br : text
            let replaced = this.$languages.replace(text)
            console.log('replaced', replaced)
            let bytes = new TextEncoder('utf-8').encode(replaced + br)
            console.log('bytes', bytes)
            return bytes
        },
        addText(arrayText) {
            let text = this.msg
            arrayText.push(text)
            if (this.isMobile) {
                while (text.length >= 20) {
                    let text2 = text.substring(20)
                    arrayText.push(text2)
                    text = text2
                }
            }
        },
        sendTextData(device) {
            let arrayText = []
            this.addText(arrayText)
            console.log('sendTextData => arrayText', arrayText)
            this.loop(0, arrayText, device)
        },
        loop(i, arrayText, device) {
            let arrayBytes = this.getBytes(arrayText[i])
            this.write(device, arrayBytes, () => {
                i++
                if (i < arrayText.length) {
                    this.loop(i, arrayText, device)
                } else {
                    let arrayBytes = this.getBytes()
                    this.write(device, arrayBytes, () => {
                        device.gatt.disconnect()
                    })
                }
            })
        },
        write(device, array, callback) {
            this.printCharacteristic
                .writeValue(array)
                .then(() => {
                    console.log('Printed Array: ' + array.length)
                    setTimeout(() => {
                        if (callback) {
                            callback()
                        }
                    }, 250)
                })
                .catch(error => {
                    this.handleError(error, device)
                })
        }
    }
}

</script>
