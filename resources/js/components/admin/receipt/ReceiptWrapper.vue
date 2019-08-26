<template>


  <div class="fill-height">
       <receipt-list 
          @selected="selected" 
          :show="showList"
       > </receipt-list>
       <receipt-tool @overlay="overlayShow"></receipt-tool>
 
        <v-content style="margin-top: 5px;">
   
      
                <receipt-view :selected="selectedItem"></receipt-view>
   
         
         <v-overlay :value="overlay" >
              <v-progress-circular indeterminate size="64"></v-progress-circular>
          </v-overlay>

        </v-content>


    
 </div>

          

</template>

<script>

import ReceiptTool from './ReceiptTool'
import ReceiptList from './ReceiptList'
import ReceiptView from './ReceiptView'

export default {
  data: () => ({
      overlay: false,   
      showList: true,
      selectedItem: null,
  }),
  components: {
    ReceiptTool,
    ReceiptList,
    ReceiptView,
  },

  methods: {
      overlayShow() {
          this.overlay = true
      },
      selected(item){
         this.selectedItem = item
      },

  }
}
</script>
