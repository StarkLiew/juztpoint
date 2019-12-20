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
        <v-dialog v-model="newAppDialog" scrollable fullscreen persistent max-width="500px" transition="dialog-transition">
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
                        <v-btn icon @click="newAppDialog = false" :disabled="saving">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                        <v-toolbar-title>
                            New Appointment
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn text @click="newAppDialog = false" :disabled="saving">
                            Save
                        </v-btn>
                    </v-toolbar>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="6" lg="6" md="6" sm="12">
                                <v-autocomplete dense v-model="form.customer" :items="customers" :rules="[v => !!v || 'Store is required',]" required :loading="loading" item-text="name" label="Customer" placeholder="Choose one" return-object></v-autocomplete>
                                <v-autocomplete dense v-model="form.user" :items="users" :rules="[v => !!v || 'Consultant is required',]" required :loading="loading" item-text="name" label="Consultant" placeholder="Choose one" return-object></v-autocomplete>
                                <v-textarea clearable v-model="form.note" clear-icon="mdi-close" label="Note"></v-textarea>
                            </v-col>
                            <v-col cols="6" lg="6" md="6" sm="12">
                                <v-dialog v-model="pickerDialog" :close-on-content-click="false" transition="scale-transition" offset-y full-screen>
                                    <template v-slot:activator="{ on }">
                                        <v-text-field dense class="" v-model="form.selectedDate" :value="$moment(form.startDate).format('YYYY-MM-DD hh:mm')" label="Date" v-on="on" readonly :rules="[v => !!v || 'Date is required',]" required></v-text-field>
                                    </template>
                                    <v-card>
                                        <v-row>
                                            <v-col cols="7" sm="12">
                                                <v-date-picker v-model="selectedDate" full-width>
                                                </v-date-picker>
                                            </v-col>
                                            <v-col cols="5" sm="12">
                                                <v-time-picker v-model="selectedTime" format="ampm" ampm-in-title full-width>
                                                </v-time-picker>
                                                <v-spacer></v-spacer>
                                                <v-card-actions>
                                                    <v-spacer></v-spacer>
                                                    <v-btn color="primary" @click="e6 = 3">Done</v-btn>
                                                    <v-btn text>Cancel</v-btn>
                                                </v-card-actions>
                                            </v-col>
                                        </v-row>
                                    </v-card>
                                </v-dialog>
                                <v-text-field v-model="form.endTime" label="Estimate End Time" readonly></v-text-field>
                                <v-container>
                                    <v-row>
                                        <v-col cols="10">
                                            <v-autocomplete dense v-model="itemForm.product" :items="services" :rules="[v => !!v || 'Service is required',]" required :loading="loading" item-text="name" label="Service requested" placeholder="Choose one" return-object></v-autocomplete>
                                        </v-col>
                                        <v-col cols="2">
                                            <v-btn icon text fab small color="green darken-1" @click="addRequest">
                                                <v-icon>mdi-plus</v-icon>
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                    <v-row v-for="(item, index) in form.items" :key="index">
                                        <v-col cols="10">
                                            {{ item.product.name }}
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
        newAppDialog: false,
        pickerDialog: false,
        loading: false,
        saving: false,
        today: '',
        focus: '',
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
            startDateTime: null,
            startTime: '',
            endTime: '',
            note: '',
            customer: '',
            user: '',
            status: '',
            items: [],
        },
        itemForm: {
            product: null,
        },
        start: null,
        end: null,
        selectedEvent: {},
        selectedElement: null,
        selectedOpen: false,
        events: [{
                name: 'Vacation',
                details: 'Going to the beach!',
                start: '2018-12-29',
                end: '2019-01-01',
                color: 'blue',
            },
            {
                name: 'Meeting',
                details: 'Spending time on how we do not have enough time',
                start: '2019-01-07 09:00',
                end: '2019-01-07 09:30',
                color: 'indigo',
            },
            {
                name: 'Large Event',
                details: 'This starts in the middle of an event and spans over multiple events',
                start: '2018-12-31',
                end: '2019-01-04',
                color: 'deep-purple',
            },
            {
                name: '3rd to 7th',
                details: 'Testing',
                start: '2019-01-03',
                end: '2019-01-07',
                color: 'cyan',
            },
            {
                name: 'Big Meeting',
                details: 'A very important meeting about nothing',
                start: '2019-01-07 08:00',
                end: '2019-01-07 11:30',
                color: 'red',
            },
            {
                name: 'Another Meeting',
                details: 'Another important meeting about nothing',
                start: '2019-01-07 10:00',
                end: '2019-01-07 13:30',
                color: 'brown',
            },
            {
                name: '7th to 8th',
                start: '2019-01-07',
                end: '2019-01-08',
                color: 'blue',
            },
            {
                name: 'Lunch',
                details: 'Time to feed',
                start: '2019-01-07 12:00',
                end: '2019-01-07 15:00',
                color: 'deep-orange',
            },
            {
                name: '30th Birthday',
                details: 'Celebrate responsibly',
                start: '2019-01-03',
                color: 'teal',
            },
            {
                name: 'New Year',
                details: 'Eat chocolate until you pass out',
                start: '2019-01-01',
                end: '2019-01-02',
                color: 'green',
            },
            {
                name: 'Conference',
                details: 'The best time of my life',
                start: '2019-01-21',
                end: '2019-01-28',
                color: 'grey darken-1',
            },
            {
                name: 'Hackathon',
                details: 'Code like there is no tommorrow',
                start: '2019-01-30 23:00',
                end: '2019-02-01 08:00',
                color: 'black',
            },
            {
                name: 'event 1',
                start: '2019-01-14 18:00',
                end: '2019-01-14 19:00',
                color: '#4285F4',
            },
            {
                name: 'event 2',
                start: '2019-01-14 18:00',
                end: '2019-01-14 19:00',
                color: '#4285F4',
            },
            {
                name: 'event 5',
                start: '2019-01-14 18:00',
                end: '2019-01-14 19:00',
                color: '#4285F4',
            },
            {
                name: 'event 3',
                start: '2019-01-14 18:30',
                end: '2019-01-14 20:30',
                color: '#4285F4',
            },
            {
                name: 'event 4',
                start: '2019-01-14 19:00',
                end: '2019-01-14 20:00',
                color: '#4285F4',
            },
            {
                name: 'event 6',
                start: '2019-01-14 21:00',
                end: '2019-01-14 23:00',
                color: '#4285F4',
            },
            {
                name: 'event 7',
                start: '2019-01-14 22:00',
                end: '2019-01-14 23:00',
                color: '#4285F4',
            },

        ],
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
        this.form.startDateTime = this.$moment().format('YYYY-MM-DD hh:mm')
        this.form.selectedDate = this.$moment().format('YYYY-MM-DD')
        this.form.selectedTime = this.$moment().format('hh:mm')
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
        updateRange({ start, end }) {
            // You could load events from an outside source (like database) now that we have the start and end dates on the calendar
            this.start = start
            this.end = end
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
            if (this.itemForm.product) {

                const { id, name, properties } = this.itemForm.product

                this.form.items.push({ product: { id, name, properties: { ...properties } } })
                this.estEndTime()
            }
            this.itemForm.product = null

        },
        removeRequest(index) {

            this.form.items.splice(index, 1)
            this.estEndTime()


        },
        estEndTime() {
            let est = 0
            for (const item of this.form.items) {

                est += parseInt(item.product.properties.duration)
            }
            const startDateTime = this.form.startDate + 'T' + this.form.startTime

            this.form.endTime = this.$moment(startDateTime).add(est, 'minutes').format('H:mm').toString()

        }
    },


}

</script>
