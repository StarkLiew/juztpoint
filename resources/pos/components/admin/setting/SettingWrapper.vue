<template>


  <div class="fill-height">

        <setting-items @overlay="overlayShow"></setting-items>
           <v-overlay :value="overlay"  >
              <v-progress-circular indeterminate size="64"></v-progress-circular>
          </v-overlay>
 </div>

                   
      

</template>

<script>

import SettingItems from './SettingItems'


export default {
  data: () => ({
      overlay: false,   
  }),
  components: {
    SettingItems,

  },

  methods: {
      overlayShow(value) {
          this.overlay = value
      },


  }
}
</script>
