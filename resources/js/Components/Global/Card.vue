<template>
    <div
        class="flex flex-col gap-4 rounded-xl bg-white p-5 shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700 lg:flex-row lg:items-center lg:justify-between"
    >
        <div
            v-if="$slots.filters"
            class="flex flex-1 flex-col gap-4 sm:flex-row sm:items-center"
        >
            <slot name="filters" />
        </div>

        <div
            v-if="$slots.content || (!$slots.filters && !$slots.actions)"
            class="flex-1"
        >
            <slot name="content">
                <span
                    class="text-gray-400 italic text-sm text-center lg:text-left"
                >
                </span>
            </slot>
        </div>

        <div v-if="$slots.actions" class="flex items-center gap-2">
            <slot name="actions" />
        </div>
    </div>
</template>
