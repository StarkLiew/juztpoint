<template>
    <v-layout justify-center class="fill-height">
        <v-card class="mx-auto" flat color="#F9F9F9" width="50%">
            <v-card-text>
                <v-list-item three-line>
                    <v-list-item-content>
                        <v-list-item-title class="headline">{{ shift && shift.status === 'open' ? 'Close' : 'Open New' }} Shift</v-list-item-title>
                        <v-list-item-subtitle wrap v-if="last && last.status === 'close'">Last Shift closed on {{ last.close.date }}</v-list-item-subtitle>
                         <v-list-item-subtitle wrap v-if="last && last.status === 'close'">by {{ last.close.user.name }}</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-divider></v-divider>
                <v-row align="center">
                    <v-col class="display-3" align="center">
                        {{ amount | currency }}
                    </v-col>
                </v-row>
                <v-divider></v-divider>
                <numeric-key @clear="amount = 0.0" @done="done" @change="amountChange" :decimal="2" :show="true">
                </numeric-key>
                <v-row v-if="shift && shift.status === 'open'">
                    <v-col align="center">
                        <v-btn large>
                            <v-icon>printer</v-icon>X Report
                        </v-btn>
                        <v-btn large>
                            <v-icon>printer</v-icon>Z Report
                        </v-btn>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
        <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
            <v-card tile>
                <v-toolbar flat dark>
                    <v-toolbar-title>Confirm to Open new Shift?</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn @click="dialog=false">
                        <v-icon>close</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card-text class="text-center">
                    <h2>Supervisor Pin require to proceed.</h2>
                    <v-layout justify-center>
                        <pin title="" :supervisor="true" @verified="openCloseShift"></pin>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
    </v-layout>
</template>
<script>
import { mapGetters } from 'vuex'
import NumericKey from '../../ui/NumericKey'
import pin from "../../auth/login/Pin"

export default {

    data: () => ({
        overlay: false,
        amount: 0.00,
        dialog: false,
    }),
    computed: mapGetters({
        shift: 'system/shift',
        last: 'system/lastShift',
    }),
    components: {
        NumericKey,
        pin,
    },
    methods: {
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
            }
            this.dialog = false
        },
        overlayShow(value) {
            this.overlay = value
        },
    }
}

</script>
