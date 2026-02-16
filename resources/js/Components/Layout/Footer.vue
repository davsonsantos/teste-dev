<script setup>
import { onMounted, onUnmounted, ref } from "vue";

// Estado para armazenar a data e hora formatada
const currentTime = ref("");

const updateTime = () => {
    const agora = new Date();

    // Formatação Brasileira (pt-BR)
    // Exemplo: 24 de Maio de 2024, 14:30:05
    currentTime.value = agora.toLocaleString("pt-BR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    });
};

let timer;

onMounted(() => {
    updateTime(); // Atualiza imediatamente ao montar
    timer = setInterval(updateTime, 1000); // Inicia o loop de 1 segundo
});

onUnmounted(() => {
    clearInterval(timer); // Limpa o intervalo para evitar memory leak
});
</script>

<template>
    <footer
        id="page-footer"
        class="flex flex-none items-center bg-white border-t border-gray-100 dark:bg-gray-800/50 dark:border-gray-700/50"
    >
        <div
            class="mx-auto flex w-full max-w-10xl flex-col px-4 text-center text-sm md:flex-row md:justify-between md:text-left lg:px-8"
        >
            <div
                class="pt-4 pb-1 md:pb-4 font-mono text-gray-600 dark:text-gray-400"
            >
                <span class="inline-flex items-center gap-2">
                    <span
                        class="size-2 rounded-full bg-primary-500 animate-pulse"
                    ></span>
                    {{ currentTime }}
                </span>
            </div>

            <div
                class="inline-flex items-center justify-center pt-1 pb-4 md:pt-4 text-gray-500 dark:text-gray-400"
            >
                <span>Crafted with</span>
                <svg
                    class="hi-solid hi-heart mx-1 inline-block size-4 text-red-600"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill-rule="evenodd"
                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                        clip-rule="evenodd"
                    />
                </svg>
                <span
                    >by
                    <a
                        href="https://davtech.com.br"
                        target="_blank"
                        class="font-medium text-primary-600 hover:text-primary-400 dark:text-primary-400 dark:hover:text-primary-300 transition-colors"
                        >Davtech</a
                    >
                </span>
            </div>
        </div>
    </footer>
</template>
