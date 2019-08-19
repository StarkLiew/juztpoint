<template>
 <v-row justify="center">
    <v-dialog fullscreen v-model="show" persistent max-width="600px">
 
      <v-card>
        <v-card-title>
          <span class="headline">New Customer</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="12" md="12">
                <v-text-field
                  label="Name*"
                  hint="Your new customer name"
                  v-model="name"
                  persistent-hint
                  required
                ></v-text-field>
              </v-col>
             <v-col cols="12">
                <v-text-field v-model="mobile" label="Phone/Mobile" type="text" required></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field v-model="email" label="Email"></v-text-field>
              </v-col>

            </v-row>
          </v-container>
          <small>*indicates required field</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="close()">Close</v-btn>
          <v-btn color="blue darken-1" text @click="save()">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>

</template>

<script>
import { mapGetters } from 'vuex'
export default {
  data: () => ({
      name: '',
      email: '',
      mobile: '',

  }),
  props:['show'],


  methods: {
     close() {
        this.$emit('close')
          this.reset()
     },
     save() {
        const {name, email, mobile} = this
        this.$emit('save', {name, email, properties: {mobile}})
        this.reset()
     },
     reset() {
        this.name = ''
        this.email = ''
        this.mobile = ''
     },
  }
}
</script>
