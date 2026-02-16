<script setup>
import Logo from "@/Components/Layout/Navigation/Logo.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { useForm } from "@inertiajs/vue3";

defineOptions({ layout: AuthLayout });

// Recebe os dados enviados pelo Controller
const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: "",
    password_confirmation: "",
});

const submit = () => {
    // Aponta para a rota de update de senha (geralmente password.update)
    form.post(route("password.update"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <section class="px-6 py-10 md:w-1/2 md:px-10 lg:p-16">
        <header class="mb-8 text-center sm:mt-0 md:text-left">
            <Logo />
            <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Olá {{ form.email }}, informe sua nova senha abaixo.
            </h2>
        </header>

        <form @submit.prevent="submit" class="space-y-6">
            <input type="hidden" v-model="form.token" />

            <div class="space-y-1">
                <label for="email" class="inline-block text-sm font-medium"
                    >Email</label
                >
                <input
                    v-model="form.email"
                    type="email"
                    id="email"
                    readonly
                    class="block w-full rounded-lg border border-gray-200 bg-gray-100 px-5 py-3 dark:border-gray-600 dark:bg-gray-700 cursor-not-allowed"
                />
            </div>

            <div class="space-y-1">
                <label for="password" class="inline-block text-sm font-medium"
                    >Nova Senha</label
                >
                <input
                    v-model="form.password"
                    type="password"
                    id="password"
                    placeholder="Mínimo 8 caracteres"
                    class="block w-full rounded-lg border border-gray-200 px-5 py-3 focus:border-primary-500 focus:ring-3 focus:ring-primary-500/50 dark:border-gray-600 dark:bg-gray-800"
                    :class="{ 'border-red-500': form.errors.password }"
                />
                <p v-if="form.errors.password" class="text-xs text-red-500">
                    {{ form.errors.password }}
                </p>
            </div>

            <div class="space-y-1">
                <label
                    for="password_confirmation"
                    class="inline-block text-sm font-medium"
                    >Confirmar Nova Senha</label
                >
                <input
                    v-model="form.password_confirmation"
                    type="password"
                    id="password_confirmation"
                    class="block w-full rounded-lg border border-gray-200 px-5 py-3 focus:border-primary-500 focus:ring-3 focus:ring-primary-500/50 dark:border-gray-600 dark:bg-gray-800"
                />
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="cursor-pointer inline-flex w-full items-center justify-center gap-2 rounded-lg bg-primary-700 px-6 py-3 font-semibold text-white hover:bg-primary-600 disabled:opacity-50"
            >
                Redefinir Senha
            </button>
        </form>
    </section>
</template>
