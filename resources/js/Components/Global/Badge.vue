<script setup>
import { computed } from "vue";

const props = defineProps({
    variant: { type: String, default: "gray" }, // success, danger, warning, info, primary, gray
    // Mudamos para aceitar tanto Objetos quanto Funções
    icon: { type: [Object, Function], default: null },
});

const variantClasses = {
    success:
        "bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400",
    danger: "bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400",
    warning:
        "bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400",
    info: "bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400",
    primary:
        "bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400",
    gray: "bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300",
};

const currentClass = computed(
    () => variantClasses[props.variant] || variantClasses.gray,
);
</script>

<template>
    <span
        :class="[
            'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider w-fit',
            currentClass,
        ]"
    >
        <component v-if="icon" :is="icon" class="size-3" />
        <slot />
    </span>
</template>
