<script setup>
import Logo from "@/Components/Layout/Navigation/Logo.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { Send } from "lucide-vue-next";
import { ref } from "vue";
import Button from "../../Components/Forms/Button.vue";
import TextInput from "../../Components/Forms/TextInput.vue";
import Modal from "../../Components/Global/Modal.vue";

// Define o layout de forma persistente
defineOptions({ layout: AuthLayout });

const isTermsModalOpen = ref(false);
const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "", // Laravel espera password_confirmation
    terms: false,
});

const handleAcceptTerms = () => {
    form.terms = true; // Marca o checkbox automaticamente
    isTermsModalOpen.value = false; // Fecha o modal
};

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};

const confirmTerms = () => {
    console.error("Confirmou os termos");
};
</script>

<template>
    <section class="px-6 py-10 md:w-1/2 md:px-10 lg:p-16">
        <header class="mb-8 text-center sm:mt-0 md:text-left">
            <Logo />
            <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Crie sua conta hoje mesmo!
            </h2>
        </header>
        <form @submit.prevent="submit" class="space-y-6">
            <TextInput
                v-model="form.name"
                id="name"
                type="text"
                label="Nome Completo"
                placeholder="Nome Completo"
                :error="form.errors.name"
            />
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

            <TextInput
                v-model="form.password_confirmation"
                id="password_confirmation"
                type="password"
                label="Confirmar Senha"
                placeholder="Sua senha"
                :error="form.errors.password_confirmation"
            />

            <div class="flex items-start flex-col">
                <div class="flex items-center">
                    <input
                        v-model="form.terms"
                        type="checkbox"
                        id="terms"
                        class="size-4 rounded-sm border border-primary-400 text-primary-500 checked:border-primary-500 focus:border-primary-500 focus:ring-3 focus:ring-primary-500/50 dark:border-gray-600 dark:bg-gray-800 dark:ring-offset-gray-900 dark:checked:border-transparent dark:checked:bg-primary-500 dark:focus:border-primary-500"
                    />
                    <span class="ml-2 text-sm font-medium">
                        Aceito os
                        <a
                            href="#"
                            @click.prevent="isTermsModalOpen = true"
                            class="underline text-primary-600"
                            >termos e condições</a
                        >

                        <Modal
                            :show="isTermsModalOpen"
                            title="Termos de Uso"
                            action-variant="green"
                            @close="isTermsModalOpen = false"
                        >
                            <!-- @action="confirmTerms" -->
                            <!-- action-text="Aceitar Termos" -->
                            <template #body>
                                <p>Aqui vai o texto dos seus termos...</p>
                            </template>
                        </Modal>
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
                />

                <div class="mt-5">
                    Já tem uma conta?
                    <Link
                        :href="route('login')"
                        class="inline-block text-sm font-medium text-primary-600 hover:text-primary-400 dark:text-primary-400 dark:hover:text-primary-300"
                    >
                        Entrar
                    </Link>
                </div>
            </div>
        </form>
    </section>
</template>
