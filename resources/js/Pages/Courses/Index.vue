<script setup>
import { router } from "@inertiajs/vue3";
import { FwbInput } from "flowbite-vue";
import { debounce } from "lodash";
import {
    BookOpen,
    Calendar,
    ChevronRight,
    Pencil,
    PlusCircle,
    Search,
    Trash2,
} from "lucide-vue-next";
import { ref, watch } from "vue";

// Componentes Customizados (Assumindo que existem no seu projeto)
import Card from "@/Components/Global/Card.vue";
import ConfirmationModal from "@/Components/Global/ConfirmationModal.vue";
import ResourceTable from "@/Components/Global/ResourceTable.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import CourseModal from "@/Pages/Courses/Partials/CourseModal.vue"; // Você precisará criar este baseado no UserModal
import Breadcrumbs from "../../Components/Global/Breadcrumbs.vue";

defineOptions({ layout: AppLayout });

const props = defineProps({
    courses: Object,
    filters: Object,
});

const isModalOpen = ref(false);
const editingCourse = ref(null);
const isLoading = ref(false);

const confirmModal = ref({
    show: false,
    title: "",
    message: "",
    confirmLabel: "",
    variant: "danger",
    action: null,
});

// Filtros e Ordenação
const search = ref(props.filters?.search || "");
const sortField = ref(props.filters?.sort_field || "created_at");
const sortDirection = ref(props.filters?.sort_direction || "desc");

const columns = [
    { label: "Curso", field: "name", sortable: true },
    { label: "Duração", field: "dates", sortable: false },
    { label: "Criado em", field: "created_at", sortable: true },
    { label: "Ações", field: "actions", sortable: false, align: "right" },
];

const fetchData = () => {
    isLoading.value = true;
    router.get(
        route("course.index"),
        {
            search: search.value,
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

watch(search, debounce(fetchData, 400));

const handleSort = (field) => {
    sortDirection.value =
        sortField.value === field && sortDirection.value === "asc"
            ? "desc"
            : "asc";
    sortField.value = field;
    fetchData();
};

const openDeleteConfirm = (course) => {
    confirmModal.value = {
        show: true,
        title: "Excluir Curso",
        message: `Tem certeza que deseja excluir o curso <b>${course.name}</b>? Esta ação não pode ser desfeita.`,
        confirmLabel: "Excluir Agora",
        variant: "danger",
        action: () => {
            router.delete(route("course.destroy", course.id), {
                onSuccess: () => (confirmModal.value.show = false),
            });
        },
    };
};
</script>

<template>
    <Breadcrumbs
        title="Gestão de Cursos"
        description="Gerencie o catálogo de cursos, datas de vigência e conteúdos."
    />

    <div class="space-y-6">
        <Card>
            <template #filters>
                <div class="w-full lg:max-w-md">
                    <fwb-input
                        v-model="search"
                        placeholder="Buscar por título ou descrição..."
                    >
                        <template #prefix>
                            <Search class="size-4 text-gray-400" />
                        </template>
                    </fwb-input>
                </div>
            </template>

            <template #actions>
                <button
                    @click="
                        editingCourse = null;
                        isModalOpen = true;
                    "
                    class="btn-primary bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 flex items-center gap-2 rounded-lg font-bold shadow-lg transition-all active:scale-95"
                >
                    <PlusCircle class="size-4" /> <span>Novo Curso</span>
                </button>
            </template>
        </Card>

        <ResourceTable
            :rows="courses.data"
            :pagination="courses"
            :columns="columns"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            :is-loading="isLoading"
            @sort="handleSort"
            @page-changed="
                (n) =>
                    router.get(
                        route('course.index'),
                        { page: n },
                        { preserveState: true },
                    )
            "
        >
            <template #body>
                <tr
                    v-for="course in courses.data"
                    :key="course.id"
                    class="border-b border-gray-100 dark:border-gray-700/50 last:border-0 hover:bg-gray-50/50 transition-colors"
                >
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="p-2 bg-primary-50 text-primary-600 rounded-lg"
                            >
                                <BookOpen class="size-5" />
                            </div>
                            <div class="flex flex-col">
                                <span
                                    class="font-bold text-gray-900 dark:text-gray-100 line-clamp-1"
                                >
                                    {{ course.name }}
                                </span>
                                <span
                                    class="text-xs text-gray-500 line-clamp-1"
                                    >{{ course.description }}</span
                                >
                            </div>
                        </div>
                    </td>

                    <td class="p-4">
                        <div
                            class="flex items-center gap-2 text-xs font-medium text-gray-600"
                        >
                            <Calendar class="size-3" />
                            <span>{{ course.start_date }}</span>
                            <ChevronRight class="size-3" />
                            <span>{{ course.end_date }}</span>
                        </div>
                    </td>

                    <td class="p-4 text-sm text-gray-500">
                        {{ new Date(course.created_at).toLocaleDateString() }}
                    </td>

                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button
                                @click="
                                    editingCourse = course;
                                    isModalOpen = true;
                                "
                                class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all"
                            >
                                <Pencil class="size-5" />
                            </button>
                            <button
                                @click="openDeleteConfirm(course)"
                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                            >
                                <Trash2 class="size-5" />
                            </button>
                        </div>
                    </td>
                </tr>
            </template>
        </ResourceTable>

        <CourseModal
            :show="isModalOpen"
            :course="editingCourse"
            @close="isModalOpen = false"
        />

        <ConfirmationModal
            :show="confirmModal.show"
            v-bind="confirmModal"
            @close="confirmModal.show = false"
            @confirm="confirmModal.action()"
        />
    </div>
</template>
