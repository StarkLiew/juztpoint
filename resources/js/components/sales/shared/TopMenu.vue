<template>
	<v-app-bar dark dense flat color="secondary">
  

 <v-menu
      transition="slide-x-transition"
      bottom
      right
    >
      <template v-slot:activator="{ on }">
   
         <v-btn icon v-on="on"><v-icon>more_vert</v-icon></v-btn>
      </template>

      <v-list>
          <v-list-item @click="">
            <v-list-item-icon>
                <v-icon>add</v-icon>
            </v-list-item-icon>
            <v-list-item-content>New Transaction</v-list-item-content>
          </v-list-item> 

          <v-list-item @click="">
            <v-list-item-icon>
                <v-icon>folder</v-icon>
            </v-list-item-icon>
            <v-list-item-content>Open Draft</v-list-item-content>
          </v-list-item> 

          <v-list-item @click="">
            <v-list-item-icon>
                <v-icon>folder</v-icon>
            </v-list-item-icon>
            <v-list-item-content>Save Draft</v-list-item-content>
          </v-list-item> 

         <v-list-item @click="">
            <v-list-item-icon>
                <v-icon>refresh</v-icon>
            </v-list-item-icon>
            <v-list-item-content>Resync Data</v-list-item-content>
          </v-list-item>


          <v-list-item to="{name: 'pos'}">
            <v-list-item-icon>
                <v-icon>close</v-icon>
            </v-list-item-icon>
            <v-list-item-content>Exit</v-list-item-content>
          </v-list-item> 
      </v-list>
    </v-menu>


    
       <v-btn icon><v-icon>search</v-icon></v-btn>
 
     <v-spacer></v-spacer>

  
  </v-app-bar>
  


</template>

<script>

import { settings } from '~/config'

export default {
	data: () => ({
		siteName: settings.siteName,
    overlay: false,
	}),

	methods: {
    refresh() {
        this.$emit('overlay', true)

        setTimeout(() => {
           
         /* Fetch latest data */
          this.$store.dispatch('user/fetchUsers')
          this.$store.dispatch('product/fetchProducts')
          this.$store.dispatch('account/fetchCustomers')
          this.$emit('overlay', false)
        }, 3000)
 


    },

    cartToggle() {
      this.$emit('cart-toggle')
    }
	}
}
</script>
