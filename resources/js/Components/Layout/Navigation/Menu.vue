<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import MenuItem from "./MenuItem.vue";
import { menuItems } from "./menuConfig.js";

const page = usePage();
const currentUrl = computed(() => page.url);

// 1. Definimos o estado
const openMenuName = ref(null);

// 2. Definimos as funções utilitárias PRIMEIRO
// (para estarem disponíveis para o watch)
const isItemActive = (itemRoute) => {
    if (!route().has(itemRoute)) return false;
    const active = route().current(itemRoute);
    return active;
};

const toggleMenu = (name) => {
    openMenuName.value = openMenuName.value === name ? null : name;
};

// 3. Agora configuramos o watch para sincronizar o menu com a URL
watch(
    currentUrl,
    () => {
        openMenuName.value = null;

        const activeMenu = menuItems.find((item) => {
            // Caso 1: O próprio item pai é o link ativo
            if (item.route && isItemActive(item.route)) return true;

            // Caso 2: Um dos filhos é o link ativo
            if (item.children) {
                return item.children.some((child) => isItemActive(child.route));
            }
            return false;
        });

        if (activeMenu) {
            // Só abrimos se for um menu com filhos (dropdown)
            if (activeMenu.children) {
                openMenuName.value = activeMenu.name;
            }
        }
    },
    { immediate: true },
);

const permissions = computed(() => page.props.auth.permissions || []);
const isSuperAdmin = computed(() => page.props.auth.user?.is_super_admin);

const canView = (item) => {
    if (isSuperAdmin.value) return true;

    // Se o item não tem permissão definida, é público
    if (!item.permission && !item.children) return true;

    // Verifica permissão direta do item
    const hasDirectPermission = item.permission
        ? permissions.value.includes(item.permission)
        : false;

    // Se tiver filhos, verifica se o utilizador pode ver pelo menos um deles
    const hasVisibleChildren = item.children?.some(
        (child) =>
            !child.permission || permissions.value.includes(child.permission),
    );

    return hasDirectPermission || hasVisibleChildren;
};

// Esta é a lista que vais usar no v-for do teu template
const filteredMenuItems = computed(() => {
    return menuItems
        .filter((item) => canView(item))
        .map((item) => {
            if (item.children) {
                return {
                    ...item,
                    children: item.children.filter(
                        (child) =>
                            isSuperAdmin.value ||
                            !child.permission ||
                            permissions.value.includes(child.permission),
                    ),
                };
            }
            return item;
        });
});
</script>

<template>
    <div
        class="flex grow flex-col overflow-y-auto p-4 bg-gray-900 min-h-screen"
    >
        <nav class="flex flex-col gap-1">
            <MenuItem
                v-for="item in filteredMenuItems"
                :key="item.name"
                :item="item"
                :is-item-active="isItemActive"
                :is-open="openMenuName === item.name"
                :is-active="
                    isItemActive(item.route) ||
                    (item.children &&
                        item.children.some((c) => isItemActive(c.route)))
                "
                @toggle="toggleMenu(item.name)"
            />
        </nav>
    </div>
</template>
