<script setup>
import { X } from "lucide-vue-next";
import { onUnmounted, watch } from "vue";
import Button from "../../Components/Forms/Button.vue";

const props = defineProps({
    show: { type: Boolean, default: false },
    title: { type: String, default: "Título do Modal" },
    maxWidth: { type: String, default: "2xl" },
    actionText: { type: String, default: null },
    actionVariant: { type: String, default: "blue" },
    loading: { type: Boolean, default: false },
});

// A CORREÇÃO ESTÁ AQUI: Declaramos os eventos que este componente pode disparar
const emit = defineEmits(["close", "action"]);

const toggleScroll = (disable) => {
    document.body.style.overflow = disable ? "hidden" : "";
};

const handleActionClick = () => {
    console.log("1. Modal: O botão foi clicado. Tentando emitir 'action'...");
    emit("action");
};

watch(
    () => props.show,
    (newValue) => {
        toggleScroll(newValue);
    },
);

onUnmounted(() => toggleScroll(false));
</script>

<template>
    <Teleport to="body">
        <div
            v-if="show"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
        >
            <div
                class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
                @click="emit('close')"
            ></div>

            <div
                class="relative w-full transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all dark:bg-gray-800"
                :class="{
                    'max-w-sm': maxWidth === 'sm',
                    'max-w-md': maxWidth === 'md',
                    'max-w-lg': maxWidth === 'lg',
                    'max-w-xl': maxWidth === 'xl',
                    'max-w-2xl': maxWidth === '2xl',
                }"
            >
                <div
                    class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-700"
                >
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        <slot name="header">{{ title }}</slot>
                    </h3>
                    <button
                        @click="emit('close')"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300"
                    >
                        <X class="size-6" />
                    </button>
                </div>

                <div class="max-h-[70vh] overflow-y-auto px-6 py-6">
                    <slot name="body"></slot>
                </div>

                <div
                    class="flex items-center justify-between border-t border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-800/50"
                >
                    <Button
                        variant="white"
                        size="sm"
                        text="Fechar"
                        type="button"
                        @click="emit('close')"
                    />

                    <Button
                        v-if="actionText"
                        :variant="actionVariant"
                        size="sm"
                        :text="actionText"
                        :loading="loading"
                        type="button"
                        @click="handleActionClick"
                    />
                </div>
            </div>
        </div>
    </Teleport>
</template>
