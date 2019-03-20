<template>
    <div class="flex flex-col h-full">
        <div class="flex-1 container mx-auto px-2">
            <div v-for="(date, key) in dates" :key="key">
                <div class="flex justify-between">
                    <div>{{date.day}}</div>
                    <div>Timer: {{date.total}}</div>
                </div>
                <div>
                    <div v-for="(entry, i) in date.hours" :key="i" class="flex">
                        <div>{{entry.start}}</div>
                        <div>{{entry.end}}</div>
                        <div>{{entry.note}}</div>
                        <div class="self-end">{{entry.time}}</div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="border-t">
            <div class="container mx-auto flex text-center text-xl">
                <div class="flex-1 p-2 border-r">Arbejdstimer: 20 timer</div>
                <div class="flex-1 p-2">Flex Saldo: 8 timer</div>
            </div>
        </footer>
    </div>
</template>

<script>
export default {
	data: () => ({
		dates: [
			{
				day: 'Mandag',
				total: 7.5,
				hours: [
					{
						start: '08:00',
						end: '16:00',
						time: 7.5,
						note: 'Woool',
					},
				],
			},
			{
				day: 'Tirsdag',
				total: 0,
				hours: [],
			},
			{
				day: 'Onsdag',
				total: 0,
				hours: [],
			},
			{
				day: 'Torsdag',
				total: 0,
				hours: [],
			},
			{
				day: 'Freday',
				total: 0,
				hours: [],
			},
			{
				day: 'Lørdag',
				total: 0,
				hours: [],
			},
			{
				day: 'Søndag',
				total: 0,
				hours: [],
			},
		],
	}),
}
</script>

