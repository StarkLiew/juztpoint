<template>



  <v-navigation-drawer fixed app :permanent="$vuetify.breakpoint.mdAndUp" light :mini-variant.sync="$vuetify.breakpoint.mdAndUp && mini" :clipped="$vuetify.breakpoint.mdAndUp" :value="mini" :width="300">
 
      <template v-slot:prepend>
       <v-toolbar dark  dense flat color="secondary">
           <v-btn icon><v-icon>shopping_cart</v-icon></v-btn>
           <v-toolbar-title class="white--text">0</v-toolbar-title>   
           <v-spacer></v-spacer>
          <v-btn icon><v-icon>delete</v-icon></v-btn>
        </v-toolbar>

        <v-list-item two-line>
        <v-btn icon><v-icon>person_add</v-icon></v-btn>
          <v-list-item-content>
            <v-list-item-title>New Customer</v-list-item-title>
   
          </v-list-item-content>
             <v-btn icon><v-icon>remove</v-icon></v-btn>
        </v-list-item>
      </template>

      <v-divider></v-divider>


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

  mounted() {
    this.navigation()
  },

    navigation() {
      this.items = [
       
      ]
    }
  }
}
</script>
