<template>

        <v-container grid-list-sm fluid>
          <v-layout wrap>
            <v-flex
              v-for="n in 9"
              :key="n"
              xs2
              d-flex
              child-flex
            >
              <v-card flat tile class="d-flex">
                <v-img
                  :src="`https://picsum.photos/500/300?image=${n * 5 + 10}`"
                  :lazy-src="`https://picsum.photos/10/6?image=${n * 5 + 10}`"
                  aspect-ratio="1"
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
                      <div class="subheading">Banana</div>
                      <div class="body-1">1.00</div>
                    </v-flex>
                  </v-layout>

                </v-img>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>

</template>

<script>

export default {
  data: () => ({

  }),

  methods: {
    touched() {

    }
  }
}
</script>
