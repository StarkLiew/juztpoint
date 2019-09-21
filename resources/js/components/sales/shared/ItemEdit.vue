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
                     <v-icon>label</v-icon>Discount
                  </v-flex>
                  <v-flex class="display-1" @click="showKeyboard = true">
                         {{discountRate | currency({fractionCount: decimal})}}
                  </v-flex>
                  <v-btn-toggle v-model="discountType" @change="(val) => { decimal = val + 1 }">
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
                  v-model="note"
                  row-height="30"
                  shaped
            ></v-textarea>
        </v-layout>
           
      <v-layout>

              <v-card tile       
                  class="pa-2"
                  outlined>

                    <v-select
                      px-10
                      :items="users"
                      item-text="name"
                      item-value="id"
                      label="Attended by"
                      chips
                      v-model="item.saleBy"
                      
                    ></v-select>
                  
              </v-card>

              <v-card tile
                  class="pa-2"
                  outlined>

                    <v-select
                      px-10
                      :items="users"
                      item-text="name"
                      item-value="id"
                      label="Share by"
                      chips
                      v-model="item.saleBy"
                      
                    ></v-select>
                  
            </v-card>
  
      </v-layout>

      <v-divider></v-divider>
      <v-layout v-if="item.properties.contain">
             <v-sheet
                 class="mx-auto"
                 max-width="380"
              >
                <v-slide-group show-arrows>
                  <v-slide-item
                    v-for="(subitem, subindex) in item.properties.contain"
                    :key="subindex"
             
                  >
                      <v-card tile class="pa-2" outlined>

                        <v-select
                          px-10
                          :items="users"
                          item-text="name"
                          item-value="id"
                          :label="getItem(subitem).name"
                          
                          
                        ></v-select>
                      
                       </v-card>
                      


                  </v-slide-item>
                </v-slide-group>
              </v-sheet>

        </v-layout>

         <keyboard 
            @done="showKeyboard = false"
            @clear="discountRate = 0.0"
            @change="discountRateChange"
            @close="closedKeyboard"
            :decimal="decimal"  
            :show="showKeyboard">
        </keyboard>
      </v-card>


</template>

<script>
import Keyboard from '../../ui/Keyboard'
import { mapGetters } from 'vuex'

export default {
  data: () => ({
     valid: false,
     lazy: false,
     discountFixed: false, 
     value: '0',
     qty: 1,
     note: '',
     showKeyboard: false,
     discountRate: 0.0,
     decimal: 1,
     discountType: 0,
     tab: 'tab-1', 
  }),
  components: {
    Keyboard,
  },
  computed: mapGetters({
    users: 'user/users',
  }),
  props: ['item', 'index', 'show'],
  mounted() {
        this.update()
  },
  watch: {
     show: {
        handler(val){
            this.update()
        },
        deep: true
     }
  },
  methods: {
      update() {
          this.qty = this.item.qty
          if(this.item.discount) {
             this.discountType = this.parseDiscountType(this.item.discount.discountType)
             this.discountRate = this.item.discount.rate
          } 
          if(this.item.note) { 
             this.note = this.item.note
          } 
      },
      inc(neg, prop) {
          let val = parseFloat(this.qty) + neg
          if(val > 0) this.qty = val
      },
      done() {
        const {qty, discountRate, discountType, note} = this
         this.item.qty = qty
         this.item.note = note
         this.item.discount = {rate: discountRate, type: this.parseDiscountType(discountType)}
         this.item.discount_amount = 
         this.$emit('done', this.item, this.index)
      },
      cancel() {
         this.$emit('cancel')
      },
      discountRateChange(val) {
         this.discountRate = val
      },
      parseDiscountType(discountType) {
            if(discountType === 0) {
                return 'percent'
            } 

            if(discountType === 1) {
                return 'fix'
            } 

            if(discountType === 'fix') {
                return 1
            } 
            
            return 0
      },
      closedKeyboard() {

          showKeyboard = false
      },
      getItem(id) {
           return this.$store.getters['service/collection'].find(o => o.id === id)  
      },
  }
}
</script>
