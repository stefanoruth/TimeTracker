<template>
    <div class="h-screen overflow-auto flex flex-col">
        <header class="w-full bg-blue-500 h-16">
            <div class="container mx-auto px-2 h-full flex justify-between items-center">
                <a to="/" class="text-white text-2xl">Time Tracker</a>
                <div class="h-full relative">
                    <button
                        v-if="user"
                        @click="showMenu = !showMenu"
                        class="w-16 p-2 h-full border-none"
                    >
                        <img class="rounded-full h-full w-full" :src="user.image">
                    </button>
                    <div v-show="showMenu" class="bg-white top-100 absolute right-0 shadow">
                        <a :href="route('logout')" class="px-8 py-4">Logout</a>
                    </div>
                </div>
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
		showMenu: false,
	}),

	mounted() {
		axios.get(this.route('auth')).then(res => {
			this.user = res.data
		})
	},
}
</script>
