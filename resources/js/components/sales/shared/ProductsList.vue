<template>
    <div>
        <v-container grid-list-sm fluid>
          <v-layout wrap >
            <v-flex
              v-for="(product, index) in products"
              :key="index"
              xs2
              d-flex
              child-flex
              @click="selected(product)"
            >
              <v-card flat tile class="d-flex">
                <v-img
                  :src="`https://picsum.photos/500/300?image=${index * 5 + 10}`"
                  :lazy-src="`https://picsum.photos/10/6?image=${index * 5 + 10}`"
                  aspect-ratio="1"
                  v-ripple
                  class="grey lighten-2 v-btn"
                >
                  <template v-slot:placeholder>
                    <v-layout
                      fill-height
                      align-center
                      justify-center
                      ma-0>
      
                      <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                    </v-layout>
                  </template>

                  <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                    <v-spacer></v-spacer>
                    <v-flex shrink>
                      <div class="subheading text-wrap">{{ product.name }}</div>
                      <div class="body-1">{{ product.properties.price }}</div>
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
       this.$emit('selected', product)
    }
  }
}
</script>
