<script setup>
import FlashMessage from "@/Components/Global/FlashMessage.vue";
import Logo from "@/Components/Layout/Navigation/Logo.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { Send } from "lucide-vue-next";
import Button from "../../Components/Forms/Button.vue";
import TextInput from "../../Components/Forms/TextInput.vue";

defineOptions({ layout: AuthLayout });

const form = useForm({ email: "" });

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <section class="px-6 py-10 md:w-1/2 md:px-10 lg:p-16">
        <header class="mb-8 text-center sm:mt-0 md:text-left">
            <Logo />
            <h2
                class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-4"
            >
                Esqueceu sua senha? Não se preocupe. Enviaremos um link de
                redefinição.
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

            <div>
                <Button
                    text="Enviar Link"
                    :loading="form.processing"
                    :icon="Send"
                    class="w-full"
                />

                <div class="mt-5">
                    <Link
                        :href="route('login')"
                        class="text-sm font-medium text-primary-600"
                    >
                        Voltar para o login
                    </Link>
                </div>
            </div>
        </form>
    </section>
</template>
