<script setup>
import { usePage } from "@inertiajs/vue3";
import { X } from "lucide-vue-next";
import { computed, onBeforeUnmount, ref, watch } from "vue";

const page = usePage();
const flash = computed(() => page.props.flash);

// Estado local para permitir fechar manualmente
const isVisible = ref(false);
let timer = null;

const styles = {
    info: "bg-blue-50 border-l-4 border-blue-500 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300",
    success:
        "bg-green-50 border-l-4 border-green-500 text-green-800 dark:bg-green-900/20 dark:text-green-300",
    error: "bg-red-50 border-l-4 border-red-500 text-red-800 dark:bg-red-900/20 dark:text-red-300",
    warning:
        "bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300",
    dark: "bg-gray-100 border-l-4 border-gray-600 text-gray-800 dark:bg-gray-800 dark:text-gray-300",
};

const icons = {
    success: "M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z",
    error: "M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z",
    info: "M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z",
    warning:
        "M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z",
};

const labels = {
    success: "Sucesso!",
    error: "Erro!",
    warning: "Atenção!",
    info: "Informação!",
    dark: "Aviso!",
};

const close = () => {
    isVisible.value = false;
};

// Gerenciamento do ciclo de vida da mensagem
watch(
    () => page.props.flash,
    (newFlash) => {
        if (newFlash?.message && newFlash.display === "flash") {
            isVisible.value = true;

            if (timer) clearTimeout(timer);

            // Auto-hide após 6 segundos
            timer = setTimeout(() => {
                close();
            }, 6000);
        }
    },
    { deep: true, immediate: true },
);

// Limpeza preventiva ao destruir o componente
onBeforeUnmount(() => {
    if (timer) clearTimeout(timer);
});
</script>

<template>
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-2"
    >
        <div
            v-if="isVisible && flash.message && flash.display === 'flash'"
            :class="[
                'relative p-4 mb-6 rounded-r-lg shadow-sm flex items-start gap-3 transition-all',
                styles[flash.type] || styles.info,
            ]"
            role="alert"
        >
            <svg
                v-if="icons[flash.type]"
                class="h-5 w-5 flex-shrink-0 mt-0.5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    :d="icons[flash.type]"
                />
            </svg>

            <div class="flex flex-col grow pr-6">
                <span
                    class="text-xs uppercase font-bold tracking-wider opacity-80"
                >
                    {{ labels[flash.type] || "Aviso" }}
                </span>
                <span class="text-sm font-medium leading-relaxed italic">
                    {{ flash.message }}
                </span>
            </div>

            <button
                @click="close"
                type="button"
                class="absolute top-3 right-3 p-1 rounded-md transition-colors hover:bg-black/5 dark:hover:bg-white/10"
                aria-label="Fechar"
            >
                <X class="size-4 opacity-60 hover:opacity-100" />
            </button>
        </div>
    </Transition>
</template>
