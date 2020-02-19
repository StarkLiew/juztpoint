<template>
    <div>
        <v-container grid-list-sm fluid>
            <v-sheet height="100%" tile>
                <v-layout wrap>
                    <v-flex xs4 sm2 md2 d-flex child-flex v-if="show !== 'appointments' && !!scannerAlwayOn">
                        <v-card flat tile color="white">
                            <v-img aspect-ratio="1">
                                <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                        <StreamBarcodeReader @decode="onDecode" @error="onError"></StreamBarcodeReader>
                                    </v-flex>
                                </v-layout>
                            </v-img>
                        </v-card>
                    </v-flex>
                    <v-flex xs4 sm2 md2 d-flex child-flex v-if="show !== 'appointments'" @click="swap('appointments', 'service')" v-ripple>
                        <v-card flat tile color="pink darken-1">
                            <v-img aspect-ratio="1">
                                <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                        <div class="caption text-wrap">
                                            <v-icon>mdi-calendar</v-icon>
                                        </div>
                                    </v-flex>
                                </v-layout>
                            </v-img>
                            <v-card-text class="caption text-wrap text-center">
                                Appointment
                            </v-card-text>
                        </v-card>
                    </v-flex>
                    <v-expansion-panels v-if="show === 'appointments'">
                        <v-expansion-panel>
                            <v-expansion-panel-header>{{ $moment(selectedDate).format('ddd, MMM D YYYY') }}</v-expansion-panel-header>
                            <v-expansion-panel-content class="text-center">
                                <v-date-picker v-model="selectedDate" @input="getAppointments()" no-title></v-date-picker>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-expansion-panels>
                    <v-flex xs4 sm2 md2 d-flex child-flex v-if="show === 'product' && !filter" @click="swap('service')" v-ripple>
                        <v-card flat tile color="purple darken-1">
                            <v-img aspect-ratio="1">
                                <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                        <div class="caption text-wrap">
                                            <v-icon>mdi-wrench</v-icon>
                                        </div>
                                    </v-flex>
                                </v-layout>
                            </v-img>
                            <v-card-text class="caption text-wrap text-center">
                                Service
                            </v-card-text>
                        </v-card>
                    </v-flex>
                    <v-flex xs4 sm2 md2 d-flex child-flex v-if="show === 'service' && !filter" @click="swap('product')" v-ripple>
                        <v-card flat tile color="green darken-1">
                            <v-img aspect-ratio="1">
                                <v-layout pa-2 column fill-height class="white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                        <div class="caption text-wrap">
                                            <v-icon>mdi-watch</v-icon>
                                        </div>
                                    </v-flex>
                                </v-layout>
                            </v-img>
                            <v-card-text class="caption text-wrap text-center">
                                Product
                            </v-card-text>
                        </v-card>
                    </v-flex>
                    <v-flex xs4 sm2 md2 d-flex child-flex v-if="show !== 'category' && show !== 'appointments'  && !filter" @click="swap('category', show)" v-ripple>
                        <v-card flat tile color="cyan darken-1">
                            <v-img aspect-ratio="1">
                                <v-layout pa-2 column fill-height class="white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                        <div class="caption text-wrap">
                                            <v-icon>mdi-shape</v-icon>
                                        </div>
                                    </v-flex>
                                </v-layout>
                            </v-img>
                            <v-card-text class="caption text-wrap text-center">
                                Category
                            </v-card-text>
                        </v-card>
                    </v-flex>
                    <v-flex v-if="show === 'product' && !filter" v-for="(product, index) in filterProducts(search)" :key="index" xs4 sm2 md2 d-flex child-flex @click="selected(product)" v-ripple>
                        <v-card flat tile :color="product.properties.color ? product.properties.color : 'blue'">
                            <v-img :src="product.thumbnail ? product.thumbnail :  ``" aspect-ratio="1">
                                <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                    </v-flex>
                                </v-layout>
                            </v-img>
                            <v-card-text class="caption text-wrap text-center">
                                {{ product.name }}
                            </v-card-text>
                        </v-card>
                    </v-flex>
                    <v-flex v-if="show === 'service' && !filter" v-for="(service, index) in filterServices(search)" :key="index" xs4 sm2 md2 d-flex child-flex @click="selected(service)" v-ripple>
                        <v-card flat tile :color="service.properties.color ? service.properties.color : 'blue darken-3'">
                            <v-img :src="service.thumbnail ? service.thumbnail :  ``" aspect-ratio="1">
                                <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                    </v-flex>
                                </v-layout>
                            </v-img>
                            <v-card-text class="caption text-wrap text-center">
                                {{ service.name }}
                            </v-card-text>
                        </v-card>
                    </v-flex>
                    <v-flex v-if="show === 'category' || show === 'appointments'" xs4 sm2 md2 d-flex child-flex v-ripple @click="swap(back)">
                        <v-card flat tile class="cyan darken-3">
                            <v-img aspect-ratio="1">
                                <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                        <div class="caption text-wrap">
                                            <v-icon>mdi-backspace</v-icon>
                                        </div>
                                    </v-flex>
                                </v-layout>
                            </v-img>
                            <v-card-text class="caption text-wrap text-center">
                                Back
                            </v-card-text>
                        </v-card>
                    </v-flex>
                    <v-flex v-if="show === 'category' && !filter" v-for="(category, index) in categories" :key="index" xs4 sm2 md2 d-flex child-flex @click="selectFilter(category)" v-ripple>
                        <v-card flat tile color="blue darken-3">
                            <v-img aspect-ratio="1" class="blue">
                                <v-layout pa-2 column fill-height class=" lightbox white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                    </v-flex>
                                </v-layout>
                            </v-img>
                            <v-card-text class="caption text-wrap text-center">
                                {{ category.name }}
                            </v-card-text>
                        </v-card>
                    </v-flex>
                    <v-flex v-if="show === 'category' && filter" v-for="(item, index) in filterItems(filter)" :key="index" xs4 sm2 md2 d-flex child-flex @click="selected(item)" v-ripple>
                        <v-card flat tile :color="item.properties.color ? item.properties.color : 'blue darken-3'">
                            <v-img :src="item.thumbnail ? item.thumbnail :  ``" aspect-ratio="1">
                                <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                    </v-flex>
                                </v-layout>
                            </v-img>
                            <v-card-text class="caption text-wrap text-center">
                                {{ item.name }}
                            </v-card-text>
                        </v-card>
                    </v-flex>
                    <v-flex v-if="show === 'appointments'" v-for="(item, index) in appointments" :key="index" xs4 sm2 md2 d-flex child-flex @click="selected(item)" v-ripple>
                        <v-card flat tile :color="'green darken-3'">
                            <v-img :src="item.thumbnail ? item.thumbnail :  ``" aspect-ratio="1">
                                <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                        <div class="caption text-wrap">
                                            <v-icon>mdi-account</v-icon><br />
                                            <div class="caption text-wrap text-center">{{
                                                $moment( item.properties.startDateTime ).format('HH:mm')
                                                }}</div>
                                        </div>
                                    </v-flex>
                                </v-layout>
                            </v-img>
                            <v-card-text class="caption text-wrap text-center">
                                {{ item.account.name }}
                            </v-card-text>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-sheet>
            <calendar v-if="show === 'calendar'" @close="swap('product')"></calendar>
        </v-container>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'
import Calendar from './Calendar'
import { StreamBarcodeReader } from "vue-barcode-reader"
export default {


    data: () => ({
        show: 'service',
        filter: '',
        back: '',
        selectedDate: '',
        appointments: [],
    }),
    components: {
        Calendar: Calendar,
        StreamBarcodeReader: StreamBarcodeReader,
    },
    props: ['search'],
    computed: {
        ...mapGetters({
            products: 'product/collection',
            services: 'service/collection',
            categories: 'system/categories',
            store: 'auth/store',
            scannerAlwayOn: 'system/scannerAlwayOn',
        })
    },
    async created() {
        this.selectedDate = this.$moment().format('YYYY-MM-DD').toString()
        await this.getAppointments()

    },
    methods: {
        async getAppointments() {
            const filter = {
                dates: [this.selectedDate, this.selectedDate],
                store: this.store.id,
            }

            const fields = `id, status, store{id, name, properties{timezone, currency}}, account_id, terminal_id, store_id, shift_id, reference, account{id, name}, terminal{id, name}, store{id, name}, transact_by, teller{id, name}, date, type, discount{type, rate, amount}, discount_amount,  tax_amount, service_charge, charge, rounding, received, change, properties{startDateTime, endDateTime,color}, note,refund, items{id, line, item_id, item{id, name, sku, properties{duration}}, user_id, note, name, discount{type, rate, amount}, discount_amount, tax{id, name, properties{rate, code}}, tax_id, tax_amount, qty, refund_qty, receives{id, store_id, store{id, name}, user{id, name}, qty, properties{do, date}}, refund_amount, total_amount, amount, saleBy{id, name}, shareWith{id, name}, composites{id, name, performBy{id, name}}, properties{price, duration}}, payments{id, name, line, item_id, total_amount},properties{name}, note`



            const result = await this.$store.dispatch('appointment/fetchAppointments', { name: 'DataAppointment', fields, filter, limit: 0, page: 1, sort: [], desc: [] })

            this.appointments = result.data.data

        },
        selected(product) {
            const defaultItem = {
                qty: 1,
                price: 0.00,
                discount: 0.0,
                saleBy: null,
            }

            const parsedProduct = JSON.parse(JSON.stringify(product))
            const item = { ...defaultItem, ...parsedProduct }
            this.$emit('selected', item)
        },
        swap(panel, back = '') {

            this.show = panel
            this.back = back
            this.filter = null
            if (panel === 'calendar') this.$emit('calendar', true)
            else this.$emit('calendar', false)
        },
        selectFilter(category) {
            this.filter = category
        },
        filterItems(selected) {

            if (this.back === 'product' && selected) {

                const filters = this.products.filter(p => p.category.id === selected.id)

                return filters
            }
            if (this.back === 'service' && selected) {
                return this.services.filter(p => p.category.id === selected.id)
            }
            return []
        },
        filterProducts(text) {
            if (!text) return this.products

            return this.products.filter(p => p.name.toLowerCase().includes(text.toLowerCase()) || p.sku.toLowerCase().includes(text.toLowerCase()))

        },

        filterServices(text) {
            if (!text) return this.services

            return this.services.filter(p => p.name.toLowerCase().includes(text.toLowerCase()) || p.sku.toLowerCase().includes(text.toLowerCase()))

        },
        onDecode(result) {
            if (result) {
                this.$emit('scanned', result)
                this.scanDialog = false
            }
        },
        onError() {

            this.scanDialog = false

        },


    }
}

</script>
<style>
.lighttext {
    background: rgba(0, 0, 0, 0.5);

}

</style>
