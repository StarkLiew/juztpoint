<template>


   <div class="fill-height">
       <carts 
          @cart-toggle="cartToggle"
          @customer-toggle="showCustomerDialog = true"
          @customer-remove="customer = null"
          @item-added="itemAdded"
          @edit-item="editProductToggle"
          :show="showCart"
          :customer="customer" 
          :product.sync="product" 
       > </carts>
          <top-menu @overlay="overlayShow" @cart-toggle="cartToggle"></top-menu>
 
        <v-content style="margin-top: 5px">
   
           <v-sheet
              id="scrolling-techniques-7"
              class="overflow-y-auto"
              max-height="calc(100vh - 48px)"
              color="transparent"
            >           
                <products-list @selected="selectedProduct" v-if="panel === 'product'"></products-list>
                <item-add  @close="showEdit = false"  v-if="item" :item="item" :show="showEdit" @done="addedProduct"></item-add>
                <customers-list @close="showCustomerDialog = false" @selected="selectedCustomer" :show="showCustomerDialog"></customers-list>




             </v-sheet>
         
         <v-overlay :value="overlay" >
              <v-progress-circular indeterminate size="64"></v-progress-circular>
          </v-overlay>

        </v-content>


    
 </div>

          

</template>

<script>

import TopMenu from './shared/TopMenu'
import Carts from './shared/Carts'
import ProductsList from './shared/ProductsList'
import ItemAdd from './shared/ItemAdd'
import CustomersList from './shared/CustomersList'


export default {
  data: () => ({
    panel: 'product',
    customer: null,
    product: null,
    showEdit: false,
    showCart: false,
    item: null,
    overlay: false,
    showCustomerDialog: false,

  }),

  components: {
    TopMenu,
    Carts,
    ProductsList,
    ItemAdd,
    CustomersList,


  },

  methods: {
    cartToggle() {
        this.showCart = !this.showCart
    },
    customerToggle() {
       this.panel = 'customer'
    },
    productToggle() {
       this.panel = 'product'
    },
    overlayShow(show) {
      this.overlay = show
    },
    editProductToggle(product) {
       this.item = product

       this.panel = 'edit-product'
    },
    itemAdded() {
       this.product = null
    },
    editProductToggle() {
       this.showCart = !this.showCart
    },
    selectedCustomer(customer) {
       this.customer = customer
       this.showCustomerDialog = false
    },
    selectedProduct(item) {
       this.showEdit = true
       this.item = item
    },
    addedProduct(item) {
       this.showEdit = false
       this.product = item
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
