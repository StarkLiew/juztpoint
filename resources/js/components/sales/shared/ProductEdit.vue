<template>
  <div> 

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
                           <v-btn color="success" fab medium dark>
                               <v-icon>add</v-icon>
                            </v-btn>
                             <v-text-field
                                label=""
                                placeholder="0"
                                outlined
                                disabled
                              ></v-text-field>
                          <v-btn color="success" fab medium dark>
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
                           <v-switch v-model="switch1" inset></v-switch>
                            <v-spacer></v-spacer>
                                       <v-text-field
                                label=""
                                placeholder="0"
                                text-right
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
                                text-right
                                outlined
                                disabled
                              ></v-text-field>
                     </v-layout>
                  </v-container>
                
                </v-tab-item>
              </v-tabs-items>


  




 
    
              <v-container fluid grid-list-sm>
                <v-layout wrap>
                  <v-flex v-for="(key, i) in keys" :key="i" xs4>
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
     tab: 'tab-1', 
     keys: ['1','2','3','4','5','6','7','8','9','cancel','0','done']
  }),
  props: ['item'],
  methods: {

  }
}
</script>
