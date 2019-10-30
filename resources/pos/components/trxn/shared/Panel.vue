<template>
    <v-navigation-drawer fixed app :permanent="$vuetify.breakpoint.mdAndUp" light :clipped="$vuetify.breakpoint.mdAndUp" :value="show" :right="isEntry">
        <v-container grid-list-sm fluid>
            <v-content style="margin-top: 5px">
                <v-sheet id="scrolling-techniques-7" class="overflow-y-auto" max-height="calc(100vh - 48px)" color="transparent">

                </v-sheet>
                <v-overlay :value="overlay">
                    <v-progress-circular indeterminate size="64"></v-progress-circular>
                </v-overlay>
            </v-content>
        </v-container>
    </v-navigation-drawer>
</template>
<script>
import { mapGetters } from 'vuex'
export default {


    data: () => ({
        show: 'product',
        filter: '',
        back: '',
        isEntry: false,
    }),
    components: {

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
