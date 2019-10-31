<template>
    <v-layout justify-center top>
        <v-dialog v-model="show" persistent max-width="350">
            <v-card>
                <v-form ref="form" v-model="valid" :lazy-validation="lazy">
                    <v-toolbar flat dark color="primary">
                        <v-btn icon dark @click="cancel()">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>{{item.name}}</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn icon dark text @click="done()" :disabled="!valid">
                            Add
                        </v-btn>
                    </v-toolbar>
                    <v-toolbar flat>
                        <v-btn color="success" large dark @click="inc(1, 'qty')">
                            <v-icon>add</v-icon>
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-toolbar-title @click="showKeyboard = true" class="display-1">{{ item.qty }}</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn color="success" large dark @click="inc(-1, 'qty')">
                            <v-icon>remove</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-toolbar v-if="item.variants" v-for="(variant, vindex) in item.variants" :key="vindex">
                        <v-select :rules="variantRules" required v-model="selectedVariant[variant.name]" :items="variant.value" :label="variant.name" class="mt-6" @change="resetPrice"></v-select>
                    </v-toolbar>
                    <v-toolbar flat>
                        <v-spacer></v-spacer>
                        <v-flex class="subheader">
                            <v-icon>money</v-icon>Price
                        </v-flex>
                        <v-flex class="display-1" @click="showKeyboard = true">
                            {{item.properties.price | currency({fractionCount: 2})}}
                        </v-flex>
                    </v-toolbar>
                    <v-layout>
                        <v-textarea filled auto-grow label="Note" rows="4" row-height="10" v-model="item.note" shaped></v-textarea>
                    </v-layout>
                    <v-layout px-10>
                        <v-combobox v-model="item.saleBy" :items="users" :rules="saleByRules" chips required label="Sale Person">
                            <template v-slot:item="{ index, item }">
                                <v-list-item-content>
                                    <v-chip>
                                        <v-avatar class="accent white--text" left>
                                            {{ item.name.slice(0, 1).toUpperCase() }}
                                        </v-avatar>
                                        {{ item.name }}
                                    </v-chip>
                                </v-list-item-content>
                            </template>
                            <template v-slot:selection="data">
                                <v-chip :key="JSON.stringify(data.item)" v-bind="data.attrs" :input-value="data.selected" :disabled="data.disabled" @click.stop="data.parent.selectedIndex = data.index" @click:close="data.parent.selectItem(data.item)">
                                    <v-avatar class="accent white--text" left>
                                        {{ data.item.name.slice(0, 1).toUpperCase() }}
                                    </v-avatar>
                                    {{ data.item.name }}
                                </v-chip>
                            </template>
                        </v-combobox>
                    </v-layout>
                </v-form>
            </v-card>
        </v-dialog>
        <keyboard @done="showKeyboard = false" @clear="item.properties.price = 0.00" @change="priceChange" @close="closedKeyboard" :decimal="2" :show="showKeyboard">
        </keyboard>
    </v-layout>
</template>
<script>
import { mapGetters } from 'vuex'
import Keyboard from '../../ui/Keyboard'

export default {
    data: () => ({
        showKeyboard: false,
        valid: false,
        selectedVariant: [],
        lazy: false,
        item: {
            name: '',
            saleBy: null,
            qty: 0,
            properties: {
                price: 0,
            }
        },
        variantRules: [
            v => !!v || 'Choice a product variant',
        ],
        saleByRules: [
            v => !!v || 'Sale person is required',
        ],
        value: '0',
        tab: 'tab-1',
        keys: ['1', '2', '3', '4', '5', '6', '7', '8', '9', 'clear', '0', 'done'],
    }),
    components: {
        Keyboard,
    },
    computed: mapGetters({
        users: 'user/users',
        auth: 'auth/user'
    }),
    mounted() {
        this.initialise(this.product)
    },
    props: ['product', 'show'],
    watch: {
        product(val) {
            this.initialise(val)
        },
    },
    methods: {
        initialise(val) {
            this.item = JSON.parse(JSON.stringify(val))
            this.item.price = 0.00
            this.item.saleBy = this.auth
        },
        inc(neg, prop) {

            let val = parseFloat(this.item[prop]) + neg
            if (val > 0) this.item[prop] = val


        },
        done() {

            if (this.$refs.form.validate()) {

                this.$emit('done', this.item)
                this.selectedVariant = []
                this.$refs.form.reset()
                this.cancel()
            }
        },
        resetPrice() {
            this.item.properties.variant = { ...this.selectedVariant }
            const joinedName = this.castVariantString(this.item)

            const stock = this.item.properties.stocks.find(v => v.name === joinedName)

            if(stock !== undefined) {

                this.item.properties.price = stock.price
            } else {
                this.item.properties.price = 0.00
            }
            
        },
        cancel() {
            this.item = {
                name: '',
                saleBy: null,
                qty: 0,
                properties: {
                    price: 0.00,
                }
            }
            this.$emit('close', this.item)
        },
        priceChange(val) {
            this.item.properties.price = val
        },
        closedKeyboard() {

            this.showKeyboard = false
        },

    }
}

</script>
