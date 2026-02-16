<script setup>
import { Menu as MenuIcon } from "lucide-vue-next";
import MenuUser from "./Navigation/MenuUser.vue";

defineProps({ desktopSidebarOpen: Boolean });
defineEmits(["toggleMobile", "toggleDesktop"]);
</script>

<template>
    <header
        id="page-header"
        class="fixed top-0 right-0 left-0 z-30 flex h-16 flex-none items-center bg-white shadow-xs dark:bg-gray-800 transition-all duration-500"
        :class="{ 'lg:pl-64': desktopSidebarOpen }"
    >
        <div
            class="mx-auto flex w-full max-w-10xl justify-between px-4 lg:px-8"
        >
            <div class="flex items-center gap-2">
                <button
                    @click="$emit('toggleDesktop')"
                    type="button"
                    class="hidden lg:inline-flex p-2 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-300"
                >
                    <MenuIcon class="size-5" />
                </button>
                <button
                    @click="$emit('toggleMobile')"
                    type="button"
                    class="lg:hidden p-2 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-300"
                >
                    <MenuIcon class="size-5" />
                </button>
            </div>

            <div class="flex items-center gap-2">
                <MenuUser />
                <!--  -->
            </div>
        </div>
    </header>
</template>
