<template>
    <div>
        <v-container grid-list-sm fluid>
            <v-sheet height="100%" tile>
                <v-layout wrap>
                    <v-flex xs4 sm2 md2 d-flex child-flex v-if="show === 'product' && !filter" @click="swap('service')">
                        <v-card flat tile class="d-flex" color="darken-1">
                            <v-img aspect-ratio="1" v-ripple class="v-btn">
                                <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                        <div class="caption text-wrap">
                                            <v-icon>mdi-wrench</v-icon><br />Service
                                        </div>
                                    </v-flex>
                                </v-layout>
                            </v-img>
                        </v-card>
                    </v-flex>
                    <v-flex xs4 sm2 md2 d-flex child-flex v-if="show === 'service' && !filter" @click="swap('product')">
                        <v-card flat tile class="d-flex" color="green darken-1">
                            <v-img aspect-ratio="1" v-ripple class="v-btn">
                                <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                        <div class="caption text-wrap">
                                            <v-icon>mdi-watch</v-icon><br />Product
                                        </div>
                                    </v-flex>
                                </v-layout>
                            </v-img>
                        </v-card>
                    </v-flex>
                    <v-flex xs4 sm2 md2 d-flex child-flex v-if="show !== 'category' && show !== 'calendar'  && !filter" @click="swap('category', show)">
                        <v-card flat tile class="d-flex" color="cyan darken-1">
                            <v-img aspect-ratio="1" v-ripple class="v-btn">
                                <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                        <div class="caption text-wrap">
                                            <v-icon>mdi-shape</v-icon><br />Category
                                        </div>
                                    </v-flex>
                                </v-layout>
                            </v-img>
                        </v-card>
                    </v-flex>
                    <v-flex v-if="show === 'product' && !filter" v-for="(product, index) in filterProducts(search)" :key="index" xs4 sm2 md2 d-flex child-flex @click="selected(product)" >
                        <v-card flat tile class="d-flex" :color="product.properties.color ? product.properties.color : 'blue'">
                            <v-img v-if="product.thumbnail" :src="product.thumbnail ? product.thumbnail :  ``" aspect-ratio="1" v-ripple class="v-btn">
                                <v-layout pa-2 style="background: rgba(0,0,0,0.5);" column fill-height class="lighttext lightbox white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                        <div class="caption text-wrap">{{ product.name }}</div>
                                    </v-flex>
                                </v-layout>
                            </v-img>
                        </v-card>
                    </v-flex>
                    <v-flex v-if="show === 'service' && !filter" v-for="(service, index) in filterServices(search)" :key="index" xs4 sm2 md2 d-flex child-flex @click="selected(service)">
                        <v-card flat tile class="d-flex" :color="service.properties.color ? service.properties.color : 'blue darken-3'">
                            <v-img v-if="service.thumbnail" :src="service.thumbnail ? service.thumbnail :  ``" aspect-ratio="1" v-ripple class="v-btn">
                                <v-layout pa-2 column fill-height class="lighttext lightbox white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                        <div class="caption text-wrap">{{ service.name  }}</div>
                                    </v-flex>
                                </v-layout>
                            </v-img>
                        </v-card>
                    </v-flex>
                    <v-flex v-if="show === 'category'" xs4 sm2 md2 d-flex child-flex>
                        <v-card flat tile class="d-flex">
                            <v-img aspect-ratio="1" v-ripple class="v-btn cyan" @click="swap(back)">
                                <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                        <v-overlay absolute color="grey darken-3">
                                            <div class="caption text-wrap">
                                                <v-icon>mdi-backspace</v-icon>
                                            </div>
                                        </v-overlay>
                                    </v-flex>
                                </v-layout>
                            </v-img>
                        </v-card>
                    </v-flex>
                    <v-flex v-if="show === 'category' && !filter" v-for="(category, index) in categories" :key="index" xs4 sm2 md2 d-flex child-flex @click="selectFilter(category)">
                        <v-card flat tile class="d-flex">
                            <v-img aspect-ratio="1" v-ripple class="v-btn blue">
                                <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                        <v-overlay absolute color="grey darken-3">
                                            <div class="caption text-wrap">{{ category.name }}</div>
                                        </v-overlay>
                                    </v-flex>
                                </v-layout>
                            </v-img>
                        </v-card>
                    </v-flex>
                    <v-flex v-if="show === 'category' && filter" v-for="(item, index) in filterItems(filter)" :key="index" xs4 sm2 md2 d-flex child-flex @click="selected(item)">
                        <v-card flat tile class="d-flex">
                            <v-img :src="item.thumbnail ? item.thumbnail :  ``" aspect-ratio="1" v-ripple class="v-btn" :class="[item.properties.color ? item.properties.color : 'blue']">
                                <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                                    <v-spacer></v-spacer>
                                    <v-flex shrink>
                                        <v-overlay absolute color="grey darken-3">
                                            <div class="caption text-wrap">{{ item.name }}</div>
                                        </v-overlay>
                                    </v-flex>
                                </v-layout>
                            </v-img>
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
export default {


    data: () => ({
        show: 'service',
        filter: '',
        back: '',
    }),
    components: {
        Calendar: Calendar
    },
    props: ['search'],
    computed: {

        ...mapGetters({
            products: 'product/collection',
            services: 'service/collection',
            categories: 'system/categories'
        })
    },

    methods: {
        swipe(direction) {

        },
        selected(product) {
            const defaultItem = {
                qty: 1,
                price: 0.00,
                discount: 0.0,
                saleBy: null,
            }
            const item = { ...defaultItem, ...product }
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
            console.log(text)
            return this.products.filter(p => p.name.toLowerCase().includes(text.toLowerCase()) || p.sku.toLowerCase().includes(text.toLowerCase()))

        },

        filterServices(text) {
            if (!text) return this.services

            return this.services.filter(p => p.name.toLowerCase().includes(text.toLowerCase()) || p.sku.toLowerCase().includes(text.toLowerCase()))

        }


    }
}

</script>

<style>
   .lighttext{
       background: rgba(0,0,0,0.5);

   }
</style>