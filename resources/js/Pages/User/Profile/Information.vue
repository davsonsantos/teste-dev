<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { CheckCircle2, CircleUserRound, Loader2 } from "lucide-vue-next";
import { computed } from "vue";

const page = usePage();
const user = computed(() => page.props.auth.user);

const form = useForm({
    id: user.value.id,
    name: user.value.name,
    email: user.value.email,
    document: user.value.document,
});

const submit = () => {
    form.patch(route("profile.information.update"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <div
        class="flex flex-col rounded-xl bg-gray-100/75 p-2 ring ring-gray-200/50 dark:bg-gray-800/35 dark:ring-gray-800/60"
    >
        <div class="px-4 pt-2 pb-4 flex items-center justify-between">
            <h2
                class="flex items-center gap-2 font-medium text-gray-700 dark:text-gray-200"
            >
                <CircleUserRound
                    class="size-5 text-primary-600 dark:text-primary-400"
                />
                <span>Informações Pessoais</span>
            </h2>

            <transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="opacity-0 translate-y-1"
                enter-to-class="opacity-100 translate-y-0"
            >
                <span
                    v-if="page.props.flash.success"
                    class="flex items-center gap-1 text-xs font-medium text-green-600 dark:text-green-400"
                >
                    <CheckCircle2 class="size-3" /> Atualizado
                </span>
            </transition>
        </div>

        <div
            class="rounded-lg bg-white p-5 shadow-xs shadow-gray-300/25 md:p-6 dark:bg-gray-900 dark:shadow-none"
        >
            <form class="space-y-5" @submit.prevent="submit">
                <div class="space-y-1">
                    <label
                        for="name"
                        class="inline-block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Nome Completo</label
                    >
                    <input
                        v-model="form.name"
                        type="text"
                        id="name"
                        placeholder="Ex: João Silva"
                        class="block w-full rounded-lg border px-3 py-2 text-sm transition focus:outline-none focus:ring-3 focus:ring-primary-500/20 dark:bg-gray-950"
                        :class="
                            form.errors.name
                                ? 'border-red-500 focus:border-red-500'
                                : 'border-gray-200 dark:border-gray-800 focus:border-primary-500'
                        "
                    />
                    <p
                        v-if="form.errors.name"
                        class="text-xs text-red-500 mt-1 font-medium"
                    >
                        {{ form.errors.name }}
                    </p>
                </div>

                <div class="space-y-1">
                    <label
                        for="email"
                        class="inline-block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >E-mail</label
                    >
                    <input
                        v-model="form.email"
                        type="email"
                        id="email"
                        class="block w-full rounded-lg border px-3 py-2 text-sm transition focus:outline-none focus:ring-3 focus:ring-primary-500/20 dark:bg-gray-950 text-gray-500"
                        :class="
                            form.errors.email
                                ? 'border-red-500 focus:border-red-500'
                                : 'border-gray-200 dark:border-gray-800 focus:border-primary-500'
                        "
                    />
                    <p
                        v-if="form.errors.email"
                        class="text-xs text-red-500 mt-1 font-medium"
                    >
                        {{ form.errors.email }}
                    </p>
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        :disabled="form.processing || !form.isDirty"
                        class="cursort-pointer w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                    >
                        <Loader2
                            v-if="form.processing"
                            class="size-4 animate-spin"
                        />
                        <span>{{
                            form.processing
                                ? "Salvando..."
                                : "Salvar Alterações"
                        }}</span>
                    </button>

                    <p
                        v-if="form.isDirty"
                        class="mt-2 text-[10px] text-gray-400 uppercase tracking-wider font-bold animate-pulse"
                    >
                        * Alterações não salvas
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>
