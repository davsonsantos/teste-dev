<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { Link } from "@inertiajs/vue3";
import { ChevronDown, LogOut, UserCircle } from "lucide-vue-next";
import Avatar from "../../Global/Avatar.vue";
</script>

<template>
    <Menu as="div" class="relative inline-block">
        <MenuButton
            class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-sm leading-5 font-semibold text-gray-300 hover:border-gray-600 hover:text-gray-200 hover:shadow-xs focus:ring-3 focus:ring-gray-600/40 active:border-gray-700 active:shadow-none"
        >
            <Avatar
                :user="$page.props.auth?.user"
                size="xs"
                class="flex-none"
            />

            <span class="hidden grow text-left sm:inline truncate">
                {{ $page.props.auth?.user?.name }}
            </span>

            <ChevronDown class="size-4 flex-none opacity-50" />
        </MenuButton>

        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="opacity-0 scale-95 -translate-y-2"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 -translate-y-2"
        >
            <MenuItems
                class="absolute top-full left-0 z-50 mt-2 w-48 origin-top-left rounded-lg bg-white p-2 shadow-xl ring-1 ring-black/5 focus:outline-hidden dark:bg-gray-800 dark:ring-gray-700 space-y-1"
            >
                <MenuItem v-slot="{ active }">
                    <Link
                        :href="route('student.profile')"
                        :class="[
                            active
                                ? 'bg-primary-50 text-primary-800 dark:bg-gray-700 dark:text-primary-400'
                                : 'text-gray-700 dark:text-gray-200',
                        ]"
                        class="flex items-center gap-2 px-2.5 py-2 text-sm rounded-lg transition-colors"
                    >
                        <UserCircle class="size-4" />
                        <span>Meu Perfil</span>
                    </Link>
                </MenuItem>

                <MenuItem v-slot="{ active }">
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        :class="[
                            active
                                ? 'bg-red-50 text-red-700 dark:bg-red-900/20'
                                : 'text-gray-700 dark:text-gray-200',
                        ]"
                        class="flex w-full items-center gap-2 px-2.5 py-2 text-sm rounded-lg transition-colors"
                    >
                        <LogOut class="size-4" />
                        <span>Sair</span>
                    </Link>
                </MenuItem>
            </MenuItems>
        </transition>
    </Menu>
</template>
