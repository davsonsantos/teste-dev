<script setup>
import Logo from "@/Components/Layout/Navigation/Logo.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { Send } from "lucide-vue-next";
import Button from "../../Components/Forms/Button.vue";
// Define o layout de forma persistente
defineOptions({ layout: AuthLayout });

const props = defineProps({
    email: String,
    referrer_id: [String, Number],
    inviter_name: String,
});
const form = useForm({
    name: "",
    email: props.email || "", // Inicializa com o email da prop
    referrer_id: props.referrer_id, // Guarda o ID do indicador oculto
    password: "",
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <section class="px-6 py-10 md:w-1/2 md:px-10 lg:p-16">
        <header class="mb-8 text-center sm:mt-0 md:text-left">
            <Logo />
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-4">
                Olá!
            </h2>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Você foi convidado por
                <span class="text-primary-600 font-bold">{{
                    inviter_name
                }}</span>
                para fazer parte da nossa plataforma.
            </p>
        </header>
        <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-1">
                <label for="name" class="inline-block text-sm font-medium">
                    Name
                </label>

                <input
                    v-model="form.name"
                    type="text"
                    id="name"
                    placeholder="Seu nome completo"
                    class="block w-full rounded-lg border border-gray-200 px-5 py-3 leading-6 placeholder-gray-500 focus:border-primary-500 focus:ring-3 focus:ring-primary-500/50 dark:border-gray-600 dark:bg-gray-800 dark:placeholder-gray-400 dark:focus:border-primary-500"
                    :class="{ 'border-red-500': form.errors.name }"
                />
                <p v-if="form.errors.name" class="text-xs text-red-500">
                    {{ form.errors.name }}
                </p>
            </div>
            <div class="space-y-1">
                <label for="email" class="inline-block text-sm font-medium"
                    >E-mail</label
                >
                <input
                    v-model="form.email"
                    type="email"
                    id="email"
                    readonly
                    class="block w-full rounded-lg border border-gray-200 bg-gray-100 px-3 py-2 text-gray-500 cursor-not-allowed focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800/50"
                    placeholder="Seu e-mail"
                />
                <p class="text-[10px] text-gray-400 italic">
                    * Este e-mail está vinculado ao seu convite e não pode ser
                    alterado.
                </p>
            </div>

            <div class="space-y-1">
                <label for="password" class="inline-block text-sm font-medium">
                    Senha
                </label>
                <input
                    v-model="form.password"
                    type="password"
                    id="password"
                    placeholder="Mínimo 8 caracteres"
                    class="block w-full rounded-lg border border-gray-200 px-5 py-3 leading-6 placeholder-gray-500 focus:border-primary-500 focus:ring-3 focus:ring-primary-500/50 dark:border-gray-600 dark:bg-gray-800 dark:placeholder-gray-400 dark:focus:border-primary-500"
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
                >
                    Confirmar Senha
                </label>
                <input
                    v-model="form.password_confirmation"
                    type="password"
                    id="password_confirmation"
                    class="block w-full rounded-lg border border-gray-200 px-5 py-3 leading-6 placeholder-gray-500 focus:border-primary-500 focus:ring-3 focus:ring-primary-500/50 dark:border-gray-600 dark:bg-gray-800 dark:placeholder-gray-400 dark:focus:border-primary-500"
                />
            </div>

            <div class="flex items-start flex-col">
                <div class="flex items-center">
                    <input
                        v-model="form.terms"
                        type="checkbox"
                        id="terms"
                        class="size-4 rounded-sm border border-gray-200 text-primary-500 checked:border-primary-500 focus:border-primary-500 focus:ring-3 focus:ring-primary-500/50 dark:border-gray-600 dark:bg-gray-800 dark:ring-offset-gray-900 dark:checked:border-transparent dark:checked:bg-primary-500 dark:focus:border-primary-500"
                    />
                    <span class="ml-2 text-sm font-medium">
                        Aceito os
                        <a href="#" class="underline"> termos e condições</a>
                    </span>
                </div>
                <p v-if="form.errors.terms" class="text-xs text-red-500 mt-1">
                    {{ form.errors.terms }}
                </p>
            </div>

            <div>
                <Button
                    text="Criar Conta"
                    :loading="form.processing"
                    :icon="Send"
                    class="w-full"
                    type="submit"
                />
                <div class="mt-5">
                    <Link
                        :href="route('login')"
                        class="inline-block text-sm font-medium text-primary-600 hover:text-primary-400 dark:text-primary-400 dark:hover:text-primary-300"
                    >
                        Já tem uma conta? Entrar
                    </Link>
                </div>
            </div>
        </form>
    </section>
</template>
