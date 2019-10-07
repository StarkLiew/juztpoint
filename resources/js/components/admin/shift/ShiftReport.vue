<template>
    <v-layout justify-center class="fill-height">
        <v-card class="mx-auto" max-width="500" flat color="#F9F9F9" v-if="auth && auth.properties.role === 'MGR'">
            <v-card-text>
                <v-row align="center">
                    <v-col class="d-flex" cols="12" sm="6">
                        <v-select v-model="shiftId" :items="shifts" :item-text="getDate" item-value="id" label="Shift Report on"></v-select>
                    </v-col>
                    <v-col class="d-flex" cols="12" sm="6">
                        <v-select v-model="staffId" :items="[{id:0, name: 'All Staff'}].concat(users)" item-text="name" item-value="id" label="by"></v-select>
                    </v-col>
                </v-row>
                <v-divider></v-divider>
                <v-sheet class="mx-auto" elevation="8" min-height="60vh" style="padding: 10px">
                    <vue-easy-print tableShow ref="report" v-if="shiftId">
                        <template slot-scope="func">
                            <report :header="{company, store}" :by="by()" :data="computeSummary()" :title="getShiftLabel(shiftId)"></report>
                        </template>
                    </vue-easy-print>
                </v-sheet>
                <v-divider></v-divider>
            </v-card-text>
            <v-btn block color="primary" dark large @click="print()">
                <v-icon>printer</v-icon>Print {{ getShiftLabel(shiftId) }} Report
            </v-btn>
        </v-card>
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
    </v-layout>
</template>
<script>
import { mapGetters } from 'vuex'
import report from "./ReportTemplate"
import vueEasyPrint from 'vue-easy-print'

export default {

    data: () => ({
        overlay: false,
        amount: 0.00,
        dialog: false,
        staffId: 0,
        shiftId: null,
    }),
    computed: mapGetters({
        auth: 'auth/user',
        users: 'user/users',
        shift: 'system/shift',
        last: 'system/lastShift',
        shifts: 'system/shifts',
        company: 'system/company',
        paymentMethod: 'system/paymentMethod',
        store: 'auth/store',
    }),
    mounted() {
        this.shiftId = this.shift ? this.shift.id : this.last.id
    },
    components: {
        vueEasyPrint,
        report,
    },
    methods: {
        print() {
            this.$refs.report.print()
        },
        amountChange(val) {
            this.amount = val

        },
        done() {
            if (this.amount > 0) {
                this.dialog = true
            }

        },
        async openCloseShift() {

            if (this.shift && this.shift.status === 'open') {
                await this.$store.dispatch('system/closeShift', this.amount)
                this.amount = 0.00

            } else {
                await this.$store.dispatch('system/openShift', this.amount)
                this.amount = 0.00
                this.$router.push({ name: 'sales' })
            }
            this.dialog = false
        },
        getDate(item) {
            try {
                if (item.close) {

                    return this.$moment(item.close.date).format('DD/MM/YYYY hh:mmA')
                } else {
                    return this.$moment(item.open.date).format('DD/MM/YYYY hh:mmA')
                }
            } catch (e) {

                return 'undefined'
            }

        },
        by() {
            if (this.staffId === 0) return { id: 0, name: 'All Staff' }
            return this.users.find(u => u.id === this.staffId)
        },
        getShift(id) {
            return this.shifts.find(s => s.id === id)

        },
        getShiftLabel(id) {
            const shift = this.shifts.find(s => s.id === id)
            if (shift) {
                if (shift.status === 'open') return 'X'
                else return 'Z'
            } else {
                return false
            }

        },
        computeSummary() {
            const id = this.shiftId

            let receipts = this.$store.getters['receipt/receipts'].filter(r => r.shiftId === id)
            if (this.staffId > 0) {
                receipts = receipts.filter(r => r.teller.id === this.staffId)
            }

            if (!receipts) return {}
            return {
                shift: this.shifts.find(s => s.id === id),
                count: receipts.length,
                nett: receipts.reduce((acc, curr) => acc + (curr.charge + (-curr.rounding) - (curr.tax_total + curr.service_charge)), 0),
                total_charge: receipts.reduce((acc, curr) => acc + curr.charge, 0),
                gross: receipts.reduce((acc, curr) => acc + ((curr.charge + curr.discount_amount + curr.refund) - (curr.service_charge + curr.tax_total + curr.rounding)), 0),
                refund: receipts.reduce((acc, curr) => acc + curr.refund, 0),
                service_charge: receipts.reduce((acc, curr) => acc + curr.service_charge, 0),
                tax_total: receipts.reduce((acc, curr) => acc + curr.tax_total, 0),
                rounding: receipts.reduce((acc, curr) => acc + curr.rounding, 0),
                footer_discount: receipts.reduce((acc, curr) => acc + curr.discount_amount, 0),
                payment: this.payment(receipts),

            }

        },
        payment(receipts) {
            let p = {}
            let id = 1
            for (const [key, value] of Object.entries(this.paymentMethod)) {

                if (value) {
                    p[key] = parseFloat(receipts.reduce((acc, curr) => acc + curr.payments.filter(p => p.item_id === id).reduce((a, c) => a + c.total_amount, 0), 0))
                } else {
                    p[key] = 0.00
                }
                id++
            }

            return p
        },

        overlayShow(value) {
            this.overlay = value
        },
    }
}

</script>
