<script setup>
import { Head, Link } from "@inertiajs/vue3";
import {
    AlertTriangle,
    Ghost,
    Home,
    ServerCrash,
    ShieldAlert,
} from "lucide-vue-next";
import { computed } from "vue";

const props = defineProps({ status: Number });

const errorData = computed(() => {
    return (
        {
            403: {
                title: "403",
                name: "Acesso Negado",
                message: "Você não tem permissão para visualizar esta página.",
                icon: ShieldAlert,
                color: "text-orange-500",
            },
            404: {
                title: "404",
                name: "Página não encontrada",
                message:
                    "Desculpe, não conseguimos encontrar a página que você está procurando.",
                icon: Ghost,
                color: "text-blue-500",
            },
            500: {
                title: "500",
                name: "Erro Interno",
                message:
                    "Algo deu errado em nossos servidores. Tente novamente mais tarde.",
                icon: ServerCrash,
                color: "text-red-500",
            },
            503: {
                title: "503",
                name: "Manutenção",
                message: "Estamos em manutenção para melhorias. Voltamos logo!",
                icon: AlertTriangle,
                color: "text-yellow-500",
            },
        }[props.status] || {
            title: props.status,
            name: "Erro Inesperado",
            message: "Ocorreu um erro inesperado.",
            icon: AlertTriangle,
            color: "text-gray-500",
        }
    );
});
</script>

<template>
    <Head :title="errorData.name" />
    <div
        class="flex min-h-screen flex-col items-center justify-center bg-gray-900 px-6 py-24 sm:py-32 lg:px-8"
    >
        <div class="text-center">
            <component
                :is="errorData.icon"
                class="mx-auto h-20 w-20 animate-pulse"
                :class="errorData.color"
            />

            <h1
                class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-5xl"
            >
                {{ errorData.title }}
            </h1>

            <h2 class="mt-2 text-xl font-semibold text-gray-200">
                {{ errorData.name }}
            </h2>

            <p class="mt-6 text-base leading-7 text-gray-400">
                {{ errorData.message }}
            </p>

            <div class="mt-10 flex items-center justify-center gap-x-6">
                <Link
                    :href="route('dashboard')"
                    class="flex items-center gap-2 rounded-md bg-orange-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 transition-colors"
                >
                    <Home class="size-4" /> Voltar ao Início
                </Link>
            </div>
        </div>
    </div>
</template>
