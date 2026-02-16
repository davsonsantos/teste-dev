<script setup>
import Menu from "@/Components/Layout/Navigation/Menu.vue"; // Importamos o novo Menu
import { Link } from "@inertiajs/vue3";

defineProps({
    mobileSidebarOpen: Boolean,
    desktopSidebarOpen: Boolean,
});

defineEmits(["closeMobileSidebar"]);
</script>

<template>
    <nav
        id="page-sidebar"
        class="fixed top-0 bottom-0 left-0 z-50 flex h-full w-full flex-col border-r border-gray-800 bg-gray-800 text-gray-200 transition-transform duration-500 ease-out lg:w-64"
        :class="{
            '-translate-x-full': !mobileSidebarOpen,
            'translate-x-0': mobileSidebarOpen,
            'lg:-translate-x-full': !desktopSidebarOpen,
            'lg:translate-x-0': desktopSidebarOpen,
        }"
        aria-label="Main Sidebar Navigation"
    >
        <div
            class="flex h-16 w-full flex-none items-center justify-between bg-gray-600/25 px-4 lg:justify-center"
        >
            <Link
                :href="route('dashboard')"
                class="group inline-flex items-center gap-2"
            >
                TESTE DEV
            </Link>

            <div class="lg:hidden">
                <button
                    @click="$emit('closeMobileSidebar')"
                    class="p-2 text-gray-300"
                >
                    <svg class="size-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"
                        />
                    </svg>
                </button>
            </div>
        </div>

        <Menu />
    </nav>
</template>
