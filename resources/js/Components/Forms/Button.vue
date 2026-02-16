<script setup>
import { computed } from "vue";

const props = defineProps({
    type: { type: String, default: "submit" },
    text: String,
    loading: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    variant: { type: String, default: "primary" }, // primary, danger, success, etc.
    icon: [Object, Function], // Para passar componentes de ícone (ex: Lucide ou Heroicons)
    iconPosition: { type: String, default: "left" }, // left ou right
    size: { type: String, default: "md" }, // xs, sm, md, lg, xl
});

const sizeClasses = computed(() => {
    const sizes = {
        xs: "px-3 py-1.5 text-xs",
        sm: "px-4 py-2 text-sm",
        md: "px-6 py-3 text-base",
        lg: "px-8 py-4 text-lg",
    };
    return sizes[props.size] || sizes.md;
});
// Mapeamento de cores para manter o padrão visual
const variantClasses = computed(() => {
    const variants = {
        red: "border-red-600 bg-red-600 hover:bg-red-500 text-white",
        orange: "border-orange-500 bg-orange-500 hover:bg-orange-400 text-white",
        gray: "border-gray-500 bg-gray-500 hover:bg-gray-400 text-white",
        green: "border-green-600 bg-green-600 hover:bg-green-500 text-white",
        blue: "border-blue-600 bg-blue-600 hover:bg-blue-500 text-white",
        white: "border-gray-300 bg-white hover:bg-gray-100 text-gray-900",
    };

    // Retorna as classes da cor escolhida ou o padrão do sistema (primary)
    return (
        variants[props.variant] ||
        "border-primary-700 bg-primary-700 hover:bg-primary-600 text-white"
    );
});
</script>

<template>
    <button
        :type="type"
        :disabled="loading || disabled"
        class="inline-flex items-center justify-center gap-2 rounded-lg border px-6 py-3 font-semibold transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
        :class="[variantClasses, sizeClasses]"
    >
        <svg
            v-if="loading"
            class="animate-spin h-5 w-5"
            :class="variant === 'secondary' ? 'text-gray-500' : 'text-white'"
            viewBox="0 0 24 24"
        >
            <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
            ></circle>
            <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
            ></path>
        </svg>

        <component
            v-if="icon && iconPosition === 'left' && !loading"
            :is="icon"
            class="size-5"
        />

        <span>{{ text }}</span>

        <component
            v-if="icon && iconPosition === 'right' && !loading"
            :is="icon"
            class="size-5"
        />
    </button>
</template>
