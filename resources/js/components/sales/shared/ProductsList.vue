<template>
    <div>
        <v-container grid-list-sm fluid>
          <v-layout wrap >
            <v-flex
              v-for="(product, index) in products"
              :key="index"
              xs4 sm2 md2
              d-flex
              child-flex
              @click="selected(product)"
            >
              <v-card flat tile class="d-flex">
                <v-img
                  :src="product.properties.thumbnail ? product.properties.thumbnail :  ``"
                  aspect-ratio="1"
                  v-ripple
                  class="v-btn"
                  :class="[product.properties.color ? product.properties.color : 'blue']"
                >
                  <!-- <template v-slot:placeholder>
                    <v-layout
                      fill-height
                      align-center
                      justify-center

                      ma-0>
      
                     <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular> 
                    </v-layout>
                  </template> -->

                  <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                    <v-spacer></v-spacer>
                    <v-flex shrink>
                      <div class="subheading text-wrap">{{ product.name }}</div>
                      
                    </v-flex>
                  </v-layout>

                </v-img>
              </v-card>
            </v-flex>
          </v-layout>
          
        </v-container>
   </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {


  data: () => ({
     
  }),

  computed: mapGetters({
    products: 'product/collection'
  }),

  methods: {
    selected(product) {
       const defaultItem = {
                             qty: 1,
                             price: 0.00,
                             discount: 0.0,
                             saleBy: null,
                          }
       const item = {...defaultItem, ...product}
       this.$emit('selected', item)
    }
  }
}
</script>
