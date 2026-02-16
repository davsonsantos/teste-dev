<script setup>
import { computed } from "vue";

const props = defineProps({
    user: {
        type: Object,
        required: true,
        // Espera um objeto com { name: string, avatar?: string }
    },
    size: {
        type: String,
        default: "md", // xs, sm, md, lg, xl
    },
    border: {
        type: Boolean,
        default: true,
    },
});

// Mapeamento de tamanhos baseados no Tailkit
const sizeClasses = {
    xs: "size-8", // Extra Small
    sm: "size-10", // Small
    md: "size-12", // Normal
    lg: "size-16", // Large
    xl: "size-20", // Extra Large
};

const avatarUrl = computed(() => {
    // Validação rigorosa: se existir e não for string vazia
    if (props.user?.avatar && props.user.avatar !== "") {
        return props.user.avatar;
    }

    // Fallback para UI-Avatars com encode para segurança
    const name = encodeURIComponent(props.user?.name || "Usuario");
    return `https://ui-avatars.com/api/?name=${name}&background=random&color=fff&font-size=0.35`;
});

const currentSizeClass = computed(
    () => sizeClasses[props.size] || sizeClasses.md,
);
</script>

<template>
    <img
        :src="avatarUrl"
        :alt="user.name"
        class="inline-block rounded-full object-cover shadow-sm transition-transform duration-200"
        :class="[
            currentSizeClass,
            border ? 'border border-gray-100 dark:border-gray-700' : '',
        ]"
    />
</template>
