<script setup>
import { useForm } from "@inertiajs/vue3";
import { FwbButton, FwbModal, FwbSelect } from "flowbite-vue";
import { ClipboardList, User, BookOpen, Activity } from "lucide-vue-next";
import { computed, watch } from "vue";

const props = defineProps({
    show: Boolean,
    enrollment: Object, // Se for null, vira modal de Criação. Se tiver dados, vira Edição.
    students: {
        type: Array,
        default: () => [],
    },
    courses: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(["close"]);

// O status padrão de uma nova matrícula é 'pendente'
const form = useForm({
    user_id: "",
    course_id: "",
    status: "pendente",
});

// Mapeamento de opções para o fwb-select
const studentOptions = computed(() =>
    props.students.map((s) => ({ value: s.id, name: s.name }))
);

const courseOptions = computed(() =>
    props.courses.map((c) => ({ value: c.id, name: c.name }))
);

const statusOptions = [
    { value: "pendente", name: "Pendente" },
    { value: "cursando", name: "Cursando" },
    { value: "concluido", name: "Concluído" },
    { value: "cancelado", name: "Cancelado" },
];

// Reatividade: Preenche o formulário se estiver editando, ou reseta se for novo
watch(
    () => props.enrollment,
    (val) => {
        if (val) {
            form.user_id = val.user_id;
            form.course_id = val.course_id;
            form.status = val.status;
        } else {
            form.reset();
        }
    },
    { immediate: true }
);

const submit = () => {
    if (props.enrollment) {
        // Fluxo de Atualização
        form.put(route("enrollments.update", props.enrollment.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        // Fluxo de Criação
        form.post(route("enrollments.store"), {
            onSuccess: () => closeModal(),
        });
    }
};

const closeModal = () => {
    form.reset();
    form.clearErrors(); // Limpa erros residuais do Inertia
    emit("close");
};
</script>

<template>
    <fwb-modal v-if="show" @close="closeModal" size="md" persistent>
        <template #header>
            <div class="flex items-center gap-3">
                <div class="p-2 bg-primary-100 rounded-lg dark:bg-primary-900/30">
                    <ClipboardList class="size-5 text-primary-600" />
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ enrollment ? "Editar Matrícula" : "Nova Matrícula" }}
                </h3>
            </div>
        </template>

        <template #body>
            <form @submit.prevent="submit" class="space-y-4">
                
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Selecionar Aluno *
                    </label>
                    <fwb-select
                        v-model="form.user_id"
                        :options="studentOptions"
                        placeholder="Busque e selecione o aluno"
                        :class="{'border-red-500 focus:ring-red-500': form.errors.user_id}"
                        :disabled="!!enrollment" 
                    >
                        <template #prefix><User class="size-4" /></template>
                    </fwb-select>
                    <p v-if="enrollment" class="mt-1 text-xs text-amber-600">
                        Não é possível alterar o aluno de uma matrícula existente.
                    </p>
                    <p v-if="form.errors.user_id" class="mt-1 text-xs text-red-600 font-medium">
                        {{ form.errors.user_id }}
                    </p>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Selecionar Curso *
                    </label>
                    <fwb-select
                        v-model="form.course_id"
                        :options="courseOptions"
                        placeholder="Selecione o curso desejado"
                        :class="{'border-red-500 focus:ring-red-500': form.errors.course_id}"
                        :disabled="!!enrollment"
                    >
                        <template #prefix><BookOpen class="size-4" /></template>
                    </fwb-select>
                    <p v-if="form.errors.course_id" class="mt-1 text-xs text-red-600 font-medium">
                        {{ form.errors.course_id }}
                    </p>
                </div>

                <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Status da Matrícula *
                    </label>
                    <fwb-select
                        v-model="form.status"
                        :options="statusOptions"
                        :class="{'border-red-500 focus:ring-red-500': form.errors.status}"
                    >
                        <template #prefix><Activity class="size-4" /></template>
                    </fwb-select>
                    <p v-if="form.errors.status" class="mt-1 text-xs text-red-600 font-medium">
                        {{ form.errors.status }}
                    </p>
                </div>

            </form>
        </template>

        <template #footer>
            <div class="flex justify-end gap-3">
                <fwb-button color="alternative" @click="closeModal">
                    Cancelar
                </fwb-button>
                <fwb-button
                    @click="submit"
                    class="!bg-primary-600 hover:!bg-primary-700 text-white font-bold"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing">Processando...</span>
                    <span v-else>{{ enrollment ? "Salvar Alterações" : "Confirmar Matrícula" }}</span>
                </fwb-button>
            </div>
        </template>
    </fwb-modal>
</template>