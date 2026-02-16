<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { Loader2, LockKeyhole } from "lucide-vue-next";
import { computed } from "vue";

const page = usePage();
const user = computed(() => page.props.auth.user);

const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.patch(route("profile.password.update"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
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
                <LockKeyhole
                    class="size-5 text-primary-600 dark:text-primary-400"
                />
                <span>Senha</span>
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Mantenha sua conta segura alterando sua senha.
            </p>
        </div>
        <div
            class="grow rounded-lg bg-white p-5 shadow-xs shadow-gray-300/25 dark:bg-gray-900 dark:shadow-none"
        >
            <form class="space-y-5" @submit.prevent="submit">
                <div class="space-y-1">
                    <label
                        for="current_password"
                        class="inline-block text-sm font-medium"
                        >Senha Atual</label
                    >
                    <input
                        v-model="form.current_password"
                        type="password"
                        id="current_password"
                        name="current_password"
                        class="block w-full rounded-lg border border-gray-200 px-3 py-2 text-sm leading-6 focus:border-primary-500 focus:ring-3 focus:ring-primary-500/50 dark:border-gray-700 dark:bg-gray-900 dark:focus:border-primary-500"
                        :class="
                            form.errors.current_password
                                ? 'border-red-500 focus:border-red-500'
                                : 'border-gray-200 dark:border-gray-800 focus:border-primary-500'
                        "
                    />
                    <p
                        v-if="form.errors.current_password"
                        class="text-xs text-red-500 mt-1 font-medium"
                    >
                        {{ form.errors.current_password }}
                    </p>
                </div>
                <div class="space-y-1">
                    <label
                        for="new_password"
                        class="inline-block text-sm font-medium"
                        >Nova Senha</label
                    >
                    <input
                        v-model="form.password"
                        type="password"
                        id="password"
                        name="password"
                        class="block w-full rounded-lg border border-gray-200 px-3 py-2 text-sm leading-6 focus:border-primary-500 focus:ring-3 focus:ring-primary-500/50 dark:border-gray-700 dark:bg-gray-900 dark:focus:border-primary-500"
                        :class="
                            form.errors.password
                                ? 'border-red-500 focus:border-red-500'
                                : 'border-gray-200 dark:border-gray-800 focus:border-primary-500'
                        "
                    />
                    <p
                        v-if="form.errors.password"
                        class="text-xs text-red-500 mt-1 font-medium"
                    >
                        {{ form.errors.password }}
                    </p>
                </div>
                <div class="space-y-1">
                    <label
                        for="confirm_password"
                        class="inline-block text-sm font-medium"
                        >Confirme a senha</label
                    >
                    <input
                        v-model="form.password_confirmation"
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="block w-full rounded-lg border border-gray-200 px-3 py-2 text-sm leading-6 focus:border-primary-500 focus:ring-3 focus:ring-primary-500/50 dark:border-gray-700 dark:bg-gray-900 dark:focus:border-primary-500"
                        :class="
                            form.errors.password_confirmation
                                ? 'border-red-500 focus:border-red-500'
                                : 'border-gray-200 dark:border-gray-800 focus:border-primary-500'
                        "
                    />
                    <p
                        v-if="form.errors.password_confirmation"
                        class="text-xs text-red-500 mt-1 font-medium"
                    >
                        {{ form.errors.password_confirmation }}
                    </p>
                </div>
                <button
                    type="submit"
                    :disabled="form.processing || !form.isDirty"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                >
                    <Loader2
                        v-if="form.processing"
                        class="size-4 animate-spin"
                    />
                    <span>{{
                        form.processing ? "Salvando..." : "Atualizar Senha"
                    }}</span>
                </button>
                <p
                    v-if="form.isDirty"
                    class="mt-2 text-[10px] text-gray-400 uppercase tracking-wider font-bold animate-pulse"
                >
                    * Alterações não salvas
                </p>
            </form>
        </div>
    </div>
</template>
