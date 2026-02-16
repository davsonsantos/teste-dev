<script setup>
import { router } from "@inertiajs/vue3";
import { FwbInput, FwbSelect } from "flowbite-vue";
import { debounce } from "lodash";
import {
    Eye,
    GraduationCap,
    Pencil,
    PlusCircle,
    Search,
    Trash2,
} from "lucide-vue-next";
import { ref, watch } from "vue";

import Avatar from "@/Components/Global/Avatar.vue";
import Badge from "@/Components/Global/Badge.vue";
import Breadcrumbs from "@/Components/Global/Breadcrumbs.vue";
import Card from "@/Components/Global/Card.vue";
import ConfirmationModal from "@/Components/Global/ConfirmationModal.vue";
import ResourceTable from "@/Components/Global/ResourceTable.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import EnrollmentModal from "./Partials/EnrollmentModal.vue";
import EnrollmentShow from "./Partials/EnrollmentShow.vue";

defineOptions({ layout: AppLayout });

const props = defineProps({
    enrollments: Object,
    filters: Object,
    courses: Array,
    students: Array,
});

const isLoading = ref(false);
const isModalOpen = ref(false);
const editingEnrollment = ref(null);
const confirmModal = ref({
    show: false,
    title: "",
    message: "",
    action: null,
});
const isShowModalOpen = ref(false);
const viewingEnrollment = ref(null);

const openDeleteConfirm = (enrollment) => {
    confirmModal.value = {
        show: true,
        title: "Remover Matrícula",
        message: `Deseja realmente remover a matrícula de <b>${enrollment.student.name}</b> no curso <b>${enrollment.course.name}</b>?`,
        action: () => {
            router.delete(route("enrollments.destroy", enrollment.id), {
                onSuccess: () => (confirmModal.value.show = false),
            });
        },
    };
};

// Filtros
const search = ref(props.filters?.search || "");
const status = ref(props.filters?.status || "");
const sortField = ref(props.filters?.sort_field || "created_at");
const sortDirection = ref(props.filters?.sort_direction || "desc");

const columns = [
    { label: "Estudante", field: "user_id", sortable: true },
    { label: "Curso", field: "course_id", sortable: true },
    { label: "Status", field: "status", sortable: true },
    { label: "Data de Matrícula", field: "created_at", sortable: true },
    { label: "Ações", field: "actions", align: "right" },
];

const fetchData = () => {
    isLoading.value = true;
    router.get(
        route("enrollments.index"),
        {
            search: search.value,
            status: status.value,
            sort_field: sortField.value,
            sort_direction: sortDirection.value,
        },
        {
            preserveState: true,
            replace: true,
            onFinish: () => (isLoading.value = false),
        },
    );
};

watch([search, status], debounce(fetchData, 400));

const getStatusType = (status) => {
    const types = {
        cursando: "info",
        pendente: "warning",
        cancelado: "danger",
        concluido: "success",
    };
    return types[status] || "dark";
};

const handleSort = (field) => {
    sortDirection.value =
        sortField.value === field && sortDirection.value === "asc"
            ? "desc"
            : "asc";
    sortField.value = field;
    fetchData();
};
</script>

<template>
    <Breadcrumbs
        title="Controle de Matrículas"
        description="Gerencie o vínculo de alunos aos cursos e acompanhe o progresso."
    />

    <div class="space-y-6">
        <Card>
            <template #filters>
                <div class="flex flex-wrap gap-4 w-full">
                    <div class="flex-1 min-w-[300px]">
                        <fwb-input
                            v-model="search"
                            placeholder="Buscar por aluno ou curso..."
                        >
                            <template #prefix
                                ><Search class="size-4 text-gray-400"
                            /></template>
                        </fwb-input>
                    </div>
                    <div class="w-full sm:w-48">
                        <fwb-select
                            placeholder="Status da Matricula"
                            v-model="status"
                            :options="[
                                { value: '', name: 'Todos Status' },
                                { value: 'pendente', name: 'Pendente' },
                                { value: 'cursando', name: 'Cursando' },
                                { value: 'concluido', name: 'Concluído' },
                                { value: 'cancelado', name: 'Cancelado' },
                            ]"
                        />
                    </div>
                </div>
            </template>

            <template #actions>
                <button
                    @click="
                        editingEnrollment = null;
                        isModalOpen = true;
                    "
                    class="btn-primary flex items-center gap-2 bg-primary-600 text-white px-4 py-2 rounded-lg font-bold shadow-md hover:bg-primary-700 transition-all"
                >
                    <PlusCircle class="size-4" /> <span>Nova Matrícula</span>
                </button>
            </template>
        </Card>

        <ResourceTable
            :rows="enrollments.data"
            :pagination="enrollments"
            :columns="columns"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            :is-loading="isLoading"
            @sort="handleSort"
        >
            <template #body>
                <tr
                    v-for="item in enrollments.data"
                    :key="item.id"
                    class="border-b dark:border-gray-700 hover:bg-gray-50/50"
                >
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <Avatar :user="item.student" size="sm" />
                            <div class="flex flex-col">
                                <span
                                    class="font-bold text-gray-900 dark:text-white"
                                    >{{ item.student.name }}</span
                                >
                                <span class="text-xs text-gray-500">{{
                                    item.student.email
                                }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-sm text-gray-600 dark:text-gray-300">
                        <div class="flex items-center gap-2">
                            <GraduationCap class="size-4 text-primary-500" />
                            {{ item.course.name }}
                        </div>
                    </td>
                    <td class="p-4 text-sm">
                        <Badge
                            :variant="getStatusType(item.status)"
                            class="uppercase font-bold text-[10px]"
                        >
                            {{ item.status }}
                        </Badge>
                    </td>
                    <td class="p-4 text-sm text-gray-500">
                        {{ new Date(item.created_at).toLocaleDateString() }}
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button
                                @click="
                                    viewingEnrollment = item;
                                    isShowModalOpen = true;
                                "
                                class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                title="Visualizar Detalhes"
                            >
                                <Eye class="size-5" />
                            </button>
                            <button
                                @click="
                                    editingEnrollment = item;
                                    isModalOpen = true;
                                "
                                class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all"
                                title="Editar Matrícula"
                            >
                                <Pencil class="size-5" />
                            </button>
                            <button
                                @click="openDeleteConfirm(item)"
                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                title="Remover Matrícula"
                            >
                                <Trash2 class="size-5" />
                            </button>
                        </div>
                    </td>
                </tr>
            </template>
        </ResourceTable>
        <EnrollmentModal
            :show="isModalOpen"
            :enrollment="editingEnrollment"
            :students="students"
            :courses="courses"
            @close="isModalOpen = false"
        />

        <ConfirmationModal
            :show="confirmModal.show"
            v-bind="confirmModal"
            @close="confirmModal.show = false"
            @confirm="confirmModal.action()"
        />

        <EnrollmentShow
            :show="isShowModalOpen"
            :enrollment="viewingEnrollment"
            @close="isShowModalOpen = false"
        />
    </div>
</template>
