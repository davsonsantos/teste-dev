<script setup>
import { useForm } from "@inertiajs/vue3";
import { FwbButton, FwbInput, FwbModal, FwbTextarea } from "flowbite-vue";
import { BookPlus, Calendar, Save } from "lucide-vue-next";
import { watch } from "vue";

const props = defineProps({
    show: Boolean,
    course: Object, // Alterado de user para course
});

const emit = defineEmits(["close"]);

// Formulário reativo ajustado para os campos da migration
const form = useForm({
    name: "",
    description: "",
    start_date: "",
    end_date: "",
});

// Watcher para preencher o formulário em caso de edição
watch(
    () => props.course,
    (val) => {
        if (val) {
            form.name = val.name;
            form.description = val.description;
            form.start_date = val.start_date;
            form.end_date = val.end_date;
        } else {
            form.reset();
        }
    },
    { immediate: true },
);

const submit = () => {
    if (props.course) {
        form.transform((data) => ({
            ...data,
            _method: "put",
        })).post(route("course.update", props.course.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route("course.store"), {
            onSuccess: () => closeModal(),
        });
    }
};

const closeModal = () => {
    form.reset();
    emit("close");
};
</script>

<template>
    <fwb-modal v-if="show" @close="closeModal" size="md" persistent>
        <template #header>
            <div class="flex items-center gap-3">
                <div
                    class="p-2 bg-primary-100 rounded-lg dark:bg-primary-900/30"
                >
                    <BookPlus v-if="!course" class="size-5 text-primary-600" />
                    <Save v-else class="size-5 text-primary-600" />
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ course ? "Editar Curso" : "Novo Curso" }}
                </h3>
            </div>
        </template>

        <template #body>
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <fwb-input
                        v-model="form.name"
                        label="Nome do Curso"
                        placeholder="Ex: Laravel Avançado"
                        :validation-status="form.errors.name ? 'error' : ''"
                    >
                        <template #validationMessage>{{
                            form.errors.name
                        }}</template>
                    </fwb-input>
                </div>

                <div>
                    <fwb-textarea
                        v-model="form.description"
                        label="Descrição"
                        placeholder="Detalhes sobre o conteúdo do curso..."
                        rows="3"
                        :validation-status="
                            form.errors.description ? 'error' : ''
                        "
                    >
                        <template #validationMessage>{{
                            form.errors.description
                        }}</template>
                    </fwb-textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <fwb-input
                        v-model="form.start_date"
                        label="Data de Início"
                        type="date"
                        :validation-status="
                            form.errors.start_date ? 'error' : ''
                        "
                    >
                        <template #prefix>
                            <Calendar class="size-4 text-gray-400" />
                        </template>
                        <template #validationMessage>{{
                            form.errors.start_date
                        }}</template>
                    </fwb-input>

                    <fwb-input
                        v-model="form.end_date"
                        label="Data de Término"
                        type="date"
                        :validation-status="form.errors.end_date ? 'error' : ''"
                    >
                        <template #prefix>
                            <Calendar class="size-4 text-gray-400" />
                        </template>
                        <template #validationMessage>{{
                            form.errors.end_date
                        }}</template>
                    </fwb-input>
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
                    <span v-else>{{
                        course ? "Salvar Alterações" : "Criar Curso"
                    }}</span>
                </fwb-button>
            </div>
        </template>
    </fwb-modal>
</template>
