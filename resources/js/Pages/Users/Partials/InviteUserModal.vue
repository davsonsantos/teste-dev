<script setup>
import { useForm } from "@inertiajs/vue3";
import { FwbButton, FwbInput, FwbModal } from "flowbite-vue";
import { Send, Mail as MailIcon } from "lucide-vue-next";

const props = defineProps({ show: Boolean });
const emit = defineEmits(["close"]);

const form = useForm({ email: "" });

const submit = () => {
    form.post(route("users.invite"), {
        onSuccess: () => {
            form.reset();
            emit("close");
        },
    });
};
</script>

<template>
    <fwb-modal v-if="show" @close="emit('close')" size="md" persistent>
        <template #header>
            <div class="flex items-center gap-3">
                <div class="p-2 bg-primary-100 rounded-lg dark:bg-primary-900/30">
                    <Send class="size-5 text-primary-600" />
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Convidar Usuário</h3>
            </div>
        </template>
        <template #body>
            <p class="mb-4 text-sm text-gray-500">
                Enviaremos um link exclusivo e seguro para o e-mail abaixo. O link expirará em 24 horas.
            </p>
            <form @submit.prevent="submit">
                <fwb-input
                    v-model="form.email"
                    label="E-mail do convidado"
                    placeholder="email@exemplo.com"
                    type="email"
                    :validation-status="form.errors.email ? 'error' : ''"
                >
                    <template #prefix><MailIcon class="size-4 text-gray-400" /></template>
                    <template #validationMessage>{{ form.errors.email }}</template>
                </fwb-input>
            </form>
        </template>
        <template #footer>
            <div class="flex justify-end gap-3">
                <fwb-button color="alternative" @click="emit('close')">Cancelar</fwb-button>
                <fwb-button @click="submit" class="!bg-primary-600" :disabled="form.processing">
                    {{ form.processing ? 'Enviando...' : 'Enviar Convite' }}
                </fwb-button>
            </div>
        </template>
    </fwb-modal>
</template>