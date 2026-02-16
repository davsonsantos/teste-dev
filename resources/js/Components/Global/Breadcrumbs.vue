<script setup>
// Definindo o que o componente pode receber
const props = defineProps({
    title: {
        type: String,
        required: true // O título é obrigatório para o cabeçalho fazer sentido
    },
    description: {
        type: String,
        default: null // A descrição permanece opcional
    }
});
</script>

<template>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ props.title }}
        </h2>
        
        <p v-if="props.description" class="text-gray-500 dark:text-gray-400 mt-1">
            {{ props.description }}
        </p>
    </div>
</template>