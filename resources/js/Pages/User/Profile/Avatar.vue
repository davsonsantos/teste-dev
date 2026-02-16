<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { Loader2, User } from "lucide-vue-next"; // Importe o ícone User e Loader2 para o loading
import { computed, ref } from "vue";
import { toast } from "vue-sonner";

const page = usePage();
const user = computed(() => page.props.auth.user);

const fileInput = ref(null);
const form = useForm({
    avatar: null,
});

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file && file.size > 2 * 1024 * 1024) {
        toast.error("A imagem deve ter no máximo 2MB");
        return;
    }
    if (file) {
        form.avatar = file;
        form.post(route("profile.avatar.update"), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                toast.success("Avatar atualizado com sucesso!");
            },
            onError: (errors) => {
                // Se houver erro de validação do Laravel (ex: formato inválido)
                if (errors.avatar) {
                    toast.error(errors.avatar);
                } else {
                    toast.error("Ocorreu um erro ao enviar a imagem.");
                }
            },
        });
    }
};

const triggerFileInput = () => fileInput.value.click();
</script>

<template>
    <div class="lg:col-span-1">
        <input
            type="file"
            ref="fileInput"
            class="hidden"
            accept="image/*"
            @change="handleFileChange"
        />

        <div
            class="flex flex-col rounded-xl bg-gray-100/75 p-2 ring ring-gray-200/50 dark:bg-gray-800/35 dark:ring-gray-800/60"
        >
            <div
                class="flex flex-col items-center rounded-lg bg-white p-6 text-center shadow-xs dark:bg-gray-900"
            >
                <div class="mb-4 relative group">
                    <div
                        class="inline-flex size-24 items-center justify-center rounded-full border border-gray-200 bg-white p-1.5 dark:border-gray-700 dark:bg-gray-900 overflow-hidden"
                    >
                        <img
                            v-if="user.avatar"
                            :src="user.avatar"
                            class="inline-block size-20 rounded-full"
                            alt="Avatar"
                        />

                        <User
                            v-else
                            class="size-12 text-gray-400 dark:text-gray-500"
                        />
                    </div>
                    <div
                        v-if="form.processing"
                        class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-full"
                    >
                        <Loader2 class="size-8 animate-spin text-white" />
                    </div>
                </div>

                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ user.name }}
                </h2>
                <p
                    class="mt-1 text-sm font-medium text-gray-600 dark:text-gray-400"
                >
                    {{ user.role }}
                </p>

                <div
                    class="mt-4 w-full space-y-2 border-t border-gray-200 pt-4 text-left text-sm dark:border-gray-700"
                >
                    <div
                        class="flex items-center gap-2 text-gray-600 dark:text-gray-400"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 16 16"
                            fill="currentColor"
                            class="size-4"
                        >
                            <path
                                d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z"
                            />
                            <path
                                d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z"
                            />
                        </svg>
                        <span>{{ user.email }}</span>
                    </div>
                </div>

                <button
                    type="button"
                    @click="triggerFileInput"
                    :disabled="form.processing"
                    class="cursor-pointer mt-6 flex w-full items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50 active:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800"
                >
                    <svg
                        v-if="!form.processing"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 16 16"
                        fill="currentColor"
                        class="size-4"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M2 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4Zm10.5 5.707a.5.5 0 0 0-.146-.353l-1-1a.5.5 0 0 0-.708 0L9.354 9.646a.5.5 0 0 1-.708 0L6.354 7.354a.5.5 0 0 0-.708 0l-2 2a.5.5 0 0 0-.146.353V12a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5V9.707ZM12 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <span>{{
                        form.processing ? "Enviando..." : "Atualizar Foto"
                    }}</span>
                </button>
            </div>
        </div>
    </div>
</template>
