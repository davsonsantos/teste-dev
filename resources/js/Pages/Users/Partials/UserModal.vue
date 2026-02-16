<script setup>
import { useForm } from "@inertiajs/vue3";
import {
    FwbButton,
    FwbCheckbox,
    FwbInput,
    FwbModal,
    FwbSelect,
} from "flowbite-vue";
import { Save, UserPlus } from "lucide-vue-next";
import { watch } from "vue";

const props = defineProps({
    show: Boolean,
    user: Object,
    roles: Array,
});

const emit = defineEmits(["close"]);

// Formulário reativo do Inertia
const form = useForm({
    name: "",
    email: "",
    role: "",
    birth_date: "",
    is_active: true,
    password: "", // Apenas para novos usuários
});

// Atualiza o formulário se um usuário for passado para edição
watch(
    () => props.user,
    (val) => {
        if (val) {
            form.name = val.name;
            form.email = val.email;
            form.role = val.role;
            form.birdth_date = val.birth_date;
            form.is_active = val.is_active;
        } else {
            form.reset();
        }
    },
    { immediate: true },
);

const submit = () => {
    if (props.user) {
        form.transform((data) => ({
            ...data,
            _method: "put",
        })).post(route("users.update", props.user.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route("users.store"), {
            onSuccess: () => closeModal(),
        });
    }
};

const closeModal = () => {
    form.reset();
    emit("close");
};

const roleOptions = [
    { value: "admin", name: "Administrador" },
    { value: "teacher", name: "Professor" },
    { value: "student", name: "Aluno" },
];
</script>

<template>
    <fwb-modal v-if="show" @close="closeModal" size="md" persistent>
        <template #header>
            <div class="flex items-center gap-3">
                <div
                    class="p-2 bg-primary-100 rounded-lg dark:bg-primary-900/30"
                >
                    <UserPlus v-if="!user" class="size-5 text-primary-600" />
                    <Save v-else class="size-5 text-primary-600" />
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ user ? "Editar Usuário" : "Novo Usuário" }}
                </h3>
            </div>
        </template>

        <template #body>
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <fwb-input
                        v-model="form.name"
                        label="Nome Completo"
                        placeholder="Ex: João Silva"
                        :validation-status="form.errors.name ? 'error' : ''"
                    >
                        <template #validationMessage>{{
                            form.errors.name
                        }}</template>
                    </fwb-input>
                </div>

                <div>
                    <fwb-input
                        v-model="form.email"
                        label="E-mail"
                        type="email"
                        placeholder="joao@empresa.com"
                        :validation-status="form.errors.email ? 'error' : ''"
                    >
                        <template #validationMessage>{{
                            form.errors.email
                        }}</template>
                    </fwb-input>
                </div>

                <div>
                    <fwb-input
                        v-model="form.birth_date"
                        label="Data de Nascimento"
                        type="date"
                        :validation-status="
                            form.errors.birth_date ? 'error' : ''
                        "
                    >
                        <template #validationMessage>{{
                            form.errors.birth_date
                        }}</template>
                    </fwb-input>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <fwb-select
                        v-model="form.role"
                        :options="roleOptions"
                        label="Tipo de Usuário"
                        placeholder="Selecione um tipo de usuário"
                    />
                    <div class="flex items-end pb-3">
                        <fwb-checkbox
                            v-model="form.is_active"
                            label="Usuário Ativo"
                        />
                    </div>
                </div>

                <div v-if="!user || showPasswordChange">
                    <fwb-input
                        v-model="form.password"
                        :label="
                            user
                                ? 'Nova Senha (deixe em branco para manter)'
                                : 'Senha Provisória'
                        "
                        type="password"
                        :validation-status="form.errors.password ? 'error' : ''"
                    >
                        <template #validationMessage>{{
                            form.errors.password
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
                    class="!bg-primary-600 hover:!bg-primary-700"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing">Processando...</span>
                    <span v-else>{{
                        user ? "Salvar Alterações" : "Criar Usuário"
                    }}</span>
                </fwb-button>
            </div>
        </template>
    </fwb-modal>
</template>
