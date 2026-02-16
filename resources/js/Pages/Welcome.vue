<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900"
    >
        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg"
        >
            <h2
                class="text-center text-2xl font-semibold text-gray-900 dark:text-white"
            >
                Bem-vindo ao Teste Prático
            </h2>
            <p class="mt-4 text-center text-gray-600 dark:text-gray-400">
                Sua plataforma de gerenciamento Cursos.
            </p>

            <div
                class="mt-5 grid grid-cols-1 gap-4 text-center sm:grid-cols-2 lg:gap-8"
            >
                <div>
                    <Link
                        :href="route('register')"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-blue-700 bg-blue-700 px-2 py-1 text-sm leading-5 font-semibold text-white hover:border-blue-600 hover:bg-blue-600 hover:text-white focus:ring-3 focus:ring-blue-400/50 active:border-blue-700 active:bg-blue-700 dark:focus:ring-blue-400/90"
                    >
                        Cadastre-se
                    </Link>
                </div>
                <div>
                    <Link
                        :href="route('login')"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-orange-700 bg-orange-700 px-2 py-1 text-sm leading-5 font-semibold text-white hover:border-orange-600 hover:bg-orange-600 hover:text-white focus:ring-3 focus:ring-orange-400/50 active:border-orange-700 active:bg-orange-700 dark:focus:ring-orange-400/90"
                    >
                        Login
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
