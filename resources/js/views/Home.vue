<template>
    <div class="flex flex-col h-full">
        <input type="week" v-model="input">

        <div class="flex-1 container mx-auto px-2">
            <div v-for="(item, key) in dates" :key="key" class="border-b">
                <div class="flex justify-between">
                    <div class="text-xl">{{item.weekDay}}</div>
                    <div>Timer: {{item.totalTime}}</div>
                </div>
                <div>
                    <div v-for="(entry, i) in item.registrations" :key="i" class="flex">
                        <div class="flex-1">{{entry.start}}</div>
                        <div class="flex-1">{{entry.end}}</div>
                        <div class="flex-1">{{entry.note}}</div>
                        <div class="flex-1 self-end">{{entry.time}}</div>
                    </div>
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
	data: () => ({
		input: null,
		weekHours: 0,
		flex: 0,
		dates: [],
	}),

	watch: {
		input: function(val, oldVal) {
			if (oldVal === null) {
				return
			}
			const d = val.split('-W')

			axios.get(route('time.index', { week: d[1], year: d[0] })).then(res => {
				this.dates = res.data.time
				this.weekHours = res.data.weekHours
			})
		},
	},

	mounted() {
		this.load()
	},

	methods: {
		delete(id) {
			axios.delete(route('time.destroy', { id })).then(() => this.load())
		},

		update() {},

		create() {},

		async load() {
			return axios.get(route('time.index')).then(res => {
				this.input = res.data.year + '-W' + res.data.week
				this.weekHours = res.data.weekHours
				this.flex = res.data.flex
				this.dates = res.data.time
			})
		},
	},
}
</script>

