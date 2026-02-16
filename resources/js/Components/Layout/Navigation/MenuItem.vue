<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { ChevronDown } from "lucide-vue-next";
import { computed } from "vue";

const props = defineProps({
    item: Object,
    isItemActive: Function,
    isOpen: Boolean, // O pai diz se estou aberto
});

const emit = defineEmits(["toggle"]);

// Verifica se este item ou algum filho está ativo para destacar o menu
const isActive = computed(() => {
    // 🧭 ESSENCIAL: Referenciamos a URL do Inertia aqui.
    // Isso garante que esta 'computed' seja reavaliada toda vez que navegamos.
    const url = usePage().url;

    // Se o item tem uma rota direta (ex: Dashboard ou Usuários)
    if (props.item.route) {
        return props.isItemActive(props.item.route);
    }

    // Se o item tem filhos (ex: Atendimento), ele fica ativo se algum filho estiver ativo
    if (props.item.children) {
        return props.item.children.some((child) =>
            props.isItemActive(child.route),
        );
    }

    return false;
});

// Lógica para avisar o pai que este item pai foi clicado para abrir/fechar o dropdown
const handleToggle = () => {
    if (props.item.children) {
        emit("toggle");
    }
};

// Se o item estiver ativo (ex: F5 na página), avisamos o pai para abrir este menu
// if (isActive.value && props.item.children && !props.isOpen) {
//     emit('toggle');
// }
</script>

<template>
    <div class="flex flex-col gap-1">
        <template v-if="item.children">
            <button
                @click="handleToggle"
                type="button"
                class="group flex w-full items-center gap-2.5 rounded-lg px-2.5 text-sm font-medium transition-all duration-200"
                :class="[
                    isActive || isOpen
                        ? 'text-white bg-gray-700/50'
                        : 'text-gray-400 hover:bg-gray-700/50 hover:text-white',
                ]"
            >
                <span
                    class="flex flex-none items-center text-gray-500 group-hover:text-gray-300"
                >
                    <component
                        :is="item.icon"
                        class="size-5"
                        stroke-width="2"
                    />
                </span>
                <span class="grow py-2 text-left">{{ item.name }}</span>
                <ChevronDown
                    class="size-4 transition-transform duration-200"
                    :class="{ 'rotate-180': isOpen }"
                />
            </button>

            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 -translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2"
            >
                <div
                    v-show="isOpen"
                    class="ml-9 flex flex-col gap-1 border-l border-gray-700 pl-2 mt-1"
                >
                    <Link
                        v-for="sub in item.children"
                        :key="sub.name"
                        :href="route().has(sub.route) ? route(sub.route) : '#'"
                        class="rounded-md px-3 py-2 text-sm font-medium transition-colors"
                        :class="[
                            isItemActive(sub.route)
                                ? 'text-orange-400 bg-gray-700/30'
                                : 'text-gray-500 hover:text-gray-200 hover:bg-gray-800/40',
                        ]"
                    >
                        {{ sub.name }}
                    </Link>
                </div>
            </Transition>
        </template>

        <Link
            v-else
            :href="route().has(item.route) ? route(item.route) : '#'"
            class="group flex items-center gap-2.5 rounded-lg px-2.5 text-sm font-medium transition-all duration-200"
            :class="[
                isActive
                    ? 'text-white bg-gray-700/75 border-gray-600 shadow-sm'
                    : 'text-gray-400 hover:bg-gray-700/50 hover:text-white border-transparent',
            ]"
        >
            <span
                class="flex flex-none items-center transition-colors duration-200"
                :class="
                    isActive
                        ? 'text-orange-500'
                        : 'text-gray-500 group-hover:text-gray-300'
                "
            >
                <component :is="item.icon" class="size-5" stroke-width="2" />
            </span>
            <span class="grow py-2">{{ item.name }}</span>
        </Link>
    </div>
</template>
