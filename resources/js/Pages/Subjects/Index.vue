<script setup>
import { router } from "@inertiajs/vue3";
import { FwbBadge, FwbInput } from "flowbite-vue";
import { debounce } from "lodash";
import {
    BookOpen,
    GraduationCap,
    Pencil,
    PlusCircle,
    Search,
    Trash2,
    User,
} from "lucide-vue-next";
import { ref, watch } from "vue";

// Componentes Customizados
import Breadcrumbs from "@/Components/Global/Breadcrumbs.vue";
import Card from "@/Components/Global/Card.vue";
import ConfirmationModal from "@/Components/Global/ConfirmationModal.vue";
import ResourceTable from "@/Components/Global/ResourceTable.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import SubjectModal from "@/Pages/Subjects/Partials/SubjectModal.vue";

defineOptions({ layout: AppLayout });

const props = defineProps({
    subjects: Object, // Paginated data
    courses: Array, // Para o Select no Modal
    teachers: Array, // Para o Select no Modal
    filters: Object,
});

const isModalOpen = ref(false);
const editingSubject = ref(null);
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
    { label: "Disciplina", field: "name", sortable: true },
    { label: "Curso Vinculado", field: "course_id", sortable: true },
    { label: "Professor", field: "user_id", sortable: true },
    { label: "Ações", field: "actions", sortable: false, align: "right" },
];

const fetchData = () => {
    isLoading.value = true;
    router.get(
        route("subject.index"),
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

const openDeleteConfirm = (subject) => {
    confirmModal.value = {
        show: true,
        title: "Excluir Disciplina",
        message: `Deseja realmente excluir a disciplina <b>${subject.name}</b>?`,
        confirmLabel: "Excluir",
        variant: "danger",
        action: () => {
            router.delete(route("subject.destroy", subject.id), {
                onSuccess: () => (confirmModal.value.show = false),
            });
        },
    };
};
</script>

<template>
    <Breadcrumbs
        title="Gestão de Disciplinas"
        description="Organize as matérias e vincule-as aos respectivos cursos e professores."
    />

    <div class="space-y-6">
        <Card>
            <template #filters>
                <div class="w-full lg:max-w-md">
                    <fwb-input
                        v-model="search"
                        placeholder="Buscar por nome da disciplina..."
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
                        editingSubject = null;
                        isModalOpen = true;
                    "
                    class="btn-primary bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 flex items-center gap-2 rounded-lg font-bold shadow-lg transition-all active:scale-95"
                >
                    <PlusCircle class="size-4" /> <span>Nova Disciplina</span>
                </button>
            </template>
        </Card>

        <ResourceTable
            :rows="subjects.data"
            :pagination="subjects"
            :columns="columns"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            :is-loading="isLoading"
            @sort="handleSort"
            @page-changed="
                (n) =>
                    router.get(
                        route('subject.index'),
                        { page: n },
                        { preserveState: true },
                    )
            "
        >
            <template #body>
                <tr
                    v-for="subject in subjects.data"
                    :key="subject.id"
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
                                    {{ subject.name }}
                                </span>
                                <span
                                    class="text-xs text-gray-500 line-clamp-1"
                                >
                                    {{ subject.description }}
                                </span>
                            </div>
                        </div>
                    </td>

                    <td class="p-4">
                        <div
                            v-if="subject.course"
                            class="flex items-center gap-2 text-sm text-gray-600"
                        >
                            <GraduationCap class="size-4 text-gray-400" />
                            <span>{{ subject.course.name }}</span>
                        </div>
                        <span v-else class="text-xs text-gray-400 italic"
                            >Sem curso</span
                        >
                    </td>

                    <td class="p-4">
                        <div
                            v-if="subject.teacher"
                            class="flex items-center gap-2 text-sm text-gray-600"
                        >
                            <div
                                class="size-6 rounded-full bg-gray-200 flex items-center justify-center"
                            >
                                <User class="size-3 text-gray-500" />
                            </div>
                            <span>{{ subject.teacher.name }}</span>
                        </div>
                        <fwb-badge v-else type="dark">Não atribuído</fwb-badge>
                    </td>

                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button
                                @click="
                                    editingSubject = subject;
                                    isModalOpen = true;
                                "
                                class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all"
                            >
                                <Pencil class="size-5" />
                            </button>
                            <button
                                @click="openDeleteConfirm(subject)"
                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                            >
                                <Trash2 class="size-5" />
                            </button>
                        </div>
                    </td>
                </tr>
            </template>
        </ResourceTable>

        <SubjectModal
            :show="isModalOpen"
            :subject="editingSubject"
            :courses="courses"
            :teachers="teachers"
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
