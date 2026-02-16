<script setup>
import FlashMessage from "@/Components/Global/FlashMessage.vue";
import Logo from "@/Components/Layout/Navigation/Logo.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { Send } from "lucide-vue-next";
import Button from "../../Components/Forms/Button.vue";
import TextInput from "../../Components/Forms/TextInput.vue";

defineOptions({ layout: AuthLayout });

const form = useForm({
    email: "",
    password: "",
    remember: false, // Campo para o "Remember me"
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <section class="px-6 py-10 md:w-1/2 md:px-10 lg:p-16">
        <header class="mb-8 text-center sm:mt-0 md:text-left">
            <Logo />
            <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Bem vindo de volta!
            </h2>
        </header>

        <FlashMessage />

        <form @submit.prevent="submit" class="space-y-6">
            <TextInput
                v-model="form.email"
                id="email"
                type="email"
                label="Email"
                placeholder="seu@email.com"
                :error="form.errors.email"
            />
            <TextInput
                v-model="form.password"
                id="password"
                type="password"
                label="Senha"
                placeholder="Sua senha"
                :error="form.errors.password"
            />
            <div>
                <div class="mb-5 flex items-center justify-between gap-2">
                    <label class="flex items-center">
                        <input
                            v-model="form.remember"
                            type="checkbox"
                            class="cursor-pointer size-4 rounded-sm border border-primary-600 text-primary-500 focus:ring-primary-500/50 dark:border-gray-600 dark:bg-gray-800"
                        />
                        <span class="ml-2 text-sm">Lembrar de mim</span>
                    </label>
                    <Link
                        :href="route('password.request')"
                        class="inline-block text-sm font-medium text-primary-600 hover:text-primary-400"
                    >
                        Esqueceu a senha?
                    </Link>
                </div>
                <Button
                    text="Entrar"
                    :loading="form.processing"
                    :icon="Send"
                    class="w-full"
                />
            </div>
        </form>

        <div class="mt-10 text-sm text-gray-500">
            Ainda não tem uma conta?
            <Link
                :href="route('register')"
                class="font-medium text-primary-600 hover:text-primary-400"
            >
                Cadastre-se
            </Link>
        </div>
    </section>
</template>
