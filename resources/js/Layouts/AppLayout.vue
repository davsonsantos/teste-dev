<script setup>
import Footer from "@/Components/Layout/Footer.vue";
import Header from "@/Components/Layout/Header.vue";
import Navigation from "@/Components/Layout/Navigation.vue";
import { usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { toast, Toaster } from "vue-sonner";
import "vue-sonner/style.css";
import FlashMessage from "../Components/Global/FlashMessage.vue";

const mobileSidebarOpen = ref(false);
const desktopSidebarOpen = ref(true);
const page = usePage();

watch(
    () => page.props.flash,
    (flash) => {
        // A CONDIÇÃO CHAVE: message existe E display é 'toast'
        if (flash?.message && flash.display === "toast") {
            const message = flash.message;

            if (flash.type === "error") {
                toast.error(message);
            } else if (flash.type === "warning") {
                toast.warning(message);
            } else {
                toast.success(message);
            }
        }
    },
    { deep: true },
);
</script>

<template>
    <div
        id="page-container"
        class="mx-auto flex min-h-dvh w-full min-w-80 flex-col bg-gray-100 dark:bg-gray-950 transition-all duration-500"
        :class="{ 'lg:pl-64': desktopSidebarOpen }"
    >
        <Toaster position="top-right" richColors closeButton />

        <Navigation
            :mobile-sidebar-open="mobileSidebarOpen"
            :desktop-sidebar-open="desktopSidebarOpen"
            @close-mobile-sidebar="mobileSidebarOpen = false"
            :class="{ 'mt-10': page.props.auth.is_impersonating }"
        />

        <Header
            :desktop-sidebar-open="desktopSidebarOpen"
            @toggle-mobile="mobileSidebarOpen = !mobileSidebarOpen"
            @toggle-desktop="desktopSidebarOpen = !desktopSidebarOpen"
            :class="{ 'mt-10': page.props.auth.is_impersonating }"
        />

        <main
            id="page-content"
            class="flex max-w-full flex-auto flex-col"
            :class="page.props.auth.is_impersonating ? 'pt-24' : 'pt-16'"
        >
            <div class="mx-auto w-full max-w-10xl p-4 lg:p-8">
                <div v-if="$slots.header" class="mb-6">
                    <slot name="header" />
                </div>

                <FlashMessage />
                <slot />
            </div>
        </main>

        <Footer />
    </div>
</template>
