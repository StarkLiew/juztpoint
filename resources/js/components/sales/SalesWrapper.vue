<template>


   <div class="fill-height">
       <carts 
          @nav-toggle="navToggle"
          @customer-toggle="customerToggle" 
          @item-added="itemAdded"
          @edit-item="editProductToggle"
          :customer="customer" 
          :product.sync="product" 
       > </carts>
          <top-menu :mini="mini"  @nav-toggle="navToggle"></top-menu>
 
        <v-content style="margin-top: 5px">
   
           <v-sheet
              id="scrolling-techniques-7"
              class="overflow-y-auto"
              max-height="calc(100vh - 48px)"
              color="transparent"
            >           
                <products-list @selected="selectedProduct" v-if="panel === 'product'"></products-list>
                <product-edit :item="item" v-if="panel === 'edit-product'" @cancel="cancelProductEdit" @done="doneProductEdit"></product-edit>
                <customers-list @selected="selectedCustomer" v-if="panel === 'customer'"></customers-list>
             </v-sheet>
        </v-content>
    
 </div>

          

</template>

<script>

import TopMenu from './shared/TopMenu'
import Carts from './shared/Carts'
import ProductsList from './shared/ProductsList'
import ProductEdit from './shared/ProductEdit'
import CustomersList from './shared/CustomersList'

export default {
  data: () => ({
    mini: false,
    panel: 'product',
    customer: null,
    product: null,
    item: null,

  }),

  components: {
    TopMenu,
    Carts,
    ProductsList,
    ProductEdit,
    CustomersList,
  },

  methods: {
    navToggle() {
        this.mini = !this.mini
    },
    customerToggle() {
       this.panel = 'customer'
    },
    productToggle() {
       this.panel = 'product'
    },
    editProductToggle(product) {
       this.item = product

       this.panel = 'edit-product'
    },
    itemAdded() {
       this.product = null
    },
    selectedCustomer(customer) {
       this.customer = customer
       this.productToggle()
    },
    selectedProduct(product) {
       this.product = product
    },
    cancelProductEdit() {
       this.productToggle() 
    },
    doneProductEdit(item) {

       this.productToggle() 
    },
  }
}
</script>
