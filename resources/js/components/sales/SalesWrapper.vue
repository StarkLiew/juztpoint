<template>


   <div class="fill-height">
       <carts 
          @nav-toggle="navToggle"
          @customer-toggle="customerToggle" 
       > </carts>
          <top-menu :mini="mini"  @nav-toggle="navToggle"></top-menu>
 
        <v-content>
   
           <v-sheet
              id="scrolling-techniques-7"
              class="overflow-y-auto"
              max-height="calc(100vh - 48px)"
              color="transparent"
            >           
                <touch-panel v-if="panel === 'product'"></touch-panel>
                <customers-list v-if="panel === 'customer'"></customers-list>
             </v-sheet>
        </v-content>
    
 </div>

          

</template>

<script>

import TopMenu from './shared/TopMenu'
import Carts from './shared/Carts'
import TouchPanel from './shared/TouchPanel'
import CustomersList from './shared/CustomersList'

export default {
  data: () => ({
    mini: false,
    panel: 'product',

  }),

  components: {
    TopMenu,
    Carts,
    TouchPanel,
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
    }

  }
}
</script>
