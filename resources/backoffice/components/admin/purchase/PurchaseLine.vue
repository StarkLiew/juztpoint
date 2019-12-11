<template>
    <v-card>
        <v-card-title>
            <v-toolbar flat dark color="primary" max-height="68" :disabled="loading || saving">
                <v-btn icon dark @click="close">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>New Item</v-toolbar-title>
                <div class="flex-grow-1"></div>
                <v-toolbar-items>
                    <v-btn dark text @click="save" :disabled="loading || !lineValid" :loading="saving">
                        <v-icon>mdi-check</v-icon>
                        Save
                    </v-btn>
                </v-toolbar-items>
                </v-menu>
            </v-toolbar>
        </v-card-title>
        <v-form ref="lineform" v-model="lineValid">
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="12" sm="12" md="6" lg="6">
                            <v-autocomplete v-model="editedLine.item" :items="products" :loading="loading" hide-selected item-text="name" item-value="id" label="Item" placeholder="Choose" prepend-icon="mdi-package" return-object @input="updateDescription" :rules="[v => !!v || 'Item is required',]" required></v-autocomplete>
                        </v-col>
                        <v-col cols="12" sm="12" md="6" lg="6">
                            <v-text-field label="Description" :rules="[v => !!v || 'Description is required',]" required v-model="editedLine.note"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" sm="6" md="6" lg="2">
                            <v-text-field type="number" label="Qty" v-model="editedLine.qty" :rules="[v => !!v || 'Qty is required',]" required @input="calc"></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="6" lg="3">
                            <v-text-field type="number" label="Price" v-model="editedLine.properties.price" :rules="[v => !!v || 'Price is required',]" required @input="calc"></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="4" md="4" lg="2">
                            <v-text-field type="number" label="Discount" v-model="editedLine.discount.rate" :rules="[v => !!v || 'Discount is required',]" required @input="calc"></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="4" md="4" lg="2">
                            <v-autocomplete v-model="editedLine.tax" :items="taxes" :loading="loading" hide-selected item-text="name" item-value="id" label="Tax" placeholder="Choose" prepend-icon="mdi-curreny-usd" return-object @input="calc" :rules="[v => !!v || 'Tax is required',]" required></v-autocomplete>
                        </v-col>
                        <v-col cols="12" sm="4" md="4" lg="3" class="text-right">
                            <h1 class="display-1">{{editedLine.total_amount | currency}}</h1>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
        </v-form>
    </v-card>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
    data: () => {
        return {
            loading: false,
            taxes: [],
            lineValid: false,
            saving: false,
            editedLine: {
                item: null,
                item_id: -1,
                line: -1,
                type: 'po',
                trxn_id: -1,
                user_id: -1,
                note: '',
                qty: null,
                discount: {
                    rate: 0,
                    type: 'percent',
                    amount: 0,
                },
                discount_amount: 0,
                tax_id: -1,
                tax: null,
                tax_amount: 0,
                total_amount: 0,
                properties: {
                    price: 0,
                }
            },
        }

    },
    watch: {
        line: {
            handler(val) {
               console.log(val)
                if (!!val) {

                    this.editedLine = JSON.parse(JSON.stringify(val))
                }
            },
            deep: true

        },


    },
    computed: {
        ...mapGetters({
            auth: 'auth/user',
            products: 'product/items',
        }),

    },
    props: ['line'],
    async created() {
        if (this.line) {
            console.log(this.line)
            this.editedLine = JSON.parse(JSON.stringify(this.line))
        }
        await this.initialize()

    },
    methods: {
        async initialize() {
            this.loading = true

            this.taxes = await this.$store.dispatch('setting/fetch', { type: 'tax', search: '', limit: 0, page: 1, sort: [], desc: [], noCommit: true })

            await this.$store.dispatch('product/fetch', { type: 'product', search: '', limit: 0, page: 1, sort: [], desc: [], noCommit: false, })

            this.loading = false
        },
        calc() {


            this.editedLine.total_amount = this.editedLine.qty * this.editedLine.properties.price


            this.editedLine.discount_amount = this.editedLine.total_amount * this.editedLine.discount.rate / 100
            this.editedLine.total_amount = this.editedLine.total_amount - this.editedLine.discount_amount
            if (this.editedLine.tax) {
                this.editedLine.tax_amount = this.editedLine.total_amount * this.editedLine.tax.properties.rate / 100
            }

            this.editedLine.total_amount = this.editedLine.total_amount + this.editedLine.tax_amount

        },
        updateDescription() {
            if (!!this.editedLine && !!this.editedLine.item) {
                this.editedLine.note = this.editedLine.item.name
            }
        },
        reset() {

            this.$refs.lineform.reset()
            this.$refs.lineform.resetValidation()
            this.editedLine = {
                item: null,
                item_id: -1,
                line: -1,
                type: 'po',
                trxn_id: -1,
                user_id: -1,
                note: '',
                qty: null,
                discount: {
                    rate: 0,
                    type: 'percent',
                    amount: 0,
                },
                discount_amount: 0,
                tax_id: null,
                tax_amount: 0,
                total_amount: 0,
                properties: {
                    price: 0,
                }
            }
        },
        close() {
            this.reset()
            this.$emit('close')
        },
        async save() {
            if (this.$refs.lineform.validate()) {
                this.saving = true

                this.editedLine.user_id = this.auth.id

                this.$emit('save', { ...this.editedLine })
                this.reset()
                this.saving = false
            }

        }
    }
}

</script>
