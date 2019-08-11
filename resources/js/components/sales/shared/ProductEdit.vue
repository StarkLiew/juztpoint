<template>
  <div> 

            <v-btn
                  color="red"
                  fab
                  dark
                  small
                  absolute
                  top
                  right
                  style="margin-top: 24px"
                  @click="cancel()"
                >
                  <v-icon>close</v-icon>
                </v-btn>
 

   <v-card
    max-width="344"
    class="mx-auto"
   >



     <v-card-title>{{item.name}}</v-card-title>
   
    


          <v-tabs
              v-model="tab"
              background-color="accent-4"
              centered
              dark
              icons-and-text
              @change="value='0'"
            >
              <v-tabs-slider></v-tabs-slider>

              <v-tab href="#tab-1">
                Qty
                <v-icon>add_shopping_cart</v-icon>
              </v-tab>

              <v-tab href="#tab-2">
                Discount
                <v-icon>label_off</v-icon>
              </v-tab>
          
              <v-tab href="#tab-3">
                Price
                <v-icon>attach_money</v-icon>
              </v-tab>
            </v-tabs>

           <v-tabs-items v-model="tab">
                <v-tab-item
                   value="tab-1"
                >
                  <v-container fluid grid-list-sm style="height:70px">
                     <v-layout wrap>
                           <v-btn color="success" fab medium dark @click="inc(1, 'qty')">
                               <v-icon>add</v-icon>
                            </v-btn>
                             <v-text-field
                                label=""
                                placeholder="0"
                                outlined
                                reverse
                                disabled
                                v-model="item.qty"
                              ></v-text-field>
                          <v-btn color="success" fab medium dark @click="inc(-1, 'qty')">
                               <v-icon>remove</v-icon>
                            </v-btn>

                     </v-layout>
                  </v-container>
                
                </v-tab-item>

              <v-tab-item
                   value="tab-2"
                >
                  <v-container fluid grid-list-sm style="height:70px"> 
                     <v-layout wrap>
                           <v-switch v-model="discountFixed" inset></v-switch>
                            <v-spacer></v-spacer>
                              <v-text-field
                                label=""
                                placeholder="0"
                                text-right
                                v-model="item.discount"
                                reverse
                                outlined
                                disabled
                              ></v-text-field>
                     

                     </v-layout>
                  </v-container>
                
                </v-tab-item>

                 <v-tab-item
                   value="tab-3"
                >
                  <v-container fluid grid-list-sm style="height:70px">
                     <v-layout wrap>
                             <v-text-field
                                label=""
                                placeholder="0"
                                v-model="item.price"
                                text-right
                                reverse
                                outlined
                                disabled
                              ></v-text-field>
                     </v-layout>
                  </v-container>
                
                </v-tab-item>
              </v-tabs-items>


  




 
    
              <v-container fluid grid-list-sm>
                <v-layout wrap>
                  <v-flex v-for="(key, i) in keys" :key="i" xs4 @click="touched(key)">
                    <v-img
                        :src="``"
                        :lazy-src="``"
                        aspect-ratio="1"
                        v-ripple
                        class="blue lighten-2 v-btn"
                      >
                        <template v-slot:placeholder>
                          <v-layout
                            fill-height
                            align-center
                            justify-center
                            ma-0>
                          </v-layout>
                        </template>

                        <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                          <v-spacer></v-spacer>
                          <v-flex shrink>
                            <div class="subheading text-wrap">{{ key }}</div>
                             <v-icon color="white">{{key}}</v-icon>
                            
                          </v-flex>
                        </v-layout>

                      </v-img>
                  </v-flex>
                </v-layout>
              </v-container>
       
   


    </v-card>
  </div>
</template>

<script>

export default {
  data: () => ({
     valid: false,
     lazy: false,
     discountFixed: false, 
     value: '0',
     tab: 'tab-1', 
     keys: ['1','2','3','4','5','6','7','8','9','clear','0','done'],
  }),
  props: ['item'],
  methods: {
      touched(key) {
          let deci = false

          let prop = ''
          if(this.tab == 'tab-1') {
             prop = 'qty'
          } 
          if(this.tab == 'tab-2') {
              prop = 'discount' 
              deci = true
          }
          if(this.tab == 'tab-3') {
             prop = 'price'
            deci = true
          }

          if(key=='clear') {
              this.value = '0'
              this.item[prop] = ''
              if(this.tab == 'tab-1') this.item[prop] = '1'  
              return
          }
          if(key=='done') {
            this.$emit('done')
            return
          }

          let val = this.value

       
          if(deci) {
  
            val = val.toString().replace('.', '')
            val = val.toString() + key
  
            val = parseFloat(Math.round(val) / 100).toFixed(2);
          } else {
            val += key
            val = parseInt(val)
          }
          this.value = val
          this.item[prop] = val


      },
      inc(neg, prop) {
          let val = parseFloat(this.item[prop]) + neg
          if(val > 0) this.item[prop] = val
      },
      done() {
           this.$emit('done', this.item)
      },
      cancel() {
           this.$emit('cancel') 
      },
  }
}
</script>
