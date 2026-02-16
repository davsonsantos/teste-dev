<script setup>
import { useForm } from "@inertiajs/vue3";
import { FwbButton, FwbModal } from "flowbite-vue";
import { AlertTriangle, RotateCcw, Trash2 } from "lucide-vue-next";

const props = defineProps({
    show: Boolean,
    user: Object,
});

const emit = defineEmits(["close"]);
const form = useForm({});

const confirmDelete = () => {
    form.delete(route("users.destroy", props.user.id), {
        onSuccess: () => emit("close"),
        preserveScroll: true,
    });
};
</script>

<template>
    <fwb-modal v-if="show" @close="$emit('close')" size="md" persistent>
        <template #header>
            <div class="flex items-center gap-3 text-red-600">
                <AlertTriangle class="size-6" />
                <h3 class="text-xl font-bold">
                    {{ user?.deleted_at ? "Restaurar" : "Excluir" }} Usuário
                </h3>
            </div>
        </template>
        <template #body>
            <p class="text-gray-600 dark:text-gray-400">
                Tem certeza que deseja
                {{ user?.deleted_at ? "restaurar" : "excluir" }} o acesso de
                <strong class="text-gray-900 dark:text-white">{{
                    user?.name
                }}</strong
                >?
            </p>
        </template>
        <template #footer>
            <div class="flex justify-end gap-3">
                <fwb-button color="alternative" @click="$emit('close')"
                    >Cancelar</fwb-button
                >
                <fwb-button
                    @click="confirmDelete"
                    :class="user?.deleted_at ? '!bg-green-600' : '!bg-red-600'"
                    class="text-white"
                    :disabled="form.processing"
                >
                    <div class="flex items-center gap-2">
                        <component
                            :is="user?.deleted_at ? RotateCcw : Trash2"
                            class="size-4"
                        />
                        {{
                            user?.deleted_at ? "Sim, Restaurar" : "Sim, Excluir"
                        }}
                    </div>
                </fwb-button>
            </div>
        </template>
    </fwb-modal>
</template>
