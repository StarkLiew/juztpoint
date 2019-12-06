<template>
    <v-card>
        <v-card-title>
            <v-toolbar flat dark color="primary" max-height="68" :disabled="loading">
                <v-btn icon dark @click="close">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>New Item</v-toolbar-title>
                <div class="flex-grow-1"></div>
                <v-toolbar-items>
                    <v-btn dark text @click="save" :disabled="!valid" :loading="saving">
                        <v-icon>mdi-check</v-icon>
                        Save
                    </v-btn>
                </v-toolbar-items>
                </v-menu>
            </v-toolbar>
        </v-card-title>
        <v-card-text>
            <v-container>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-row>
                        <v-col cols="12" sm="12" md="6" lg="6">
                            <v-autocomplete v-model="editedLine.item" :items="products" :loading="loading" hide-selected item-text="name" item-value="id" label="Item" placeholder="Choose" prepend-icon="mdi-package" :rules="[v => !!v || 'Item is required',]" required return-object @input="updateDescription"></v-autocomplete>
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
                            <v-text-field type="number" label="Price" v-model="editedLine.price" :rules="[v => !!v || 'Price is required',]" required @input="calc"></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="4" md="4" lg="2">
                            <v-text-field type="number" label="Discount" v-model="editedLine.discount_rate" :rules="[v => !!v || 'Discount is required',]" required @input="calc"></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="4" md="4" lg="2">
                            <v-autocomplete v-model="editedLine.tax" :items="taxes" :loading="loading" hide-selected item-text="name" item-value="id" label="Tax" placeholder="Choose" prepend-icon="mdi-curreny-usd" return-object @input="calc" :rules="[v => !!v || 'Tax is required',]" required></v-autocomplete>
                        </v-col>
                        <v-col cols="12" sm="4" md="4" lg="3" class="text-right">
                            <h1 class="display-1">{{editedLine.total_amount | currency}}</h1>
                        </v-col>
                    </v-row>
                    {{ line }}
                </v-form>
            </v-container>
        </v-card-text>
    </v-card>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
    data: () => {
        return {
            loading: false,
            taxes: [],
            valid: false,
            saving: false,
            editedLine: {
                item: null,
                item_id: -1,
                line: 1,
                type: 'po',
                trxn_id: '',
                item: {},
                note: '',
                qty: null,
                price: null,
                discount_rate: null,
                discount_amount: 0,
                tax: null,
                tax_amount: 0,
                total_amount: 0,
            },
        }

    },
    watch: {
        line: {
            handler(val) {
                this.editedLine = JSON.parse(JSON.stringify(val))
                console.log(this.editedLine)
            },
            deep: true
        }
    },
    computed: {
        ...mapGetters({
            products: 'product/items',
        }),

    },
    props: ['line'],
    async mounted() {
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

            this.editedLine.total_amount = this.editedLine.qty * this.editedLine.price
            this.editedLine.discount_amount = this.editedLine.total_amount * this.editedLine.discount_rate / 100
            this.editedLine.total_amount = this.editedLine.total_amount - this.editedLine.discount_amount
            if (this.editedLine.tax) {
                this.editedLine.tax_amount = this.editedLine.total_amount * this.editedLine.tax.properties.rate / 100
            }

            this.editedLine.total_amount = this.editedLine.total_amount + this.editedLine.tax_amount

        },
        updateDescription() {
            this.editedLine.note = this.editedLine.item.name
        },
        reset() {
            this.editedLine = {
                item: null,
                item_id: -1,
                line: 1,
                type: 'po',
                trxn_id: '',
                item: {},
                note: '',
                qty: null,
                price: null,
                discount_rate: null,
                discount_amount: 0,
                tax: null,
                tax_amount: 0,
                total_amount: 0,
            }
        },
        close() {
            this.reset()
            this.$emit('close')
        },
        async save() {
            this.saving = true
            this.$emit('save', this.editedLine)
            this.reset()
            this.saving = false
        }
    }
}

</script>
