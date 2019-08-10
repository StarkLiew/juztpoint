<template>

 <v-navigation-drawer fixed app :permanent="$vuetify.breakpoint.mdAndUp" light :mini-variant.sync="$vuetify.breakpoint.mdAndUp && mini" :clipped="$vuetify.breakpoint.mdAndUp" :value="mini" :width="350" right>

      <template v-slot:prepend>
       <v-toolbar dark  dense flat color="secondary">
           <v-btn icon><v-icon>shopping_cart</v-icon></v-btn>
           <v-toolbar-title class="white--text">{{count}}</v-toolbar-title>   
           <v-spacer></v-spacer>
          <v-btn icon @click="enableRemoveItem()"><v-icon>delete</v-icon></v-btn>
        </v-toolbar>

        <v-list-item two-line>
        <v-btn icon @click="customerToggle()"><v-icon>person_add</v-icon></v-btn>
          <v-list-item-content>
            <v-list-item-title v-if="!customer">Add Customer</v-list-item-title>
            <v-list-item-title v-if="customer">{{ customer.name }}</v-list-item-title>
          </v-list-item-content>
             <v-btn icon @click="removeCustomer()"><v-icon>remove</v-icon></v-btn>
        </v-list-item> 

       <v-divider></v-divider>


       </template> 
       
       <div v-for="(item, index) in items"
              :key="index" >
        <v-list-item two-line       >
            <v-btn icon @click="editItem(item)">{{item.qty}}</v-btn>
            <v-list-item-content>
              <v-list-item-title>{{item.name}}</v-list-item-title>
            </v-list-item-content>
            <v-btn icon>{{item.properties.price}}</v-btn>
            <v-btn icon v-if="allowRemoveItem" @click="removeItem(index)"><v-icon>remove</v-icon></v-btn>
          </v-list-item>
          <v-divider></v-divider>
       </div>
  

       <v-footer
          flat
          dense
          absolute
          padless
        >
            <v-list-item one-line>
              <v-list-item-content>
                <v-list-item-title>Discount</v-list-item-title>
              </v-list-item-content>
               <v-btn icon>2.50</v-btn>
            </v-list-item>
             <v-list-item one-line>
              <v-list-item-content>
                <v-list-item-title>Tax</v-list-item-title>
              </v-list-item-content>
               <v-btn icon>2.50</v-btn>
            </v-list-item>
             <v-list-item one-line>
              <v-list-item-content>
                <v-list-item-title>Service</v-list-item-title>
              </v-list-item-content>
               <v-btn icon>2.50</v-btn>
            </v-list-item>
            <v-list-item one-line>
              <v-list-item-content>
                <v-list-item-title>Total</v-list-item-title>
              </v-list-item-content>
               <v-btn icon>2.50</v-btn>
            </v-list-item>
        </v-footer>

  </v-navigation-drawer>

</template>

<script>
import { mapGetters } from 'vuex'

export default {
  data: () => ({
      items: [],
      allowRemoveItem: false,
   }),
  props: ['mini', 'customer', 'product'],

  mounted() {

  },
  watch: { 
    product: function(newVal, oldVal) {
      this.allowRemoveItem = false
      if(newVal) {
        const item = {...newVal}
        item.qty = 1 
        this.items.push(item)  
        this.$emit('item-added')
        let container = this.$el.querySelector(".v-navigation-drawer__content");
        container.scrollTop = container.scrollHeight;
      }     
    }
  },
  computed: {
    count() {
       return this.items.length
    },

  },
  methods: {
    navToggle() {
      this.$emit('nav-toggle')
    },
    customerToggle() {
      this.$emit('customer-toggle')
    },
    removeCustomer() {
       this.customer = null
    },
    enableRemoveItem() {
        this.allowRemoveItem = !this.allowRemoveItem
    },
    removeItem(index) {
       this.items.splice(index, 1)
    },
    editItem(item) {
       this.$emit('edit-item', item)
    },

  }
}
</script>

<style>
  .v-navigation-drawer__content {
      max-height: calc(100vh - (56px * 6));
  }
</style>
