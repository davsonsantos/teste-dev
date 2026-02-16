<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed, watch } from "vue";

const page = usePage();
const flash = computed(() => page.props.flash);

watch(
    () => flash.value.status,
    (message) => {
        if (message) {
            console.log("Mensagem Global:", message);
        }
    },
);
</script>

<template>
    <div
        id="page-container"
        class="mx-auto flex min-h-dvh w-full min-w-80 flex-col bg-gray-100 dark:bg-gray-900 dark:text-gray-100"
    >
        <main id="page-content" class="flex max-w-full flex-auto flex-col">
            <div
                class="mx-auto flex min-h-dvh w-full max-w-10xl items-center justify-center bg-gray-200/50 p-4 lg:p-8 dark:bg-transparent"
            >
                <div
                    v-if="flash.status"
                    class="fixed top-5 right-5 z-50 max-w-sm animate-bounce"
                >
                    <div
                        class="rounded-lg bg-green-600 px-4 py-3 text-white shadow-lg"
                    >
                        {{ flash.status }}
                    </div>
                </div>
                <div class="w-full max-w-5xl lg:py-16">
                    <div
                        class="flex flex-col overflow-hidden rounded-lg bg-white shadow-xs md:flex-row dark:bg-gray-800 dark:text-gray-100"
                    >
                        <slot />

                        <div
                            class="relative flex items-center bg-cover px-6 py-10 md:w-1/2 md:px-10 lg:p-16"
                            style="
                                background-image: url(&quot;https://cdn.tailkit.com/media/placeholders/photo-m_7p45JfXQo-600x600.jpg&quot;);
                            "
                        >
                            <div
                                class="absolute inset-0 bg-primary-900/50"
                            ></div>
                        </div>
                    </div>
                    <div
                        class="mt-5 text-center text-sm text-gray-600 dark:text-gray-400"
                    >
                        Powered by
                        <Link
                            class="font-medium text-primary-600 hover:text-primary-400 dark:text-primary-400 dark:hover:text-primary-300"
                            href="https://davtech.com.br"
                            >Davson Santos</Link
                        >
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
