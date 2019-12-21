<template>
    <div>
        <v-toolbar flat color="white">
            <v-btn outlined class="mr-4" @click="setToday">
                Today
            </v-btn>
            <v-btn fab text small @click="prev">
                <v-icon small>mdi-chevron-left</v-icon>
            </v-btn>
            <v-btn fab text small @click="next">
                <v-icon small>mdi-chevron-right</v-icon>
            </v-btn>
            <v-toolbar-title>{{ title }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-menu bottom right>
                <template v-slot:activator="{ on }">
                    <v-btn outlined v-on="on">
                        <span>{{ typeToLabel[type] }}</span>
                        <v-icon right>mdi-menu-down</v-icon>
                    </v-btn>
                </template>
                <v-list>
                    <v-list-item @click="type = 'day'">
                        <v-list-item-title>Day</v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="type = 'week'">
                        <v-list-item-title>Week</v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="type = 'month'">
                        <v-list-item-title>Month</v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="type = '4day'">
                        <v-list-item-title>4 days</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-toolbar>
        <v-sheet>
            <v-calendar ref="calendar" v-model="focus" color="primary" :events="events" :event-color="getEventColor" :event-margin-bottom="3" :now="today" :type="type" @click:event="showEvent" @click:more="viewDay" @click:date="viewDay" @change="updateRange"></v-calendar>
            <v-menu v-model="selectedOpen" :close-on-content-click="false" :activator="selectedElement" full-width offset-x>
                <v-card color="grey lighten-4" min-width="350px" flat>
                    <v-toolbar :color="selectedEvent.color" dark>
                        <v-btn icon>
                            <v-icon>mdi-pencil</v-icon>
                        </v-btn>
                        <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn icon>
                            <v-icon>mdi-heart</v-icon>
                        </v-btn>
                        <v-btn icon>
                            <v-icon>mdi-dots-vertical</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <span v-html="selectedEvent.details"></span>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn text color="secondary" @click="selectedOpen = false">
                            Cancel
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-menu>
        </v-sheet>
        <v-dialog v-model="formDialog" scrollable fullscreen persistent max-width="500px" transition="dialog-transition">
            <template v-slot:activator="{on}">
                <v-fab-transition>
                    <v-btn v-on="on" color="pink" dark fixed bottom right large fab>
                        <v-icon>mdi-plus</v-icon>
                    </v-btn>
                </v-fab-transition>
            </template>
            <v-card>
                <v-card-title primary-title>
                    <v-toolbar dark color="primary">
                        <v-btn icon @click="closeForm" :disabled="saving">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                        <v-toolbar-title>
                            New Appointment
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn text @click="saveForm" :disabled="saving">
                            Save
                        </v-btn>
                    </v-toolbar>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-form ref="appForm" v-model="validForm">
                            <v-row>
                                <v-col cols="6" lg="6" md="6" sm="12">
                                    <v-autocomplete v-model="form.customer" :items="customers" :rules="[v => !!v || 'Customer is required',]" required :loading="loading" item-text="name" label="Customer" placeholder="Choose one" return-object></v-autocomplete>
                                    <v-autocomplete v-model="form.user" :items="users" :rules="[v => !!v || 'Consultant is required',]" required :loading="loading" item-text="name" label="Consultant" placeholder="Choose one" return-object></v-autocomplete>
                                    <v-textarea clearable v-model="form.note" clear-icon="mdi-close" label="Note"></v-textarea>
                                </v-col>
                                <v-col cols="6" lg="6" md="6" sm="12">
                                    <v-row>
                                        <v-col cols="7" lg="7" md="7" sm="7">
                                            <v-menu v-model="datePickerShow" :close-on-content-click="false" transition="scale-transition" offset-y min-width="344" max-width="400">
                                                <template v-slot:activator="{ on }">
                                                    <v-text-field class="" v-model="form.startDate" :value="$moment(form.startDate).format('YYYY-MM-DD hh:mm')" label="Start Date" v-on="on" readonly :rules="[v => !!v || 'Date is required',]" required></v-text-field>
                                                </template>
                                                <v-date-picker @input="closeDatePicker" v-model="form.startDate" full-width>
                                                </v-date-picker>
                                            </v-menu>
                                        </v-col>
                                        <v-col cols="5" lg="5" md="5" sm="5">
                                            <v-menu v-model="timePickerShow" :close-on-content-click="false" transition="scale-transition" offset-y min-width="344" max-width="400">
                                                <template v-slot:activator="{ on }">
                                                    <v-text-field class="" v-model="form.startTime" :value="$moment(form.startTime).format('YYYY-MM-DD hh:mm')" label="Start Time" v-on="on" readonly :rules="[v => !!v || 'Date is required',]" required></v-text-field>
                                                </template>
                                                <v-time-picker @input="closeTimePicker" v-model="form.startTime" format="ampm" ampm-in-title full-width>
                                                </v-time-picker>
                                            </v-menu>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="8" lg="8" md="8" sm="8">
                                            <v-text-field v-model="form.endDate" label="End Date" disabled readonly></v-text-field>
                                        </v-col>
                                        <v-col cols="4" lg="4" md="4" sm="4">
                                            <v-text-field v-model="form.endTime" label="End Time" disabled readonly></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="10">
                                                <v-autocomplete dense v-model="itemForm.item" :items="services" required :loading="loading" item-text="name" label="Service requested" placeholder="Choose one" return-object clearable></v-autocomplete>
                                            </v-col>
                                            <v-col cols="2">
                                                <v-btn icon text :disabled="!itemForm.item && !form.user" fab small color="green darken-1" @click="addRequest">
                                                    <v-icon>mdi-plus</v-icon>
                                                </v-btn>
                                            </v-col>
                                        </v-row>
                                        <v-row v-for="(item, index) in form.items" :key="index">
                                            <v-col cols="10">
                                                {{ item.item.name }}
                                            </v-col>
                                            <v-col cols="2">
                                                <v-btn icon text fab small color="red darken-1" @click="removeRequest(index)">
                                                    <v-icon>mdi-close</v-icon>
                                                </v-btn>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
import Vue from 'vue'
import { mapGetters } from 'vuex'

export default {
    data: () => ({
        e6: 1,
        formDialog: false,
        pickerDialog: false,
        validForm: false,
        loading: false,
        saving: false,
        today: '',
        focus: '',
        datePickerShow: false,
        timePickerShow: false,
        selectedDate: null,
        selectedTime: null,
        type: 'week',
        typeToLabel: {
            month: 'Month',
            week: 'Week',
            day: 'Day',
            '4day': '4 Days',
        },
        form: {
            startDate: null,
            startTime: '',
            endDate: null,
            endTime: '',
            note: '',
            customer: '',
            user: '',
            status: '',
            items: [],
        },
        itemForm: {
            item: null,
        },
        start: null,
        end: null,
        selectedEvent: {},
        selectedElement: null,
        selectedOpen: false,
        events: [],
    }),
    computed: {
        ...mapGetters({
            auth: 'auth/user',
            items: 'receipt/items',
            customers: 'account/items',
            users: 'user/users',
            services: 'product/items',

        }),
        title() {
            const { start, end } = this
            if (!start || !end) {
                return ''
            }

            const startMonth = this.monthFormatter(start)
            const endMonth = this.monthFormatter(end)
            const suffixMonth = startMonth === endMonth ? '' : endMonth

            const startYear = start.year
            const endYear = end.year
            const suffixYear = startYear === endYear ? '' : endYear

            const startDay = start.day + this.nth(start.day)
            const endDay = end.day + this.nth(end.day)

            switch (this.type) {
                case 'month':
                    return `${startMonth} ${startYear}`
                case 'week':
                case '4day':
                    return `${startMonth} ${startDay} ${startYear} - ${suffixMonth} ${endDay} ${suffixYear}`
                case 'day':
                    return `${startMonth} ${startDay} ${startYear}`
            }
            return ''
        },
        monthFormatter() {
            return this.$refs.calendar.getFormatter({
                timeZone: 'UTC',
                month: 'long',
            })
        },
    },
    created() {
        this.retrieve()
        this.today = this.$moment().format('YYYY-MM-DD').toString()
        this.focus = this.$moment().format('YYYY-MM-DD').toString()

    },
    mounted() {
        this.$refs.calendar.checkChange()

    },

    methods: {

        async retrieve() {

            this.loading = true

            await this.$store.dispatch('user/fetch', { type: 'user', search: '', limit: 0, page: 1, sort: [], desc: [], noCommit: false, })

            await this.$store.dispatch('account/fetch', { type: 'customer', search: '', limit: 0, page: 1, sort: [], desc: [], noCommit: false, })

            await this.$store.dispatch('product/fetch', { type: 'service', search: '', limit: 0, page: 1, sort: [], desc: [], noCommit: false, })




            this.loading = false
        },

        viewDay({ date }) {
            this.focus = date
            this.type = 'day'
        },
        getEventColor(event) {
            return event.color
        },
        setToday() {
            this.focus = this.today
        },
        prev() {
            this.$refs.calendar.prev()
        },
        next() {
            this.$refs.calendar.next()
        },
        showEvent({ nativeEvent, event }) {
            const open = () => {
                this.selectedEvent = event
                this.selectedElement = nativeEvent.target
                setTimeout(() => this.selectedOpen = true, 10)
            }

            if (this.selectedOpen) {
                this.selectedOpen = false
                setTimeout(open, 10)
            } else {
                open()
            }

            nativeEvent.stopPropagation()
        },
        async updateRange({ start, end }) {
            // You could load events from an outside source (like database) now that we have the start and end dates on the calendar
            this.start = start
            this.end = end
            this.loading = true

            const filter = { from: start, to: end }

            const fields = `id, status, store{id, name, properties{timezone, currency}}, account_id, terminal_id, store_id, shift_id, reference, account{id, name}, terminal{id, name}, store{id, name}, transact_by, teller{id, name}, date, type, discount{type, rate, amount}, discount_amount,  tax_amount, service_charge, charge, rounding, received, change, properties{startDateTime, endDateTime}, note,refund, items{id, line, item_id, item{id, name, sku}, user_id, note, name, discount{type, rate, amount}, discount_amount, tax{id, name, properties{rate, code}}, tax_id, tax_amount, qty, refund_qty, receives{id, store_id, store{id, name}, user{id, name}, qty, properties{do, date}}, refund_amount, total_amount, amount, saleBy{id, name}, shareWith{id, name}, composites{id, name, performBy{id, name}}, properties{price}}, payments{id, name, line, item_id, total_amount},properties{name}, note`


            const results = await this.$store.dispatch('receipt/fetch', { name: 'appointment', fields, filter, limit: 0, page: 1, sort: [], desc: [], noCommit: true })

            this.events = results.data.data.map(d => {

                let details = ''
                for (const line of d.items) {
                    details += line.item.name + '<br />'
                }

                return {
                    name: d.account.name,
                    details,
                    start: d.properties.startDateTime,
                    end: d.properties.endDateTime,
                    color: 'pink',
                }
            })


            this.loading = false


        },
        nth(d) {
            return d > 3 && d < 21 ?
                'th' : ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'][d % 10]
        },
        pickedDate(val) {
            this.form.startDate = val
            this.showDatePicker = false
        },
        addRequest() {
            if (this.itemForm.item && this.form.user) {

                const { id, name, properties } = this.itemForm.item
                const item = {
                    item: {
                        id,
                        name,
                    },
                    user_id: this.form.user.id,
                    qty: 1,
                    tax: { id: 0 },
                    tax_id: 0,
                    discount: {},
                    discount_amount: 0.00,
                    tax_amount: 0.00,
                    total_amount: 0.00,
                    note: '',
                    properties: { price: 0.00, ...properties }
                }


                this.form.items.push({ ...item })
                this.estEndTime()
            }
            this.itemForm.item = null

        },
        removeRequest(index) {

            this.form.items.splice(index, 1)
            this.estEndTime()


        },
        estEndTime() {
            let est = 0
            for (const item of this.form.items) {
                est += parseInt(item.properties.duration)
            }
            const startDateTime = this.form.startDate + 'T' + this.form.startTime
            const endDateTime = this.$moment(startDateTime).add(est, 'minutes')
            this.form.endDate = endDateTime.format('YYYY-MM-DD').toString()
            this.form.endTime = endDateTime.format('H:mm').toString()
        },
        closeForm() {
            this.resetForm()
            this.formDialog = false
        },
        resetForm() {
            this.$refs.appForm.reset()
            this.$refs.appForm.resetValidation()
            this.validForm = false
            this.form = {
                startDate: null,
                startTime: '',
                endDate: null,
                endTime: '',
                note: '',
                customer: '',
                user: '',
                status: '',
                items: [],
            }
        },
        async saveForm() {
            if (this.$refs.appForm.validate()) {
                this.saving = true
                this.estEndTime()
                const { customer, user, startDate, startTime, endDate, endTime, status, note, items } = this.form
                let item = {
                    date: this.$moment(startDate).format('YYYY-MM-DD').toString(),
                    account: customer,
                    transact_by: user.id,
                    type: 'appointment',
                    status: 'set',
                    reference: this.generateUID(),
                    charge: 0.00,
                    tax_amount: 0.00,
                    items,
                    properties: {
                        startDateTime: this.$moment(startDate + 'T' + startTime).format('YYYY-MM-DD H:mm').toString(),
                        endDateTime: this.$moment(endDate + 'T' + endTime).format('YYYY-MM-DD H:mm').toString(),
                    },
                    note,

                }



                await this.$store.dispatch('document/add', item)
                this.closeForm()
                this.saving = false
            }

        },
        closeDatePicker() {
            this.estEndTime()
            this.datePickerShow = false
        },
        closeTimePicker() {
            this.estEndTime()
            this.timePickerShow = false
        },
        generateUID() {
            // I generate the UID from two parts here 
            // to ensure the random number provide enough bits.
            var firstPart = (Math.random() * 46656) | 0;
            var secondPart = (Math.random() * 46656) | 0;
            firstPart = ("000" + firstPart.toString(36)).slice(-3);
            secondPart = ("000" + secondPart.toString(36)).slice(-3);
            return firstPart + secondPart;
        }
    },


}

</script>
