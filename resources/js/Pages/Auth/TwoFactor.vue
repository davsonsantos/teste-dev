<script setup>
import Logo from "@/Components/Layout/Navigation/Logo.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { Loader2 } from "lucide-vue-next";
import { ref, watch } from "vue";

defineOptions({ layout: AuthLayout });

// Estado dos inputs individuais
const digits = ref(["", "", "", "", "", ""]);
const inputs = ref([]);

const form = useForm({
    code: "",
});

// Computa o código final
watch(
    digits,
    (newVal) => {
        form.code = newVal.join("");
    },
    { deep: true },
);

const handleSubmit = () => {
    if (form.code.length !== 6) return;

    form.post(route("two-factor.post"), {
        onFinish: () => {
            if (form.errors.code) digits.value = ["", "", "", "", "", ""];
        },
    });
};

const handleInput = (index, e) => {
    const val = e.target.value;
    if (val.length > 0 && index < 5) {
        inputs.value[index + 1].focus();
    }
    if (form.code.length === 6) handleSubmit();
};

const handleBackspace = (index, e) => {
    if (e.key === "Backspace" && !digits.value[index] && index > 0) {
        inputs.value[index - 1].focus();
    }
};

const handlePaste = (e) => {
    const paste = e.clipboardData.getData("text").slice(0, 6).split("");
    paste.forEach((char, i) => {
        if (i < 6) digits.value[i] = char;
    });
    handleSubmit();
};
</script>

<template>
    <section class="px-6 py-10 md:w-1/2 md:px-10 lg:p-16">
        <header class="mb-8 text-center md:text-left">
            <Logo />
            <h2 class="text-sm font-medium text-gray-500">
                Verificação de Segurança
            </h2>
            <p class="text-xs text-gray-400">
                Insira o código enviado ao seu e-mail.
            </p>
        </header>

        <form @submit.prevent="handleSubmit" class="space-y-6">
            <div class="flex justify-center gap-2 sm:gap-4">
                <input
                    v-for="(digit, i) in 6"
                    :key="i"
                    :ref="(el) => (inputs[i] = el)"
                    v-model="digits[i]"
                    type="text"
                    maxlength="1"
                    class="size-10 sm:size-12 text-center rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-3 focus:ring-primary-500/50 dark:bg-gray-900 dark:border-gray-700 text-lg font-bold"
                    @input="handleInput(i, $event)"
                    @keydown="handleBackspace(i, $event)"
                    @paste="handlePaste"
                />
            </div>

            <p v-if="form.errors.code" class="text-center text-xs text-red-500">
                {{ form.errors.code }}
            </p>

            <button
                type="submit"
                :disabled="form.processing || form.code.length < 6"
                class="w-full flex items-center justify-center gap-2 rounded-lg bg-primary-700 px-6 py-3 font-semibold text-white hover:bg-primary-600 disabled:opacity-50 transition-all"
            >
                <Loader2 v-if="form.processing" class="size-4 animate-spin" />
                <span>Verificar Código</span>
            </button>
        </form>
    </section>
</template>
