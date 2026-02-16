<script setup>
import StundantLayout from "@/Layouts/StundantLayout.vue";
import {
    BookOpen,
    CheckCircle,
    Clock,
    PlayCircle,
    Trophy,
} from "lucide-vue-next";

defineOptions({ layout: StundantLayout });

defineProps({
    stats: Object,
    activeEnrollments: Array,
    completedEnrollments: Array,
    pendingEnrollments: Array,
});
</script>

<template>
    <div class="space-y-8">
        <div
            class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-2xl p-8 text-white shadow-lg"
        >
            <h1 class="text-3xl font-bold mb-2">
                Olá, {{ $page.props.auth.user.name.split(" ")[0] }}! 👋
            </h1>
            <p class="text-primary-100 opacity-90">
                Bem-vindo(a) de volta ao seu ambiente de aprendizagem.
            </p>

            <div class="mt-6 flex flex-wrap gap-4">
                <div
                    class="bg-white/10 backdrop-blur-sm rounded-lg p-4 flex items-center gap-3 min-w-[150px]"
                >
                    <PlayCircle class="size-8 text-primary-200" />
                    <div>
                        <p class="text-2xl font-bold">{{ stats.active }}</p>
                        <p
                            class="text-xs text-primary-200 uppercase tracking-wider"
                        >
                            Em Andamento
                        </p>
                    </div>
                </div>
                <div
                    class="bg-white/10 backdrop-blur-sm rounded-lg p-4 flex items-center gap-3 min-w-[150px]"
                >
                    <Trophy class="size-8 text-yellow-300" />
                    <div>
                        <p class="text-2xl font-bold">{{ stats.completed }}</p>
                        <p
                            class="text-xs text-primary-200 uppercase tracking-wider"
                        >
                            Concluídos
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="pendingEnrollments.length > 0"
            class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-lg shadow-sm"
        >
            <div class="flex items-center gap-2 text-amber-800 font-bold mb-2">
                <Clock class="size-5" />
                <span>Você tem matrículas aguardando liberação</span>
            </div>
            <p class="text-sm text-amber-700">
                Seus cursos:
                <span class="font-medium">{{
                    pendingEnrollments.map((e) => e.course.name).join(", ")
                }}</span>
                estão com status pendente. Aguarde a confirmação da
                administração.
            </p>
        </div>

        <div>
            <h2
                class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2"
            >
                <BookOpen class="size-6 text-primary-600" />
                Continuar Estudando
            </h2>

            <div
                v-if="activeEnrollments.length === 0"
                class="text-gray-500 bg-white dark:bg-gray-800 p-8 rounded-xl text-center border border-dashed border-gray-300 dark:border-gray-700"
            >
                Você não possui cursos em andamento no momento.
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="enrollment in activeEnrollments"
                    :key="enrollment.id"
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow group"
                >
                    <div class="h-32 bg-gray-100 dark:bg-gray-700 relative">
                        <div
                            class="absolute inset-0 flex items-center justify-center text-gray-300 dark:text-gray-600"
                        >
                            <BookOpen class="size-12" />
                        </div>
                    </div>
                    <div class="p-5">
                        <h3
                            class="font-bold text-lg text-gray-900 dark:text-white line-clamp-1 mb-1"
                        >
                            {{ enrollment.course.name }}
                        </h3>
                        <p
                            class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4 h-10"
                        >
                            {{
                                enrollment.course.description ||
                                "Sem descrição."
                            }}
                        </p>

                        <button
                            class="w-full btn-primary bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors"
                        >
                            <PlayCircle class="size-4" /> Acessar Aulas
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="completedEnrollments.length > 0">
            <h2
                class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2"
            >
                <Trophy class="size-6 text-green-600" />
                Minhas Conquistas
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                    v-for="enrollment in completedEnrollments"
                    :key="enrollment.id"
                    class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-green-100 dark:border-green-900/30 shadow-sm flex flex-col items-center text-center hover:bg-green-50 dark:hover:bg-green-900/10 transition-colors cursor-pointer"
                >
                    <div
                        class="p-3 bg-green-100 dark:bg-green-900/50 rounded-full mb-3 text-green-600 dark:text-green-400"
                    >
                        <CheckCircle class="size-8" />
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-1">
                        {{ enrollment.course.name }}
                    </h3>
                    <p class="text-xs text-gray-500 mb-3">
                        Concluído em
                        {{
                            new Date(
                                enrollment.completed_at,
                            ).toLocaleDateString()
                        }}
                    </p>

                    <span
                        class="text-sm text-green-600 font-medium border-b border-transparent hover:border-green-600"
                        >Ver Certificado</span
                    >
                </div>
            </div>
        </div>
    </div>
</template>
