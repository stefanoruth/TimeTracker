<template>
    <div class="flex flex-col h-full">
        <div class="container mx-auto px-2 py-4 flex justify-between border-b">
            <div class="text-2xl">Uge {{currentWeek}}, {{currentYear}}</div>
            <input class="w-48 h-12 border px-2" type="week" v-model="weekSelector">
        </div>

        <div class="container mx-auto px-2 py-4 flex">
            <input class="px-2 py-1 border" type="time" name="start" v-model="entry.start">
            <input class="px-2 py-1 border" type="time" name="end" v-model="entry.end">
            <textarea
                class="px-2 py-1 border h-10 flex-1"
                placeholder="Note..."
                v-model="entry.note"
            ></textarea>
            <label class="px-2 py-1 border flex justify-center items-center">
                <span class="pr-2">Lunch break</span>
                <input type="checkbox" v-model="entry.include_lunch">
            </label>
            <label class="px-2 py-1 border flex justify-center items-center">
                <span class="pr-2">Vacation</span>
                <input type="checkbox" v-model="entry.vacation">
            </label>
            <input class="px-2 py-1 border" type="date" v-model="entry.date">
            <button class="bg-blue-500 px-4 py-1 text-white" @click="store">Save</button>
        </div>

        <div class="flex-1 container mx-auto px-2">
            <div v-for="(item, key) in dates" :key="key" class="mb-8">
                <div class="flex justify-between items-center py-2 border-t border-b">
                    <div class="pr-4">
                        <span class="text-xl">{{item.weekDay}}</span>
                        <span>({{item.date_short}})</span>
                    </div>
                    <div>Timer: {{item.totalTime}}</div>
                </div>
                <div>
                    <table class="w-full table-fixed">
                        <tbody>
                            <tr
                                v-for="(entry, i) in item.registrations"
                                :key="i"
                                class="hover:bg-blue-100"
                            >
                                <td>{{entry.start}}</td>
                                <td>{{entry.end}}</td>
                                <td>{{entry.note}}</td>
                                <td>{{entry.include_lunch ? 'Lunch' : 'No lunch'}}</td>
                                <td>{{entry.vacation ? 'vecation': 'just work'}}</td>
                                <td>{{entry.time}}</td>
                                <td>
                                    <button @click="destroy(entry.id)">X</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <footer class="border-t">
            <div class="container mx-auto flex text-center text-xl">
                <div class="flex-1 p-2 border-r">Arbejdstimer: {{weekHours}} timer</div>
                <div class="flex-1 p-2">Flex Saldo: {{flex}} timer</div>
            </div>
        </footer>
    </div>
</template>

<script>
export default {
	props: ['user'],

	data: () => ({
		weekSelector: null,
		entry: {
			start: null,
			end: null,
			include_lunch: null,
			vacation: false,
			note: null,
			date: null,
		},
		currentWeek: null,
		currentYear: null,
		weekHours: 0,
		flex: 0,
		dates: [],
	}),

	watch: {
		weekSelector: function(val, oldVal) {
			if (oldVal === null) {
				return
			}
			const d = val.split('-W')

			axios.get(this.route('time.index', { week: d[1], year: d[0] })).then(res => {
				this.setData(res.data)
			})
		},
		user: function(user) {
			if (user) {
				this.entry.start = user.settings.start
				this.entry.end = user.settings.end
				this.entry.include_lunch = user.settings.lunch > 0
			}
		},
	},

	mounted() {
		this.entry.date = this.formatDate(new Date())

		this.load()
	},

	methods: {
		destroy(id) {
			axios.delete(this.route('time.destroy', { time: id })).then(() => this.load())
		},

		store() {
			axios.post(this.route('time.store'), this.entry).then(() => this.load())
		},

		load() {
			console.log({ week: this.currentWeek, year: this.currentYear })
			return axios.get(this.route('time.index'), { week: this.currentWeek, year: this.currentYear }).then(res => {
				this.weekSelector = res.data.year + '-W' + res.data.week
				this.setData(res.data)
			})
		},

		setData(data) {
			this.weekHours = data.weekHours
			this.currentWeek = data.week
			this.currentYear = data.year
			this.flex = data.flex
			this.dates = data.time
		},

		formatDate(date) {
			var d = new Date(date),
				month = '' + (d.getMonth() + 1),
				day = '' + d.getDate(),
				year = d.getFullYear()

			if (month.length < 2) month = '0' + month
			if (day.length < 2) day = '0' + day

			return [year, month, day].join('-')
		},
	},
}
</script>

