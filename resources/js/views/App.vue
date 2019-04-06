<template>
    <div class="h-screen overflow-auto flex flex-col">
        <header class="w-full bg-blue-500 h-16">
            <div class="container mx-auto px-2 h-full flex justify-between items-center">
                <a to="/" class="text-white text-2xl">Time Tracker</a>
                <button v-if="user" class="w-16 h-16 p-2">
                    <img class="rounded-full h-full w-full" :src="user.image">
                </button>
            </div>
        </header>
        <main class="flex-1">
            <router-view :user="user"></router-view>
        </main>
    </div>
</template>

<script>
export default {
	data: () => ({
		user: null,
	}),

	mounted() {
		axios.get(route('auth')).then(res => {
			this.user = res.data
		})
	},
}
</script>
