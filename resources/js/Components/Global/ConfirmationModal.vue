<script setup>
import { FwbModal } from "flowbite-vue";
import { AlertTriangle, CheckCircle, LogOut, Trash2 } from "lucide-vue-next";
import { watch } from "vue";

const props = defineProps({
    show: Boolean,
    title: String,
    message: String,
    confirmLabel: { type: String, default: "Confirmar" },
    variant: { type: String, default: "danger" }, // danger, warning
    loading: Boolean,
    success: Boolean,
});

const emit = defineEmits(["close", "confirm"]);

const iconMap = {
    danger: Trash2,
    warning: LogOut,
    success: CheckCircle,
};

// Autoclose após 1.5s ao detectar sucesso
watch(
    () => props.success,
    (val) => {
        if (val) {
            setTimeout(() => {
                emit("close");
            }, 1500);
        }
    },
);
</script>

<template>
    <fwb-modal
        v-if="show"
        @close="!loading && !success && $emit('close')"
        size="md"
        not-escapable
        class="rounded-3xl shadow-2xl overflow-hidden"
    >
        <template #header>
            <div v-if="!success" class="flex items-center gap-3">
                <div
                    class="p-2 bg-white rounded-xl shadow-sm border border-gray-100"
                    :class="
                        variant === 'danger' ? 'text-red-600' : 'text-amber-500'
                    "
                >
                    <AlertTriangle class="size-6" />
                </div>
                <h3 class="text-lg font-bold tracking-tight text-gray-900">
                    {{ title }}
                </h3>
            </div>
            <div v-else class="flex items-center gap-3 text-green-600">
                <CheckCircle class="size-6" />
                <h3 class="text-lg font-bold tracking-tight">Sucesso</h3>
            </div>
        </template>

        <template #body>
            <div
                v-if="success"
                class="py-6 flex flex-col items-center text-center animate-in fade-in zoom-in duration-500"
            >
                <div
                    class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-4"
                >
                    <CheckCircle class="size-12 animate-pulse" />
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">Concluído!</h3>
                <p class="text-gray-500 text-sm">
                    Operação realizada com sucesso.
                </p>
            </div>

            <div v-else class="py-2">
                <p
                    class="text-gray-600 text-sm leading-relaxed"
                    v-html="message"
                ></p>
            </div>
        </template>

        <template #footer>
            <div v-if="!success" class="flex justify-end gap-3 w-full">
                <button
                    @click="$emit('close')"
                    :disabled="loading"
                    class="px-5 py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors disabled:opacity-50"
                >
                    Cancelar
                </button>

                <button
                    @click="$emit('confirm')"
                    :disabled="loading"
                    class="relative flex items-center justify-center gap-2 min-w-[140px] px-6 py-2.5 text-sm font-bold text-white rounded-2xl transition-all shadow-lg active:scale-95 disabled:cursor-not-allowed disabled:opacity-80"
                    :class="
                        variant === 'danger'
                            ? 'bg-red-600 hover:bg-red-700'
                            : 'bg-amber-500 hover:bg-amber-600'
                    "
                >
                    <div v-if="loading" class="btn-loader"></div>

                    <component
                        :is="iconMap[variant]"
                        v-if="!loading"
                        class="size-4"
                    />

                    <span :class="{ 'opacity-0': loading }">{{
                        confirmLabel
                    }}</span>
                    <span
                        v-if="loading"
                        class="absolute inset-0 flex items-center justify-center"
                    >
                        Processando...
                    </span>
                </button>
            </div>
        </template>
    </fwb-modal>
</template>

<style scoped>
/* Mantivemos o seu loader customizado para manter a identidade */
.btn-loader {
    width: 18px;
    height: 18px;
    border: 2px solid #ffffff60;
    border-bottom-color: white;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
}

@keyframes rotation {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
