<script setup>
import { FwbPagination } from "flowbite-vue";
import {
    ArrowDownWideNarrow,
    ArrowUpDown,
    ArrowUpNarrowWide,
    Loader2,
    ShieldAlert,
} from "lucide-vue-next";

defineProps({
    rows: Array,
    pagination: Object,
    isLoading: Boolean,
    columns: Array,
    sortField: String,
    sortDirection: String,
});

defineEmits(["sort", "page-changed"]);
</script>

<template>
    <div class="space-y-4">
        <div
            class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700"
        >
            <div
                class="relative overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-700/50"
            >
                <transition
                    enter-active-class="transition duration-200"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="transition duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-if="isLoading"
                        class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-white/60 backdrop-blur-[1px] dark:bg-gray-800/60"
                    >
                        <Loader2
                            class="size-10 animate-spin text-primary-600"
                        />
                    </div>
                </transition>

                <table
                    class="min-w-full align-middle text-sm whitespace-nowrap transition-opacity duration-300"
                    :class="{ 'opacity-40 pointer-events-none': isLoading }"
                >
                    <thead>
                        <tr
                            class="border-b border-gray-100 dark:border-gray-700/50"
                        >
                            <th
                                v-for="col in columns"
                                :key="col.field"
                                class="bg-gray-50/50 px-3 py-4 font-semibold text-gray-900 dark:bg-gray-700/25 dark:text-gray-50"
                                :class="[
                                    col.sortable
                                        ? 'cursor-pointer select-none hover:bg-gray-100 transition-colors'
                                        : '',
                                    col.align === 'right'
                                        ? 'text-right'
                                        : col.align === 'center'
                                          ? 'text-center'
                                          : 'text-left',
                                ]"
                                @click="
                                    col.sortable
                                        ? $emit('sort', col.field)
                                        : null
                                "
                            >
                                <div
                                    class="flex items-center gap-2"
                                    :class="{
                                        'justify-end': col.align === 'right',
                                        'justify-center':
                                            col.align === 'center',
                                    }"
                                >
                                    <span>{{ col.label }}</span>
                                    <template v-if="col.sortable">
                                        <component
                                            :is="
                                                sortField === col.field
                                                    ? sortDirection === 'asc'
                                                        ? ArrowUpNarrowWide
                                                        : ArrowDownWideNarrow
                                                    : ArrowUpDown
                                            "
                                            class="size-3.5 transition-colors"
                                            :class="
                                                sortField === col.field
                                                    ? 'text-primary-600 font-bold'
                                                    : 'text-gray-400/50'
                                            "
                                        />
                                    </template>
                                </div>
                            </th>
                        </tr>
                    </thead>

                    <tbody
                        class="divide-y divide-gray-100 dark:divide-gray-700/50"
                    >
                        <slot name="body" />
                        <tr v-if="rows.length === 0">
                            <td
                                :colspan="columns.length"
                                class="py-16 text-center"
                            >
                                <div
                                    class="flex flex-col items-center gap-3 text-gray-400"
                                >
                                    <ShieldAlert class="size-10 opacity-20" />
                                    <span class="text-sm font-medium"
                                        >Nenhum registro encontrado.</span
                                    >
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div
            v-if="pagination && pagination.total > 0"
            class="flex flex-col items-center justify-between gap-4 py-2 sm:flex-row"
        >
            <span class="text-sm text-gray-500 dark:text-gray-400">
                Mostrando <b>{{ pagination.from }}</b> a
                <b>{{ pagination.to }}</b> de
                <b>{{ pagination.total }}</b> registros
            </span>
            <fwb-pagination
                v-model="pagination.current_page"
                :total-items="pagination.total"
                :per-page="pagination.per_page"
                class="pagination-orange"
                @page-changed="(page) => $emit('page-changed', page)"
            />
        </div>
    </div>
</template>
