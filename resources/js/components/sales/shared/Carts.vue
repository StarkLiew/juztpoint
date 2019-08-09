<template>

 <v-navigation-drawer fixed app :permanent="$vuetify.breakpoint.mdAndUp" light :mini-variant.sync="$vuetify.breakpoint.mdAndUp && mini" :clipped="$vuetify.breakpoint.mdAndUp" :value="mini" :width="350" right>

      <template v-slot:prepend>
       <v-toolbar dark  dense flat color="secondary">
           <v-btn icon><v-icon>shopping_cart</v-icon></v-btn>
           <v-toolbar-title class="white--text">0</v-toolbar-title>   
           <v-spacer></v-spacer>
          <v-btn icon><v-icon>delete</v-icon></v-btn>
        </v-toolbar>

        <v-list-item two-line>
        <v-btn icon @click="customerToggle()"><v-icon>person_add</v-icon></v-btn>
          <v-list-item-content>
            <v-list-item-title>New Customer</v-list-item-title>
          </v-list-item-content>
             <v-btn icon><v-icon>remove</v-icon></v-btn>
        </v-list-item> 

       <v-divider></v-divider>


       </template> 
       

        <v-list-item two-line       
         v-for="n in 10"
              :key="n" >
        <v-btn icon>1</v-btn>
          <v-list-item-content>
            <v-list-item-title>Item A</v-list-item-title>
          </v-list-item-content>
             <v-btn icon>2.50</v-btn>
        </v-list-item>
        <v-divider></v-divider>

  

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
   }),
  props: ['mini'],

  mounted() {
      this.navigation()
  },

  methods: {
    navToggle() {
      this.$emit('nav-toggle')
    },
    navigation() {
      this.items = [
       
      ]
    },
    customerToggle() {
      this.$emit('customer-toggle')
    }

  }
}
</script>

<style>
  .v-navigation-drawer__content {
      max-height: calc(100vh - (56px * 6));
  }
</style>
