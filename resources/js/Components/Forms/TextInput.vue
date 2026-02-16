<script setup>
import { computed, ref } from "vue";

const props = defineProps({
    modelValue: [String, Number],
    type: { type: String, default: "text" },
    label: String,
    error: String,
    placeholder: String,
    id: String,
});

const emit = defineEmits(["update:modelValue"]);

// Estado para alternar visibilidade se for password 👁️
const showPassword = ref(false);

const inputType = computed(() => {
    if (props.type === "password") {
        return showPassword.value ? "text" : "password";
    }
    return props.type;
});

const toggleVisibility = () => (showPassword.value = !showPassword.value);
</script>

<template>
    <div class="space-y-1">
        <label v-if="label" :for="id" class="inline-block text-sm font-medium">
            {{ label }}
        </label>

        <div class="relative">
            <input
                :id="id"
                :type="inputType"
                :value="modelValue"
                @input="emit('update:modelValue', $event.target.value)"
                :placeholder="placeholder"
                class="block w-full rounded-lg border border-gray-200 px-5 py-3 leading-6 focus:border-primary-500 focus:ring-3 focus:ring-primary-500/50 dark:border-gray-600 dark:bg-gray-800 placeholder:text-gray-400 dark:placeholder:text-gray-500"
                :class="{ 'border-red-500': error }"
            />

            <button
                v-if="type === 'password'"
                type="button"
                @click="toggleVisibility"
                class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-primary-600 transition-colors"
            >
                <svg
                    v-if="!showPassword"
                    xmlns="http://www.w3.org/2000/svg"
                    class="size-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                    />
                </svg>
                <svg
                    v-else
                    xmlns="http://www.w3.org/2000/svg"
                    class="size-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"
                    />
                </svg>
            </button>
        </div>

        <p v-if="error" class="text-xs text-red-500 mt-1">
            {{ error }}
        </p>
    </div>
</template>
