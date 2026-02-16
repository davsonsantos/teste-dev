<script setup>
import Card from "@/Components/Global/Card.vue";
import ConfirmationModal from "@/Components/Global/ConfirmationModal.vue";
import ResourceTable from "@/Components/Global/ResourceTable.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import InviteUserModal from "@/Pages/Users/Partials/InviteUserModal.vue";
import UserModal from "@/Pages/Users/Partials/UserModal.vue";
import { router, usePage } from "@inertiajs/vue3";
import { FwbCheckbox, FwbInput, FwbSelect } from "flowbite-vue";
import { debounce } from "lodash";
import {
    Pencil,
    RotateCcw,
    Search,
    Send,
    Trash2,
    UserCheck,
    UserPlus,
    UserX,
} from "lucide-vue-next";
import { ref, watch } from "vue";
import Avatar from "../../Components/Global/Avatar.vue";
import Badge from "../../Components/Global/Badge.vue";
import Breadcrumbs from "../../Components/Global/Breadcrumbs.vue";

defineOptions({ layout: AppLayout });

const page = usePage();
const props = defineProps({ users: Object, filters: Object, roles: Array });

const impersonateUser = (user) => {
    router.post(route("admin.impersonate.take", user.id));
};

const isModalOpen = ref(false);
const editingUser = ref(null);
const isInviteModalOpen = ref(false);
const isLoading = ref(false);

const confirmModal = ref({
    show: false,
    title: "",
    message: "",
    confirmLabel: "",
    variant: "danger",
    loading: false,
    success: false,
    action: null,
});

const search = ref(props.filters?.search || "");
const status = ref(props.filters?.status || "");
const trashed = ref(
    props.filters?.trashed === "true" || props.filters?.trashed === true,
);
const sortField = ref(props.filters?.sort_field || "created_at");
const sortDirection = ref(props.filters?.sort_direction || "desc");

const columns = [
    { label: "Usuário", field: "name", sortable: true },
    { label: "Status", field: "is_active", sortable: false },
    { label: "Permissão", field: "role", sortable: true },
    { label: "Ações", field: "actions", sortable: false, align: "right" },
];

const role = ref(props.filters?.role || "");

const handleSort = (field) => {
    sortDirection.value =
        sortField.value === field && sortDirection.value === "asc"
            ? "desc"
            : "asc";
    sortField.value = field;
    fetchData();
};

const fetchData = () => {
    isLoading.value = true;
    router.get(
        route("users.index"),
        {
            search: search.value,
            status: status.value,
            role: role.value,
            trashed: trashed.value,
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

watch([search, status, role, trashed], debounce(fetchData, 300));

const handlePageChange = (pageNumber) => {
    router.get(
        route("users.index"),
        {
            page: pageNumber,
            search: search.value,
            status: status.value,
            trashed: trashed.value,
            sort_field: sortField.value,
            sort_direction: sortDirection.value,
        },
        { preserveState: true },
    );
};

const openDeleteConfirm = (user) => {
    const isRestoring = !!user.deleted_at;
    confirmModal.value = {
        show: true,
        title: isRestoring ? "Restaurar Usuário" : "Excluir Usuário",
        message: isRestoring
            ? `Deseja restaurar <b>${user.name}</b>?`
            : `Deseja excluir <b>${user.name}</b>?`,
        confirmLabel: isRestoring ? "Restaurar" : "Excluir",
        variant: isRestoring ? "warning" : "danger",
        loading: false,
        success: false,
        action: () => {
            confirmModal.value.loading = true;
            router.delete(route("users.destroy", user.id), {
                onSuccess: () => {
                    confirmModal.value.success = true;
                },
                onFinish: () => (confirmModal.value.loading = false),
            });
        },
    };
};

const roleMap = {
    admin: {
        label: "Administrador",
        variant: "info",
    },
    teacher: {
        label: "Professor",
        variant: "primary",
    },
    student: {
        label: "Estudante",
        variant: "success",
    },
};
</script>

<template>
    <Breadcrumbs
        title="Gerenciar Usuários"
        description="Visualize e edite as informações dos colaboradores do sistema."
    />
    <div class="space-y-6">
        <Card>
            <template #filters>
                <div class="w-full lg:max-w-xs">
                    <fwb-input
                        v-model="search"
                        placeholder="Procurar usuário..."
                    >
                        <template #prefix>
                            <Search class="size-4 text-gray-400" />
                        </template>
                    </fwb-input>
                </div>
                <div class="w-full sm:w-48">
                    <fwb-select
                        v-model="status"
                        :options="[
                            { value: 'active', name: 'Ativos' },
                            { value: 'inactive', name: 'Inativos' },
                        ]"
                        placeholder="Status"
                    />
                </div>
                <div class="w-full sm:w-48">
                    <fwb-select
                        v-model="role"
                        :options="[
                            { value: '', name: 'Todos os Usuários' },
                            { value: 'admin', name: 'Administrador' },
                            { value: 'teacher', name: 'Professor' },
                            { value: 'student', name: 'Estudante' },
                        ]"
                        placeholder="Tipo De Usuário"
                    />
                </div>
                <fwb-checkbox v-model="trashed" label="Excluídos" />
            </template>

            <template #actions>
                <button
                    @click="isInviteModalOpen = true"
                    class="btn-secondary px-5 py-2.5 flex items-center gap-2 border rounded-lg font-bold transition-all active:scale-95"
                >
                    <Send class="size-4" /> <span>Convidar</span>
                </button>
                <button
                    @click="
                        editingUser = null;
                        isModalOpen = true;
                    "
                    class="btn-primary bg-primary-600 text-white px-5 py-2.5 flex items-center gap-2 rounded-lg font-bold shadow-lg shadow-primary-600/20 active:scale-95"
                >
                    <UserPlus class="size-4" /> <span>Novo Usuário</span>
                </button>
            </template>
        </Card>
        <ResourceTable
            :rows="users.data"
            :pagination="users"
            :columns="columns"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            :is-loading="isLoading"
            @sort="handleSort"
            @page-changed="handlePageChange"
        >
            <template #body>
                <tr
                    v-for="user in users.data"
                    :key="user.id"
                    class="border-b border-gray-100 dark:border-gray-700/50 last:border-0 hover:bg-gray-50/50 transition-colors"
                    :class="{ 'opacity-60 grayscale-[0.5]': user.deleted_at }"
                >
                    <td class="p-3">
                        <div class="flex items-center gap-3">
                            <Avatar :user="user" size="sm" />
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="font-bold text-gray-900 dark:text-gray-100"
                                        >{{ user.name }}</span
                                    >
                                    <span
                                        v-if="
                                            user.id === page.props.auth.user.id
                                        "
                                        class="px-1.5 py-0.5 rounded-md bg-primary-50 text-primary-600 text-[9px] font-black uppercase"
                                        >Você</span
                                    >
                                </div>
                                <span class="text-xs text-gray-500">{{
                                    user.email
                                }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="p-3">
                        <Badge
                            v-if="user.deleted_at"
                            variant="danger"
                            :icon="Trash2"
                        >
                            Excluído
                        </Badge>

                        <Badge
                            v-else-if="user.is_active"
                            variant="success"
                            :icon="UserCheck"
                        >
                            Ativo
                        </Badge>

                        <Badge v-else variant="warning" :icon="UserX">
                            Inativo
                        </Badge>
                    </td>
                    <td class="p-3">
                        <Badge
                            :variant="roleMap[user.role]?.variant || 'primary'"
                        >
                            {{ roleMap[user.role]?.label || user.role }}
                        </Badge>
                    </td>
                    <td class="p-3 text-right text-gray-400">
                        <div class="flex justify-end gap-1">
                            <button
                                @click="
                                    editingUser = user;
                                    isModalOpen = true;
                                "
                                class="p-2 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all"
                            >
                                <Pencil class="size-5" />
                            </button>
                            <button
                                @click="openDeleteConfirm(user)"
                                class="p-2 rounded-lg transition-all"
                                :class="
                                    user.deleted_at
                                        ? 'hover:text-green-600 hover:bg-green-50'
                                        : 'hover:text-red-600 hover:bg-red-50'
                                "
                            >
                                <RotateCcw
                                    v-if="user.deleted_at"
                                    class="size-5"
                                /><Trash2 v-else class="size-5" />
                            </button>
                        </div>
                    </td>
                </tr>
            </template>
        </ResourceTable>

        <UserModal
            :show="isModalOpen"
            :user="editingUser"
            :roles="roles"
            @close="isModalOpen = false"
        />
        <InviteUserModal
            :show="isInviteModalOpen"
            @close="isInviteModalOpen = false"
        />
        <ConfirmationModal
            :show="confirmModal.show"
            :title="confirmModal.title"
            :message="confirmModal.message"
            :confirm-label="confirmModal.confirmLabel"
            :variant="confirmModal.variant"
            :loading="confirmModal.loading"
            :success="confirmModal.success"
            @close="
                confirmModal.show = false;
                confirmModal.success = false;
            "
            @confirm="confirmModal.action()"
        />
    </div>
</template>
