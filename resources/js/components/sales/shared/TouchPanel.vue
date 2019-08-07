<template>
      <v-container fluid grid-list-sm>
          <v-layout wrap>
            <v-flex v-for="i in 6" :key="i" xs4>
              <img :src="`https://randomuser.me/api/portraits/men/${i + 20}.jpg`" class="image" alt="lorem" width="100%" height="100%">
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
