<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {
    FlowbiteThemable,
    FwbBreadcrumb,
    FwbBreadcrumbItem,
} from "flowbite-vue";
import Avatar from "./Avatar.vue";
import Information from "./Information.vue";
import Security from "./Security.vue";
const breadcrumbs = [
    { label: "Dashboard", route: "dashboard", active: false }, // Sugestão: iniciar pelo dashboard
    { label: "Perfil", active: true },
];

const theme = "orange";
defineOptions({ layout: AppLayout });
</script>

<template #header>
    <flowbite-themable :theme="theme">
        <fwb-breadcrumb>
            <fwb-breadcrumb-item home :href="route('dashboard')">
                Home
            </fwb-breadcrumb-item>

            <fwb-breadcrumb-item :href="route('profile')">
                Perfil
            </fwb-breadcrumb-item>

            <fwb-breadcrumb-item> Editar </fwb-breadcrumb-item>
        </fwb-breadcrumb>
    </flowbite-themable>
    
    <div class="dark:text-gray-100">
        <div class="grid gap-6 lg:grid-cols-3 lg:gap-8">
            <Avatar />
            <div class="space-y-6 lg:col-span-2 lg:space-y-8">
                <Information />
                <Security />
            </div>
        </div>
    </div>
</template>
