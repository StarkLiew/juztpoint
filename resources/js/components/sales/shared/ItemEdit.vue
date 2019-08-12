<template>

 
      <v-card>
        <v-toolbar flat dark color="primary">
          <v-btn icon dark @click="cancel()">
            <v-icon>close</v-icon>
          </v-btn>
           <v-spacer></v-spacer>
          <v-toolbar-title>{{item.name}}</v-toolbar-title>
          <v-spacer></v-spacer>
     
            <v-btn icon dark text @click="done()">
                 Done
            </v-btn>
            
      
        </v-toolbar>
        <v-toolbar flat>

               <v-btn  color="success"  large dark @click="inc(1, 'qty')">
                    <v-icon>add</v-icon>
               </v-btn>
               <v-spacer></v-spacer>
               <v-toolbar-title class="display-1" >{{ qty }}</v-toolbar-title>
              <v-spacer></v-spacer>
               <v-btn  color="success" large dark @click="inc(-1, 'qty')">
                    <v-icon>remove</v-icon>
               </v-btn>       
        </v-toolbar>
        <v-divider></v-divider>
        <v-toolbar flat>
                  <v-spacer></v-spacer> 
                  <v-flex class="subheader">
                     <v-icon>label</v-icon>   Discount
                  </v-flex>
                  <v-flex class="display-1">
                         {{discountRate}}
                  </v-flex>
                  <v-btn-toggle v-model="discountType">
                      <v-btn large text>
                           %
                      </v-btn>
                      <v-btn large text>
                           $
                      </v-btn>
                  </v-btn-toggle>
     
        </v-toolbar>
        <v-layout>
            <v-textarea
                  filled
                  auto-grow
                  label="Note"
                  rows="2"
                  row-height="30"
                  shaped
            ></v-textarea>
        </v-layout>
           
      <v-layout px-10>
        <v-combobox
          v-model="item.saleBy"
          :items="['def','abc']"
          chips
          label="Sale Person"
        >
          <template v-slot:selection="data">
            <v-chip
              :key="JSON.stringify(data.item)"
              v-bind="data.attrs"
              :input-value="data.selected"
              :disabled="data.disabled"
              @click.stop="data.parent.selectedIndex = data.index"
              @click:close="data.parent.selectItem(data.item)"
            >
              <v-avatar class="accent white--text" left>
                {{ data.item.slice(0, 1).toUpperCase() }}
              </v-avatar>
              {{ data.item }}
            </v-chip>
          </template>
        </v-combobox>
      </v-layout>

              

      </v-card>


</template>

<script>

export default {
  data: () => ({
     valid: false,
     lazy: false,
     discountFixed: false, 
     value: '0',
     qty: 1,
     discountRate: 0.0,
     discountType: 'percentage',
     tab: 'tab-1', 
  }),
  props: ['item', 'index', 'show'],
  watch: {
     show(newVal) {
       if(newVal) {
          this.qty = this.item.qty
       }

     }
  },
  methods: {
      inc(neg, prop) {
          let val = parseFloat(this.qty) + neg
          if(val > 0) this.qty = val
      },
      done() {
         this.item.qty = this.qty
         this.$emit('done', this.item)
      
      },
      cancel() {
          this.$emit('cancel', this.item)
      },
  }
}
</script>
