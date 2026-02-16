<script setup>
import { useForm } from "@inertiajs/vue3";
import {
    FwbButton,
    FwbInput,
    FwbModal,
    FwbSelect,
    FwbTextarea,
} from "flowbite-vue";
import { GraduationCap, User } from "lucide-vue-next";
import { computed, watch } from "vue";

const props = defineProps({
    show: Boolean,
    subject: Object,
    courses: Array,
    teachers: Array,
});

const emit = defineEmits(["close"]);

const form = useForm({
    name: "",
    description: "",
    course_id: "",
    user_id: "",
});

const coursesOptions = computed(() =>
    props.courses.map((c) => ({ value: c.id, name: c.name })),
);

const teachersOptions = computed(() =>
    props.teachers.map((t) => ({ value: t.id, name: t.name })),
);

watch(
    () => props.subject,
    (val) => {
        if (val) {
            form.name = val.name;
            form.description = val.description;
            form.course_id = val.course_id;
            form.user_id = val.user_id;
        } else {
            form.reset();
        }
    },
    { immediate: true },
);

const submit = () => {
    if (props.subject) {
        form.transform((data) => ({ ...data, _method: "put" })).post(
            route("subject.update", props.subject.id),
            { onSuccess: () => closeModal() },
        );
    } else {
        form.post(route("subject.store"), { onSuccess: () => closeModal() });
    }
};

const closeModal = () => {
    form.reset();
    emit("close");
};
</script>

<template>
    <fwb-modal v-if="show" @close="closeModal" size="md" persistent>
        <template #body>
            <form @submit.prevent="submit" class="space-y-4">
                <fwb-input
                    v-model="form.name"
                    label="Nome da Disciplina"
                    :validation-status="form.errors.name ? 'error' : ''"
                >
                    <template v-if="form.errors.name" #validationMessage>
                        {{ form.errors.name }}
                    </template>
                </fwb-input>

                <div class="space-y-1">
                    <fwb-textarea
                        v-model="form.description"
                        label="Descrição *"
                        rows="3"
                        placeholder="Descrição da Disciplina"
                        :class="{
                            'border-red-500 focus:ring-red-500 focus:border-red-500':
                                form.errors.description,
                        }"
                        :validation-status="
                            form.errors.description ? 'error' : ''
                        "
                    />
                    <p
                        v-if="form.errors.description"
                        class="mt-1 text-sm text-red-600 dark:text-red-500 font-medium"
                    >
                        {{ form.errors.description }}
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-4 mt-4">
                    <div>
                        <fwb-select
                            v-model="form.course_id"
                            :options="coursesOptions"
                            label="Vincular ao Curso"
                            placeholder="Selecione o Curso"
                        >
                            <template #prefix
                                ><GraduationCap class="size-4"
                            /></template>
                        </fwb-select>
                        <p
                            v-if="form.errors.course_id"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.course_id }}
                        </p>
                    </div>

                    <div>
                        <fwb-select
                            v-model="form.user_id"
                            :options="teachersOptions"
                            label="Professor Responsável"
                            placeholder="Selecione o Professor"
                        >
                            <template #prefix><User class="size-4" /></template>
                        </fwb-select>
                        <p
                            v-if="form.errors.user_id"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.user_id }}
                        </p>
                    </div>
                </div>
            </form>
        </template>

        <template #footer>
            <div class="flex justify-end gap-3">
                <fwb-button color="alternative" @click="closeModal"
                    >Cancelar</fwb-button
                >
                <fwb-button
                    @click="submit"
                    class="!bg-primary-600 text-white"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing">Processando...</span>
                    <span v-else>{{
                        subject ? "Salvar Alterações" : "Criar Disciplina"
                    }}</span>
                </fwb-button>
            </div>
        </template>
    </fwb-modal>
</template>
