<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import {
    ArrowRight,
    CheckCircle2,
    Loader2,
    ShieldCheck,
    Smartphone,
} from "lucide-vue-next";
import { computed } from "vue";
import { toast } from "vue-sonner";

const page = usePage();
// Assumindo que o campo venha no objeto user
const user = computed(() => page.props.auth.user);

const form = useForm({
    enable_2fa: user.value.enable_2fa ?? false,
});

const toggle2FA = () => {
    // Inverte o estado atual
    form.enable_2fa = !form.enable_2fa;

    form.patch(route("profile.2fa.update"), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success(
                form.enable_2fa
                    ? "Autenticação em dois fatores ativada!"
                    : "Autenticação em dois fatores desativada!",
            );
        },
        onError: () => {
            // Reverte em caso de erro
            form.enable_2fa = user.value.enable_2fa;
            toast.error("Erro ao atualizar segurança.");
        },
    });
};
</script>

<template>
    <div
        class="flex flex-col rounded-xl bg-gray-100/75 p-2 ring ring-gray-200/50 dark:bg-gray-800/35 dark:ring-gray-800/60"
    >
        <div class="px-4 pt-2 pb-4">
            <h2 class="flex items-center gap-2 font-medium">
                <ShieldCheck
                    class="size-5 text-primary-600 dark:text-primary-400"
                />
                <span>Segurança em Duas Etapas</span>
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Adicione uma camada extra de segurança.
            </p>
        </div>

        <div
            class="grow rounded-lg bg-white p-5 shadow-xs shadow-gray-300/25 dark:bg-gray-900 dark:shadow-none"
        >
            <div class="space-y-5">
                <div class="flex items-start gap-3">
                    <div class="flex-none">
                        <div
                            class="flex size-10 items-center justify-center rounded-lg bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400"
                        >
                            <Smartphone
                                class="size-5 text-gray-600 dark:text-gray-400"
                            />
                        </div>
                    </div>
                    <div class="grow">
                        <div
                            class="flex items-center gap-2 text-sm font-medium"
                        >
                            App de Autenticação
                            <span
                                v-if="form.enable_2fa"
                                class="flex items-center gap-1 text-[10px] text-green-500 font-bold uppercase"
                            >
                                <CheckCircle2 class="size-3" /> Ativo
                            </span>
                        </div>
                        <p
                            class="mt-1 text-xs font-medium text-gray-600 dark:text-gray-400"
                        >
                            Receba um código de autenticação via E-mail para
                            login no sistema.
                        </p>

                        <button
                            @click="toggle2FA"
                            :disabled="form.processing"
                            type="button"
                            class="mt-3 inline-flex items-center justify-center gap-2 rounded-lg border px-3 py-1.5 text-sm font-semibold transition-all disabled:opacity-50"
                            :class="
                                form.enable_2fa
                                    ? 'border-red-200 bg-red-50 text-red-700 hover:bg-red-100 dark:border-red-900/30 dark:bg-red-900/20 dark:text-red-400'
                                    : 'border-gray-200 bg-white text-gray-800 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300'
                            "
                        >
                            <Loader2
                                v-if="form.processing"
                                class="size-3 animate-spin"
                            />
                            <span>{{
                                form.enable_2fa ? "Desativar" : "Ativar Agora"
                            }}</span>
                            <ArrowRight
                                v-if="!form.processing"
                                class="size-4"
                            />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
