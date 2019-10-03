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
            <v-textarea filled auto-grow label="Note" rows="2" v-model="note" row-height="30" shaped></v-textarea>
        </v-layout>
        <v-sheet v-if="item.properties.contain" class="mx-auto" elevation="8" max-width="380">
            <v-slide-group class="pa-4" center-active show-arrows>
                <v-slide-item v-for="(subitem, subindex) in item.properties.contain" :key="subindex" v-slot:default="{ active, toggle }">
                    <v-row class="fill-height" align="center" justify="center">
                        <v-menu offset-y>
                            <template v-slot:activator="{ on }">
                                <v-card>
                                    <p class="caption">{{ getItem(subitem).name }} </p>
                                    <v-btn color="primary overline" dark v-on="on">
                                        {{ servicesBy[subitem] ? servicesBy[subitem].name : "" }}
                                    </v-btn>
                                </v-card>
                            </template>
                            <v-list>
                                <v-list-item @click="changeUserService(subitem, false)">
                                    <v-list-item-title>None</v-list-item-title>
                                </v-list-item>
                                <v-list-item v-for="(user, index) in users" :key="index" @click="changeUserService(subitem, user)">
                                    <v-list-item-title>{{ user.name }}</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </v-row>
                </v-slide-item>
            </v-slide-group>
        </v-sheet>
        <v-layout>
            <v-card tile class="pa-2" outlined>
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
            <v-card tile class="pa-2" outlined>
                <v-combobox v-model="shareWith" :items="users" chips required label="Share with">
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
        shareWith: null,
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
            this.item.discount = { rate: discountRate, type: this.parseDiscountType(discountType) }
            this.item.saleBy = saleBy
            this.item.servicesBy = servicesBy
            this.item.shareWith = shareWith
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

            showKeyboard = false
        },
        getItem(id) {
            return this.$store.getters['service/collection'].find(o => o.id === id)
        },
    }
}

</script>
