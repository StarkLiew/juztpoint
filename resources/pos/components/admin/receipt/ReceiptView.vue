<template>
    <div id="edit-receipt-content">
        <v-card v-if="value">
            <v-sheet id="scroll-receipt-content" class="overflow-y-auto">
                <v-container class="text-center">
                    <h1 class="display-2">{{value.charge | currency}}</h1>
                    <p class="caption">Reference: {{ value.reference }}</p>
                    <p class="caption" v-if="value.teller">Teller: {{ value.teller.name }}</p>
                    <p class="subtitle" v-if="value.customer">{{ value.customer.name }}</p>
                    <v-divider></v-divider>
                    <div v-for="(item, index) in value.items" :key="index">
                        <v-list-item two-line>
                            <v-menu absolute v-model="editItem[index]" :close-on-content-click="false" :close-on-click="false" :min-width="380" :nudge-left="30" offset-x right>
                                <template v-slot:activator="{ on }">
                                    <v-btn @click="editItem = []" icon v-on="on">{{item.qty}}</v-btn>
                                </template>
                                <item-qty :item="item" :show="editItem[index]" :index="index" @done="editedItem" @cancel="editItem = []"></item-qty>
                            </v-menu>
                            <v-list-item-content>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <div>
                                            <v-list-item-title v-on="on">{{item.name}}</v-list-item-title>
                                            <span class="caption" v-if="item.saleBy">
                                                <v-avatar class="accent white--text" left size="16">
                                                    {{ item.saleBy.name.slice(0, 1).toUpperCase() }}
                                                </v-avatar>
                                                {{item.saleBy.name}}
                                            </span>
                                        </div>
                                    </template>
                                    <span>{{item.note}}</span>
                                </v-tooltip>
                            </v-list-item-content>
                            <v-btn icon>{{item.amount | currency}}</v-btn>
                        </v-list-item>
                        <v-divider></v-divider>
                    </div>
                </v-container>
                <vue-easy-print tableShow style="display: none" ref="receipt">
                    <template slot-scope="func">
                        <receipt v-model="value" :header="{company, store}"></receipt>
                    </template>
                </vue-easy-print>
            </v-sheet>
        </v-card>
        <v-footer v-if="value" flat dense padless>
            <v-sheet width="100%" id="receipt-footer" class="overflow-y-auto">
                <v-list-item one-line>
                    <v-list-item-content>
                        <v-list-item-title>Discount</v-list-item-title>
                    </v-list-item-content>
                    <v-btn icon>{{ value.discount_amount | currency}}</v-btn>
                </v-list-item>
                <v-list-item one-line>
                    <v-list-item-content>
                        <v-list-item-title>Service</v-list-item-title>
                    </v-list-item-content>
                    <v-btn icon>{{ value.service_charge | currency}}</v-btn>
                </v-list-item>
                <v-list-item one-line>
                    <v-list-item-content>
                        <v-list-item-title>Tax</v-list-item-title>
                    </v-list-item-content>
                    <v-btn icon>{{ value.tax_total | currency}}</v-btn>
                </v-list-item>
                <v-list-item one-line>
                    <v-list-item-content>
                        <v-list-item-title>Charge</v-list-item-title>
                    </v-list-item-content>
                    <v-btn icon class="caption">{{ value.charge | currency}}</v-btn>
                </v-list-item>
                <v-list-item one-line v-for="(payment, index) in value.payments" :key="index">
                    <v-list-item-content>
                        <v-list-item-title>Received {{ payment.name }}</v-list-item-title>
                    </v-list-item-content>
                    <v-btn icon class="caption">{{ payment.total_amount | currency}}</v-btn>
                </v-list-item>
                <v-list-item one-line>
                    <v-list-item-content>
                        <v-list-item-title>Refund</v-list-item-title>
                    </v-list-item-content>
                    <v-btn icon class="caption">{{ value.refund | currency}}</v-btn>
                </v-list-item>
            </v-sheet>
            <v-bottom-navigation horizontal>
                <v-btn value="recent" @click="print()">
                    <span>Reprint</span>
                    <v-icon>print</v-icon>
                </v-btn>
                <v-btn value="favorites">
                    <span>Resend</span>
                    <v-icon>email</v-icon>
                </v-btn>
                <v-dialog v-model="refundDialog" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
                    <template v-slot:activator="{ on }">
                        <v-btn dark v-on="on" :disabled="refundDisabled">
                            <span>Refund</span>
                            <v-icon>money_off</v-icon>
                        </v-btn>
                    </template>
                    <v-card tile>
                        <v-toolbar flat dark>
                            <v-toolbar-title>Confirm refund to customer?</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-btn @click="refundDialog=false">
                                <v-icon>close</v-icon>
                            </v-btn>
                        </v-toolbar>
                        <v-card-text class="text-center">
                            <h2>Manager Pin require to proceed.</h2>
                            <v-layout justify-center>
                                <pin title="" :supervisor="true" @verified="refund"></pin>
                            </v-layout>
                        </v-card-text>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="voidDialog" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
                    <template v-slot:activator="{ on }">
                        <v-btn dark v-on="on">
                            <span>Void</span>
                            <v-icon>cancel</v-icon>
                        </v-btn>
                    </template>
                    <v-card tile>
                        <v-toolbar flat dark>
                            <v-toolbar-title>Confirm to void this receipt?</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-btn @click="voidDialog=false">
                                <v-icon>close</v-icon>
                            </v-btn>
                        </v-toolbar>
                        <v-card-text class="text-center">
                            <h2>Manager Pin require to proceed.</h2>
                            WARNING: You cannot undo once the receipt is void.
                            <v-layout justify-center>
                                <pin title="" :supervisor="true" @verified="proceedVoid"></pin>
                            </v-layout>
                        </v-card-text>
                    </v-card>
                </v-dialog>
            </v-bottom-navigation>
        </v-footer>
        <v-card v-if="!value">
        </v-card>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'
import ItemQty from '../../sales/shared/ItemQty'
import vueEasyPrint from 'vue-easy-print'
import receipt from "../../sales/shared/ReceiptTemplate"
import pin from "../../auth/login/Pin"
import Carts from "../../sales/shared/Carts"

export default {

    data: () => ({
        editItem: [],
        voidDialog: false,
        refundDisabled: true,
        refundDialog: false,
        value: null,
    }),
    components: {
        vueEasyPrint,
        receipt,
        pin,
        ItemQty,
        Carts,
    },
    props: ['selected'],

    watch: {
        selected: {
            handler: function(newValue) {
                this.value = JSON.parse(JSON.stringify(newValue));
            },
            deep: true
        }

    },
    computed: mapGetters({
        company: 'system/company',
        store: 'auth/store',
    }),
    methods: {
        print() {
            this.$refs.receipt.print()
        },
        async refund() {
            await this.$store.dispatch('receipt/refundReceipt', this.value)
            this.refundDialog = false

        },
        editedItem(item, index) {
            const original = this.selected.items[index]
            item = this.sumAmount(item)
            item.refund = { qty: original.qty - item.qty, amount: original.amount - item.amount }

            this.value.items[index] = item
            this.sumTotal(this.value)
   
            if (this.selected.charge !== this.value.charge) this.refundDisabled = false
            else this.refundDisabled = true

        },
        async proceedVoid() {
            await this.$store.dispatch('receipt/voidReceipt', this.value)
            this.voidDialog = false
        }
    },
}

</script>
<style>
#edit-receipt-content {
      min-height: calc(100vh - 54px);
}

#scroll-receipt-content {
    min-height: calc(100vh - 54px - (54px + (56px * 7)));
    max-height: calc(100vh - 54px - (54px + (56px * 7)));
}

#receipt-footer {
    min-height: calc(100vh - (54px + (56px * 6)));
    max-height: calc(100vh - (54px + (56px * 6)));
}

</style>
