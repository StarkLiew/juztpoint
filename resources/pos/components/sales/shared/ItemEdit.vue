<template>
    <v-card>
        <v-toolbar flat dark color="primary">
            <v-btn icon dark @click="cancel()">
                <v-icon>close</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
            <v-toolbar-title>{{item.name}}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon dark text @click="done()">
                Done
            </v-btn>
        </v-toolbar>
        <v-toolbar flat>
            <v-btn color="success" large dark @click="inc(1, 'qty')">
                <v-icon>add</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
            <v-toolbar-title class="display-1">{{ qty }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn color="success" large dark @click="inc(-1, 'qty')">
                <v-icon>remove</v-icon>
            </v-btn>
        </v-toolbar>
        <v-divider></v-divider>
        <v-toolbar flat>
            <v-spacer></v-spacer>
            <v-flex class="subheader">
                <v-icon>label</v-icon>Discount
            </v-flex>
            <v-flex class="display-1" @click="showKeyboard = true">
                {{discountRate | currency({fractionCount: decimal})}}
            </v-flex>
            <v-btn-toggle v-model="discountType" @change="(val) => { decimal = val + 1 }">
                <v-btn large text>
                    %
                </v-btn>
                <v-btn large text>
                    $
                </v-btn>
            </v-btn-toggle>
        </v-toolbar>
        <v-layout>
            <v-textarea filled auto-grow label="Note" rows="2" v-model="note" row-height="10" shaped></v-textarea>
        </v-layout>
            <v-toolbar v-if="item.composites" v-for="(composite, index) in item.composites" :key="index">
                <v-select return-object v-model="composite.performBy" :items="[{id:0, name: 'None'}].concat(users)" :label="composite.name" class="mt-6" item-value="id" item-text="name"></v-select>
            </v-toolbar>
        <v-layout>
            <v-card tile class="pa-2">
                <v-combobox v-model="saleBy" :items="users" :rules="saleByRules" chips required label="Sale Person">
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
            </v-card>

            <v-card v-if="item.allow_assistant === 1" tile class="pa-2">
                <v-combobox v-model="shareWith" :items="[{id:0, name: 'None'}].concat(users)" chips required label="Share with">
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
            </v-card>
        </v-layout>
        <keyboard @done="showKeyboard = false" @clear="discountRate = 0.0" @change="discountRateChange" @close="closedKeyboard" :decimal="decimal" :show="showKeyboard">
        </keyboard>
    </v-card>
</template>
<script>
import Keyboard from '../../ui/Keyboard'
import { mapGetters } from 'vuex'

export default {
    data: () => ({
        valid: false,
        lazy: false,
        discountFixed: false,
        value: '0',
        qty: 1,
        note: '',
        saleByRules: [
            v => !!v || 'Sale person is required',
        ],
        showKeyboard: false,
        discountRate: 0.0,
        shareWith: { id: 0, name: 'none' },
        saleBy: null,
        servicesBy: [],
        decimal: 1,
        discountType: 0,
        tab: 'tab-1',
    }),
    components: {
        Keyboard,
    },
    computed: mapGetters({
        users: 'user/users',
    }),
    props: ['item', 'index', 'show'],
    mounted() {
        this.update()
    },
    watch: {
        show: {
            handler(val) {
                this.update()
            },
            deep: true
        }
    },
    methods: {
        update() {
            this.qty = this.item.qty
            if (this.item.discount) {
                this.discountType = this.parseDiscountType(this.item.discount.discountType)
                this.discountRate = this.item.discount.rate
            }
            if (this.item.note) {
                this.note = this.item.note
            }
            if (this.item.saleBy) {
                this.saleBy = this.item.saleBy
            }
            if (this.item.shareWith) {
                this.shareWith = this.item.shareWith
            }
            if (this.item.servicesBy) {
                this.servicesBy = this.item.servicesBy
            }

        },
        changeUserService(subitem, user) {
            if (!user) this.servicesBy.splice(subitem, 1)
            else this.servicesBy[subitem] = user

        },
        inc(neg, prop) {
            let val = parseFloat(this.qty) + neg
            if (val > 0) this.qty = val
        },
        done() {
            const { qty, discountRate, discountType, note, servicesBy, shareWith, saleBy } = this
            this.item.qty = qty
            this.item.note = note
            this.item.discount = { rate: parseFloat(discountRate), type: this.parseDiscountType(discountType) }
            this.item.saleBy = saleBy
            this.item.servicesBy = servicesBy
            if (shareWith.id === 0) {
                this.item.shareWith = null
            } else {
                this.item.shareWith = shareWith
            }

            this.$emit('done', this.item, this.index)
        },
        cancel() {
            this.$emit('cancel')
        },
        discountRateChange(val) {
            this.discountRate = val
        },
        parseDiscountType(discountType) {
            if (discountType === 0) {
                return 'percent'
            }

            if (discountType === 1) {
                return 'fix'
            }

            if (discountType === 'fix') {
                return 1
            }

            return 0
        },
        closedKeyboard() {

            this.showKeyboard = false
        },
        getItem(id) {
            return this.$store.getters['service/collection'].find(o => o.id === id)
        },
    }
}

</script>
