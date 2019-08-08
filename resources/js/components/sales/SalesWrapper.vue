<template>


   <div class="fill-height">
       <carts @nav-toggle="navToggle"> </carts>
  

       <v-content>
         <top-menu :mini="mini"  @nav-toggle="navToggle"></top-menu>
        <v-container fluid>
            <touch-panel></touch-panel>
        </v-container>
    </v-content>
 </div>

          

</template>

<script>

import TopMenu from './shared/TopMenu'
import Carts from './shared/Carts'
import TouchPanel from './shared/TouchPanel'

export default {
  data: () => ({
    mini: false
  }),

  components: {
    TopMenu,
    Carts,
    TouchPanel,
  },

  methods: {
    navToggle() {
        this.mini = !this.mini
    }
  }
}
</script>
