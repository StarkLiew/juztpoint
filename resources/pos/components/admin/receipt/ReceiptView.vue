<template>
    <div id="edit-receipt-content">
        <v-container v-if="value"  style="max-height: calc(100vh - 56px - 56px)" class="overflow-y-auto">
          
                <v-card>
                    <v-card-text class="text-center">
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
                                    <item-edit :refund="true" :item="item" :show="editItem[index]" :index="index" @done="editedItem" @cancel="editItem = []"></item-edit>
                                </v-menu>
                                <v-list-item-content id="receipt-content">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <div>
                                                <v-list-item-title v-on="on">{{item.name}}</v-list-item-title>
                                                <span class="caption" v-if="item.properties.variant">
                                                    {{ castVariantString(item) }}
                                                </span>
                                                <span class="caption" v-if="item.saleBy">
                                                    <v-avatar class="accent white--text" left size="16">
                                                        {{ item.saleBy.name.slice(0, 1).toUpperCase() }}
                                                    </v-avatar>
                                                    {{item.saleBy.name}}
                                                </span>
                                                <span class="caption" v-if="item.shareWith"> &amp;
                                                    <v-avatar class="accent white--text" left size="16">
                                                        {{ item.shareWith.name.slice(0, 1).toUpperCase() }}
                                                    </v-avatar>
                                                    {{item.shareWith.name}}
                                                </span>
                                                <div v-if="item.composites" v-for="(composite, index) in item.composites" :key="index">
                                                    <v-chip pill v-if="'performBy' in composite && composite.performBy.id !== 0" x-small>
                                                        <strong> {{ composite.name.toUpperCase() }}</strong>&nbsp;
                                                        <span>({{ composite.performBy.name }})</span>
                                                    </v-chip>
                                                </div>
                                            </div>
                                        </template>
                                        <span>{{item.note}}</span>
                                    </v-tooltip>
                                </v-list-item-content>
                                <v-btn icon>{{item.amount | currency}}</v-btn>
                            </v-list-item>
                            <v-divider></v-divider>
                        </div>
                        <v-footer v-if="value" flat dense padless>
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
                        </v-footer>
                    </v-card-text>
                    <vue-easy-print tableShow style="display: none" ref="receipt">
                        <template slot-scope="func">
                            <receipt v-model="value" :header="{company, store}"></receipt>
                        </template>
                    </vue-easy-print>
                </v-card>
    
            <v-bottom-navigation horizontal bottom absolute>
                <v-btn value="recent" @click="print()">
                    <span>Reprint</span>
                    <v-icon>mdi-printer</v-icon>
                </v-btn>
                <v-dialog v-model="sendDialog" persistent max-width="600px">
                    <template v-slot:activator="{ on }">
                        <v-btn value="favorites" v-on="on">
                            <span>Resend</span>
                            <v-icon>
                                mdi-email
                            </v-icon>
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="headline">
                                <v-icon color="green darken-1">mdi-email</v-icon>Send Receipt
                            </span>
                            <v-spacer></v-spacer>
                            <v-btn fab light small top right @click="closeDialog">
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-card-text>
                            <v-container>
                                <v-form ref="form" @submit.prevent="sendEmail()" lazy-validation v-model="valid">
                                    <v-row>
                                        <v-text-field label="Recipient email" v-model="form.email" :value="value.customer ? value.customer.properties.email : ''" type="email" :error-messages="errors.email" :rules="[rules.required('email')]" :disabled="loading"></v-text-field>
                                    </v-row>
                                    <v-layout row class="mt-4 mx-0">
                                        <v-spacer></v-spacer>
                                        <v-btn type="submit" :loading="loading" :disabled="loading || !valid" color="primary" class="ml-4">
                                            Send
                                        </v-btn>
                                    </v-layout>
                                </v-form>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="refundDialog" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
                    <template v-slot:activator="{ on }">
                        <v-btn dark v-on="on" :disabled="refundDisabled">
                            <span>Refund</span>
                            <v-icon>mdi-currency-usd-off</v-icon>
                        </v-btn>
                    </template>
                    <v-card tile>
                        <v-toolbar flat dark>
                            <v-toolbar-title>Confirm refund to customer?</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-btn @click="refundDialog=false">
                                <v-icon>mdi-close</v-icon>
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
                            <v-icon>mdi-close-circle</v-icon>
                        </v-btn>
                    </template>
                    <v-card tile>
                        <v-toolbar flat dark>
                            <v-toolbar-title>Confirm to void this receipt?</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-btn @click="voidDialog=false">
                                <v-icon>mdi-close</v-icon>
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
        </v-container>
        <v-card v-if="!value">
        </v-card>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'
import ItemQty from '../../sales/shared/ItemQty'
import ItemEdit from '../../sales/shared/ItemEdit'
import vueEasyPrint from 'vue-easy-print'
import receipt from "../../sales/shared/ReceiptTemplate"
import pin from "../../auth/login/Pin"
import Carts from "../../sales/shared/Carts"
import axios from 'axios'
import { api } from '~~/config'
import Form from '~~/mixins/form'


export default {
    mixins: [Form],
    data: () => ({
        editItem: [],
        voidDialog: false,
        refundDisabled: true,
        refundDialog: false,
        sendDialog: false,
        value: null,
        loading: false,
        form: {
            email: '',
            name: '',
            ref: '',
        }
    }),
    components: {
        vueEasyPrint,
        receipt,
        ItemEdit,
        pin,
        ItemQty,
        Carts,
    },
    props: ['selected'],

    watch: {
        selected: {
            handler: function(newValue) {
                this.value = JSON.parse(JSON.stringify(newValue))
                this.refundDisabled = true
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

            /* if (this.selected.charge !== this.value.charge) this.refundDisabled = false
             else this.refundDisabled = true */

            if (JSON.stringify(this.selected) !== JSON.stringify(this.value)) this.refundDisabled = false
            else this.refundDisabled = true

            this.editItem = []

        },
        async proceedVoid() {
            await this.$store.dispatch('receipt/voidReceipt', this.value)
            this.voidDialog = false
        },
        closeDialog() {
            this.sendDialog = false

            this.form.email = ''

        },

        async sendEmail() {

            if (this.$refs.form.validate()) {

                this.loading = true

                const item = this.value

                const form = {
                    id: this.value.id,
                    to: this.form.email,
                    name: this.value.customer ? this.value.customer.name : 'Valuable customer',
                    data: { item, company: this.company, store: this.store }
                }

                await axios.post(api.path('receipt'), form)
                    .then(res => {
                        this.$toast.success('You have been successfully registered!')
                        this.$emit('success')
                    })
                    .catch(err => {
                        this.handleErrors(err.response.data.errors)
                    })
                    .then(() => {
                        this.loading = false
                    })
            }
        },
    },
}

</script>
<style>
#scroll-receipt-content {
    height: calc(100vh - (500 + (48 * 7)));
}

#receipt-footer {
    min-height: calc(100vh - (48 * 7));
    max-height: calc(100vh - (48 * 7));
}

</style>
