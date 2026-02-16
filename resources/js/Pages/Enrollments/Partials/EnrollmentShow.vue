<script setup>
import { FwbModal, FwbButton } from "flowbite-vue";
import { User, BookOpen, Activity, CheckCircle } from "lucide-vue-next";

defineProps({
    show: Boolean,
    enrollment: Object
});

defineEmits(["close"]);

// Função auxiliar para pintar o card de status dinamicamente
const getStatusColors = (status) => {
    const colors = {
        cursando: 'bg-blue-50 text-blue-800 border-blue-200 dark:bg-blue-900/20 dark:border-blue-800 dark:text-blue-300',
        pendente: 'bg-amber-50 text-amber-800 border-amber-200 dark:bg-amber-900/20 dark:border-amber-800 dark:text-amber-300',
        concluido: 'bg-green-50 text-green-800 border-green-200 dark:bg-green-900/20 dark:border-green-800 dark:text-green-300',
        cancelado: 'bg-red-50 text-red-800 border-red-200 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300'
    };
    return colors[status] || 'bg-gray-50 text-gray-800 border-gray-200';
};
</script>

<template>
    <fwb-modal v-if="show && enrollment" @close="$emit('close')" size="md" persistent>
        <template #header>
            <div class="flex items-center gap-3">
                <div class="p-2 bg-primary-100 rounded-lg dark:bg-primary-900/30">
                    <Activity class="size-5 text-primary-600" />
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    Detalhes da Matrícula
                </h3>
            </div>
        </template>

        <template #body>
            <div class="space-y-4">
                <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="p-3 bg-white dark:bg-gray-700 rounded-full shadow-sm">
                        <User class="size-6 text-gray-500 dark:text-gray-400"/>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-bold tracking-wider mb-0.5">Aluno Matriculado</p>
                        <p class="font-bold text-gray-900 dark:text-white">{{ enrollment.student?.name }}</p>
                        <p class="text-xs text-gray-500">{{ enrollment.student?.email }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="p-3 bg-white dark:bg-gray-700 rounded-full shadow-sm">
                        <BookOpen class="size-6 text-primary-500"/>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-bold tracking-wider mb-0.5">Curso / Disciplina</p>
                        <p class="font-bold text-gray-900 dark:text-white">{{ enrollment.course?.name }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 border rounded-xl text-center flex flex-col items-center justify-center transition-colors" :class="getStatusColors(enrollment.status)">
                        <p class="text-[10px] uppercase font-bold opacity-70 mb-1">Status Atual</p>
                        <p class="font-black uppercase text-sm tracking-wider">{{ enrollment.status }}</p>
                    </div>

                    <div class="p-4 border border-gray-100 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800/50 text-center">
                        <p class="text-[10px] text-gray-500 uppercase font-bold mb-1">Data da Matrícula</p>
                        <p class="font-bold text-sm text-gray-900 dark:text-white">{{ new Date(enrollment.created_at).toLocaleDateString() }}</p>
                    </div>
                </div>

                <div v-if="enrollment.status === 'concluido' && enrollment.completed_at" class="p-4 border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 rounded-xl text-center flex flex-col items-center">
                    <p class="text-[10px] text-green-600 dark:text-green-400 uppercase font-bold mb-1 flex items-center gap-1">
                        <CheckCircle class="size-3" /> Concluído em
                    </p>
                    <p class="font-bold text-sm text-green-800 dark:text-green-300">{{ new Date(enrollment.completed_at).toLocaleDateString() }}</p>
                </div>
            </div>
        </template>

        <template #footer>
            <div class="flex justify-end w-full">
                <fwb-button color="alternative" @click="$emit('close')">Fechar Janela</fwb-button>
            </div>
        </template>
    </fwb-modal>
</template>