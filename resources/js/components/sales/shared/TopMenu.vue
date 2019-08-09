<template>
	<v-app-bar dark dense flat color="secondary">
  
    <v-app-bar-nav-icon @click.stop="navToggle"></v-app-bar-nav-icon>
       <v-btn icon><v-icon>search</v-icon></v-btn>
 
     <v-spacer></v-spacer>
     
    <v-btn icon><v-icon>more_vert</v-icon></v-btn>
  </v-app-bar>



</template>

<script>
import { settings } from '~/config'

export default {
	data: () => ({
		siteName: settings.siteName
	}),

	methods: {
    navToggle() {
      this.$emit('nav-toggle')
    }
	}
}
</script>
