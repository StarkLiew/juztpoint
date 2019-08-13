<template>
	<v-app-bar dark dense flat color="secondary">
  
     <v-btn icon><v-icon>more_vert</v-icon></v-btn>
       <v-btn icon><v-icon>search</v-icon></v-btn>
 
     <v-spacer></v-spacer>
       <v-btn icon  @click.stop="cartToggle"><v-icon>shopping_cart</v-icon></v-btn>
   
  </v-app-bar>



</template>

<script>
import { settings } from '~/config'

export default {
	data: () => ({
		siteName: settings.siteName
	}),

	methods: {
    cartToggle() {
      this.$emit('cart-toggle')
    }
	}
}
</script>
