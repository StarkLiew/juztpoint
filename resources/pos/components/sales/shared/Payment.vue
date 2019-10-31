<template>
    <div>
        <v-container v-if="!paid">
            <v-toolbar>
                <v-btn icon @click="back()">
                    <v-icon>arrow_back</v-icon>
                </v-btn>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                </v-toolbar-items>
            </v-toolbar>
            <v-card class="mx-auto" tile>
                <v-card-title class="display-4 ">
                    <v-spacer></v-spacer>
                    <div>{{ rounding(trxn.footer.charge) | currency}}
                    </div>
                    <v-spacer></v-spacer>
                </v-card-title>
                <v-list shaped>
                    <v-list-item-group color="primary" large>
                        <v-divider></v-divider>
                        <v-list-item @click="editCash()" v-if="payementMethod.cash">
                            <v-list-item-icon>
                                <v-icon>attach_money</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>Cash</v-list-item-title>
                            </v-list-item-content>
                            <span>{{ cash.amount | currency }}</span>
                        </v-list-item>
                        <v-divider></v-divider>
                        <v-list-item @click="editCard()" v-if="payementMethod.card">
                            <v-list-item-icon>
                                <v-icon>credit_card</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>Card</v-list-item-title>
                            </v-list-item-content>
                            <span>{{ card.amount | currency }}</span>
                        </v-list-item>
                        <v-divider></v-divider>
                        <v-list-item @click="editTransfer()" v-if="payementMethod.transfer">
                            <v-list-item-icon>
                                <v-icon>account_balance</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>Transfer</v-list-item-title>
                            </v-list-item-content>
                            <span>{{ transfer.amount | currency }}</span>
                        </v-list-item>
                        <v-divider></v-divider>
                        <v-list-item @click="editBoost()" v-if="payementMethod.boost">
                            <v-list-item-icon>
                                <v-icon>account_balance_wallet</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>Boost</v-list-item-title>
                            </v-list-item-content>
                            <span>{{ boost.amount | currency }}</span>
                        </v-list-item>
                    </v-list-item-group>
                </v-list>
                <v-list>
                    <v-list-item>
                        <v-dialog ref="dialog" v-model="modalDateTime" persistent width="500">
                            <template v-slot:activator="{ on }">
                                <v-btn v-if="false" dark :disabled="!trxn.customer" large fab color="green" v-on="on">
                                    <v-icon>schedule</v-icon>
                                </v-btn>
                            </template>
                            <v-stepper v-model="appStep" vertical>
                                <v-stepper-step :complete="appStep > 1" step="1">
                                    Date
                                    <small>Set Appointment Date</small>
                                </v-stepper-step>
                                <v-stepper-items>
                                    <v-stepper-content step="1">
                                        <v-row justify="center">
                                            <v-date-picker v-model="appDate" :min="$moment().format('YYYY-MM-DD')">
                                                <div class="flex-grow-1"></div>
                                                <v-btn text color="primary" @click="cancelAppointment()">Cancel</v-btn>
                                                <v-btn text color="primary" :disabled="!appDate" @click="appStep = 2">Next</v-btn>
                                            </v-date-picker>
                                        </v-row>
                                    </v-stepper-content>
                                    <v-stepper-step :complete="appStep > 2" step="2">
                                        Time
                                        <small>Set Appointment Time</small>
                                    </v-stepper-step>
                                    <v-stepper-content step="2">
                                        <v-row justify="center">
                                            <v-time-picker v-model="appTime">
                                                <div class="flex-grow-1"></div>
                                                <v-btn text color="primary" @click="cancelAppointment()">Cancel</v-btn>
                                                <v-btn text color="primary" :disabled="!appTime" @click="appStep = 3">Next</v-btn>
                                            </v-time-picker>
                                        </v-row>
                                    </v-stepper-content>
                                    <v-stepper-step step="3">
                                        Confirm?
                                        <small>Confirm Appointment</small>
                                    </v-stepper-step>
                                    <v-stepper-content step="3">
                                        <v-row justify="center">
                                            <v-card class="mx-auto">
                                                <v-list-item two-line>
                                                    <v-list-item-content>
                                                        <v-list-item-title class="headline">
                                                            Customer {{!trxn.customer ? '' : trxn.customer.name}}
                                                        </v-list-item-title>
                                                        <v-list-item-title class="headline"> on {{appDate + ' ' + appTime + ':00'| moment('timezone', store.properties.timezone.replace(/\\/g, ''), 'dddd, D/M/YYYY') }}</v-list-item-title>
                                                        <v-list-item-subtitle class="headline">
                                                            at {{appDate + ' ' + appTime + ':00'| moment('timezone', store.properties.timezone.replace(/\\/g, ''), 'h:mmA') }} - {{ estEndTime() | moment('timezone', store.properties.timezone.replace(/\\/g, ''), 'h:mmA') }}
                                                        </v-list-item-subtitle>
                                                    </v-list-item-content>
                                                </v-list-item two-line>
                                                <v-card-actions>
                                                    <v-spacer></v-spacer>
                                                    <v-btn large color="primary" @click="save('appointment')">Confirm</v-btn>
                                                    <v-btn large @click="cancelAppointment()">Cancel</v-btn>
                                                </v-card-actions>
                                            </v-card>
                                        </v-row>
                                    </v-stepper-content>
                                </v-stepper-items>
                            </v-stepper>
                        </v-dialog>
                        <v-spacer></v-spacer>
                        <v-list-item-content class="title">
                            Received {{ parseFloat(this.cash.amount) + parseFloat(this.card.amount) + parseFloat(this.transfer.amount) + parseFloat(this.boost.amount) | currency}}
                        </v-list-item-content>
                        <v-btn dark large fab color="teal" @click="save()" :disabled="valid()">
                            <v-icon>arrow_forward</v-icon>
                        </v-btn>
                    </v-list-item>
                </v-list>
            </v-card>
        </v-container>
        <v-container v-if="paid">
            <v-toolbar>
                <v-spacer></v-spacer>
                <span> Thank You </span>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                </v-toolbar-items>
            </v-toolbar>
            <v-card class="mx-auto" tile>
                <v-list>
                    <v-list-item>
                        <v-list-item-content class="title">
                            Charge
                        </v-list-item-content>
                        <span>
                            {{ rounding(parseFloat(trxn.footer.charge)) | currency}}
                        </span>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-content class="title">
                            Received
                        </v-list-item-content>
                        <span>
                            {{ parseFloat(cash.amount) + parseFloat(card.amount) | currency}}
                        </span>
                    </v-list-item>
                    <v-divider></v-divider>
                    <v-list-item>
                        <v-list-item-content class="title">
                            Change
                        </v-list-item-content>
                        <span class="display-3" color="error">{{ parseFloat(this.cash.amount) + parseFloat(this.card.amount) + parseFloat(this.transfer.amount) + parseFloat(this.boost.amount) - rounding(trxn.footer.charge) | currency}}
                        </span>
                    </v-list-item>
                </v-list>
            </v-card>
        </v-container>
        <v-container v-if="paid">
            <v-card class="mx-auto" tile>
                <v-list>
                    <v-list-item>
                        <v-list-item-content class="title">
                            Print
                        </v-list-item-content>
                        <v-btn @click="print()">
                            <v-icon>print</v-icon>
                        </v-btn>
                    </v-list-item>
                    <v-divider></v-divider>
                    <v-list-item>
                        <v-list-item-content>
                            <v-text-field label="Email" prepend-inner-icon="email"></v-text-field>
                        </v-list-item-content>
                        <v-btn>
                            <v-icon>send</v-icon>
                        </v-btn>
                    </v-list-item>
                </v-list>
                <vue-easy-print tableShow style="display: none" ref="easyPrint">
                    <template slot-scope="func">
                        <receipt v-model="receipt" :header="{company, store}"></receipt>
                    </template>
                </vue-easy-print>
            </v-card>
        </v-container>
        <v-container v-if="paid">
            <v-btn rounded block large color="primary" @click="done()">Done</v-btn>
        </v-container>
        <keyboard @done="showKeyboard = false" @clear="clear" @change="change" @close="" :decimal="2" :show="showKeyboard">
        </keyboard>
        <v-overlay :value="showKeyboard" opacity="0">
        </v-overlay>
        <v-overlay :value="inprogress">
            <v-progress-circular :size="70" :width="7" color="amber" indeterminate></v-progress-circular>
         </v-overlay>   
    </div>
</template>
</v-overlay>
</div>
</template>
<script>
import { mapGetters } from 'vuex'
import Keyboard from '../../ui/Keyboard'
import vueEasyPrint from 'vue-easy-print'
import receipt from "./ReceiptTemplate"

export default {


    data: () => ({
        showKeyboard: false,
        cash: { amount: 0.00 },
        card: { amount: 0.00, ref: '' },
        boost: { amount: 0.00, ref: '' },
        transfer: { amount: 0.00, ref: '' },
        target: null,
        paid: false,
        inprogress: false,
        receipt: null,
        appDate: null,
        appTime: null,
        endEvent: null,
        appStep: 1,
        modalDateTime: false,

    }),
    components: {
        Keyboard,
        vueEasyPrint,
        receipt,
    },
    props: ['trxn'],
    computed: mapGetters({
        auth: 'auth/user',
        company: 'system/company',
        store: 'auth/store',
        shift: 'system/shift',
        offline: 'system/offline',
        terminal: 'auth/terminal',
        payementMethod: 'system/paymentMethod',
    }),
    methods: {
        back() {
            this.$emit('back')
        },

        change(val) {
            this.target.amount = val
        },
        clear() {
            this.target.amount = 0
        },
        editCash() {
            this.showKeyboard = true
            this.target = this.cash
        },
        editCard() {
            this.showKeyboard = true
            this.target = this.card
        },
        editTransfer() {
            this.showKeyboard = true
            this.target = this.transfer
        },
        editBoost() {
            this.showKeyboard = true
            this.target = this.boost
        },

        done() {
            /* this.target = null
            this.paid = false
            this.receipt = null */

            this.$emit('done')

        },
        valid() {

            return (parseFloat(this.cash.amount) + parseFloat(this.card.amount) + parseFloat(this.transfer.amount) + parseFloat(this.boost.amount)) < parseFloat(this.trxn.footer.charge)

        },
        rounding(amount) {

            return Math.round(parseFloat(amount) * 20) / 20


        },
        estEndTime() {

            const now = this.appDate + ' ' + this.appTime + ':00'

            let durations = 0
            for (let item of this.trxn.items) {

                if (item.type === 'service') {

                    durations += item.properties.duration
                }
            }
            return new Date((new Date(now)).getTime() + durations * 60000)

        },
        async save(type = 'receipt') {
            this.inprogress = true
            const amount_received = parseFloat(this.cash.amount) + parseFloat(this.card.amount) + parseFloat(this.transfer.amount) + parseFloat(this.boost.amount)
            const rounded = this.rounding(this.trxn.footer.charge)
            const amount_change = amount_received - rounded

            const { customer, footer, items } = this.trxn
            let payments = []

            if (this.cash.amount > 0) {
                payments.push({ item_id: 1, name: 'Cash', total_amount: this.cash.amount, note: '' })
            }
            if (this.card.amount > 0) {
                payments.push({ item_id: 2, name: 'Card', note: this.card.ref, total_amount: this.card.amount })
            }
            if (this.transfer.amount > 0) {
                payments.push({ item_id: 3, name: 'Transfer', note: this.card.ref, total_amount: this.transfer.amount })
            }
            if (this.boost.amount > 0) {
                payments.push({ item_id: 4, name: 'Boost', note: this.card.ref, total_amount: this.boost.amount })
            }

            const dateObj = new Date()
            let month = dateObj.getUTCMonth() + 1 //months from 1-12
            let day = dateObj.getUTCDate()
            let year = dateObj.getUTCFullYear()
            let hours = dateObj.getUTCHours()
            let minutes = dateObj.getUTCMinutes()
            let seconds = dateObj.getUTCSeconds()

            let user_id = this.auth.id


            let cast_user_id = ("0" + user_id)
            cast_user_id = cast_user_id.substr(cast_user_id.length - 2)
            day = ("0" + day)
            day = day.substr(day.length - 2)
            month = ("0" + month)
            month = month.substr(month.length - 2)

            const sqlYear = year
            year = year.toString().substr(year.toString().length - 2)

            hours = ("0" + hours)
            hours = hours.substr(hours.length - 2)
            minutes = ("0" + minutes)
            minutes = minutes.substr(minutes.length - 2)
            seconds = ("0" + seconds)
            seconds = seconds.substr(seconds.length - 2)

            let now = `${sqlYear}-${month}-${day} ${hours}:${minutes}:${seconds}`
            let end = ''

            if (type === 'appointment') {
                now = this.appDate + ' ' + this.appTime + ':00'
                end = this.estEndTime()

            }

    
            const reference = cast_user_id + year + month + day + hours + minutes + seconds

            let receipt = {
                account_id: customer ? customer.uid : '',
                terminal_id: this.terminal.id,
                customer: customer ? customer : null,
                shiftId: this.shift ? this.shift.id : '',
                date: now,
                type: type,
                reference: reference,
                teller: this.auth,
                discount: { rate: footer.discount.rate, type: footer.discount.type },
                discount_amount: footer.discount.amount,
                tax_total: footer.tax,
                service_charge: 0,
                charge: rounded,
                received: amount_received,
                change: amount_change,
                rounding: rounded - footer.charge,
                note: "",
                refund: 0,
                items: items,
                payments: payments,
                properties: {
                    start: type === 'appointment' ? now : '',
                    end: type === 'appointment' ? this.$moment(end).format('YYYY-MM-DD HH:mm') : '',
                }

            }

            if (type === 'appointment') {
                // check any appointment clash

                const bStart = new Date(now)
                const bEnd = new Date(end)
                const current = new Date()

                if (bStart.getTime() <= current.getTime()) {
                    this.$toast.success('Invalid appointment time')
                    return
                }

                const appointments = this.$store.getters['receipt/appointments']

                const results = appointments.filter((any) => {
                    const aStart = new Date(any.properties.start)
                    const aEnd = new Date(any.properties.end)

                    if (bStart.getTime() >= aStart.getTime() && bStart.getTime() <= aEnd.getTime()) {
                        return true

                    }

                    if (bEnd.getTime() >= aStart.getTime() && bEnd.getTime() <= aEnd.getTime()) {

                        return true
                    }

                    return false
                })

                if (results.length > 0) {
                    this.$toast.success('Time crash with another appointment')
                    return
                }
            }


            this.receipt = await this.$store.dispatch('receipt/addReceipt', receipt)

            if (!this.offline && this.receipt.status === 'offline') {
                await this.$store.dispatch('system/setOffline', { status: true })
                this.$toast.success('Connection fail, transaction is saved offline. Get Administrator help to reestablish online mode.')
            } else {
                this.$toast.success('Transaction is saved!')
            }

            if (type === 'appointment') return this.done()
            this.paid = true
            this.inprogress = false

        },
        saveAppointment() {
            this.done()

        },

        cancelAppointment() {
            this.appDate = null
            this.appTime = null
            this.appStep = 1
            this.modalDateTime = false
        },
        print() {
            this.$refs.easyPrint.print()
        },

    }
}

</script>
