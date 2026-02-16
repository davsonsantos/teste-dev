<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div
        class="flex h-16 flex-none items-center justify-between border-b border-gray-100 px-4 dark:border-gray-700/75"
    >
        <Link
            :href="route('dashboard')"
            class="group inline-flex items-center gap-2 px-2.5 font-bold tracking-wide text-gray-900 hover:text-gray-600 dark:text-gray-100 dark:hover:text-gray-300"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 78 32"
                fill="none"
                class="w-8"
            >
                <path
                    d="M55.5 0H77.5L58.5 32H36.5L55.5 0Z"
                    fill="#3f3f46"
                ></path>
                <path
                    d="M35.5 0H51.5L32.5 32H16.5L35.5 0Z"
                    fill="#71717a"
                ></path>
                <path
                    d="M19.5 0H31.5L12.5 32H0.5L19.5 0Z"
                    fill="#d4d4d8"
                ></path>
            </svg>
            <span>TESTE DEV</span>
        </Link>

        <div class="flex items-center lg:hidden">
            <button
                @click="$emit('close')"
                type="button"
                class="group relative inline-flex items-center p-2 text-gray-800 dark:text-gray-200"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 16 16"
                    fill="currentColor"
                    class="size-4 relative"
                >
                    <path
                        d="M5.28 4.22a.75.75 0 0 0-1.06 1.06L6.94 8l-2.72 2.72a.75.75 0 1 0 1.06 1.06L8 9.06l2.72 2.72a.75.75 0 1 0 1.06-1.06L9.06 8l2.72-2.72a.75.75 0 0 0-1.06-1.06L8 6.94 5.28 4.22Z"
                    />
                </svg>
            </button>
        </div>
    </div>
</template>
