<script setup></script>
<template>
    <div
        class="flex flex-col rounded-xl bg-gray-100/75 p-2 ring ring-gray-200/50 dark:bg-gray-800/35 dark:ring-gray-800/60"
    >
        <div class="px-4 pt-2 pb-4">
            <h2 class="flex items-center gap-2 font-medium">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    class="hi-mini hi-cog inline-block size-5 text-primary-600 dark:text-primary-400"
                >
                    <path
                        d="M13.024 9.25c.47 0 .827-.433.637-.863a4 4 0 0 0-4.094-2.364c-.468.05-.665.576-.43.984l1.08 1.868a.75.75 0 0 0 .649.375h2.158ZM7.84 7.758c-.236-.408-.79-.5-1.068-.12A3.982 3.982 0 0 0 6 10c0 .884.287 1.7.772 2.363.278.38.832.287 1.068-.12l1.078-1.868a.75.75 0 0 0 0-.75L7.839 7.758ZM9.138 12.993c-.235.408-.039.934.43.984a4 4 0 0 0 4.094-2.364c.19-.43-.168-.863-.638-.863h-2.158a.75.75 0 0 0-.65.375l-1.078 1.868Z"
                    />
                    <path
                        fill-rule="evenodd"
                        d="m14.13 4.347.644-1.117a.75.75 0 0 0-1.299-.75l-.644 1.116a6.954 6.954 0 0 0-2.081-.556V1.75a.75.75 0 0 0-1.5 0v1.29a6.954 6.954 0 0 0-2.081.556L6.525 2.48a.75.75 0 1 0-1.3.75l.645 1.117A7.04 7.04 0 0 0 4.347 5.87L3.23 5.225a.75.75 0 1 0-.75 1.3l1.116.644A6.954 6.954 0 0 0 3.04 9.25H1.75a.75.75 0 0 0 0 1.5h1.29c.078.733.27 1.433.556 2.081l-1.116.645a.75.75 0 1 0 .75 1.298l1.117-.644a7.04 7.04 0 0 0 1.523 1.523l-.645 1.117a.75.75 0 1 0 1.3.75l.644-1.116a6.954 6.954 0 0 0 2.081.556v1.29a.75.75 0 0 0 1.5 0v-1.29a6.954 6.954 0 0 0 2.081-.556l.645 1.116a.75.75 0 0 0 1.299-.75l-.645-1.117a7.042 7.042 0 0 0 1.523-1.523l1.117.644a.75.75 0 0 0 .75-1.298l-1.116-.645a6.954 6.954 0 0 0 .556-2.081h1.29a.75.75 0 0 0 0-1.5h-1.29a6.954 6.954 0 0 0-.556-2.081l1.116-.644a.75.75 0 0 0-.75-1.3l-1.117.645a7.04 7.04 0 0 0-1.524-1.523ZM10 4.5a5.475 5.475 0 0 0-2.781.754A5.527 5.527 0 0 0 5.22 7.277 5.475 5.475 0 0 0 4.5 10a5.475 5.475 0 0 0 .752 2.777 5.527 5.527 0 0 0 2.028 2.004c.802.458 1.73.719 2.72.719a5.474 5.474 0 0 0 2.78-.753 5.527 5.527 0 0 0 2.001-2.027c.458-.802.719-1.73.719-2.72a5.475 5.475 0 0 0-.753-2.78 5.528 5.528 0 0 0-2.028-2.002A5.475 5.475 0 0 0 10 4.5Z"
                        clip-rule="evenodd"
                    />
                </svg>
                <span>Preferences &amp; Notifications</span>
            </h2>
        </div>
        <div
            class="rounded-lg bg-white p-5 shadow-xs shadow-gray-300/25 md:p-6 dark:bg-gray-900 dark:shadow-none"
        >
            <form class="space-y-6" @submit.prevent>
                <div class="space-y-3">
                    <div
                        class="text-sm font-semibold text-gray-900 dark:text-white"
                    >
                        Email Notifications
                    </div>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                id="email1"
                                name="email1"
                                class="size-4 rounded-sm border border-gray-200 text-primary-500 checked:border-primary-500 focus:border-primary-500 focus:ring-3 focus:ring-primary-500/50 dark:border-gray-700 dark:bg-gray-900 dark:ring-offset-gray-900 dark:checked:border-transparent dark:checked:bg-primary-500 dark:focus:border-primary-500"
                                checked
                            />
                            <span class="ml-2 text-sm">Account related</span>
                        </label>
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                id="email2"
                                name="email2"
                                class="size-4 rounded-sm border border-gray-200 text-primary-500 checked:border-primary-500 focus:border-primary-500 focus:ring-3 focus:ring-primary-500/50 dark:border-gray-700 dark:bg-gray-900 dark:ring-offset-gray-900 dark:checked:border-transparent dark:checked:bg-primary-500 dark:focus:border-primary-500"
                                checked
                            />
                            <span class="ml-2 text-sm"
                                >Product announcements</span
                            >
                        </label>
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                id="email3"
                                name="email3"
                                class="size-4 rounded-sm border border-gray-200 text-primary-500 checked:border-primary-500 focus:border-primary-500 focus:ring-3 focus:ring-primary-500/50 dark:border-gray-700 dark:bg-gray-900 dark:ring-offset-gray-900 dark:checked:border-transparent dark:checked:bg-primary-500 dark:focus:border-primary-500"
                                checked
                            />
                            <span class="ml-2 text-sm"
                                >News and promotions</span
                            >
                        </label>
                    </div>
                </div>
                <div class="border-t border-gray-200 pt-6 dark:border-gray-700">
                    <div class="space-y-3">
                        <div
                            class="text-sm font-semibold text-gray-900 dark:text-white"
                        >
                            Display Preferences
                        </div>
                        <div class="space-y-1">
                            <label
                                for="language"
                                class="inline-block text-sm font-medium"
                                >Language</label
                            >
                            <select
                                id="language"
                                name="language"
                                class="block w-full rounded-lg border border-gray-200 px-3 py-2 text-sm leading-6 focus:border-primary-500 focus:ring-3 focus:ring-primary-500/50 dark:border-gray-700 dark:bg-gray-900 dark:focus:border-primary-500"
                            >
                                <option>English (US)</option>
                                <option>Spanish</option>
                                <option>French</option>
                                <option>German</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label
                                for="timezone"
                                class="inline-block text-sm font-medium"
                                >Timezone</label
                            >
                            <select
                                id="timezone"
                                name="timezone"
                                class="block w-full rounded-lg border border-gray-200 px-3 py-2 text-sm leading-6 focus:border-primary-500 focus:ring-3 focus:ring-primary-500/50 dark:border-gray-700 dark:bg-gray-900 dark:focus:border-primary-500"
                            >
                                <option>UTC -5:00 (Eastern Time)</option>
                                <option>UTC -6:00 (Central Time)</option>
                                <option>UTC -7:00 (Mountain Time)</option>
                                <option>UTC -8:00 (Pacific Time)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div
                    class="flex gap-3 border-t border-gray-200 pt-6 dark:border-gray-700"
                >
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-primary-700 bg-primary-700 px-4 py-2 text-sm font-semibold text-white hover:border-primary-600 hover:bg-primary-600 focus:ring-3 focus:ring-primary-400/50 active:border-primary-700 active:bg-primary-700 dark:focus:ring-primary-400/90"
                    >
                        Save changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
