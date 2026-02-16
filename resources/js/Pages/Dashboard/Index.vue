<script setup>
import { onMounted, ref } from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import Card from "@/Components/Global/Card.vue";
import Breadcrumbs from "@/Components/Global/Breadcrumbs.vue";
import Chart from 'chart.js/auto'; // Importa o Chart.js automaticamente
import { Users, BookOpen, GraduationCap, Activity, TrendingUp } from "lucide-vue-next";

defineOptions({ layout: AppLayout });

const props = defineProps({
    stats: Object,
    chartTrendData: Object,
    recentEnrollments: Array,
});

// Referências para as tags <canvas> no template
const statusChartRef = ref(null);
const trendChartRef = ref(null);

onMounted(() => {
    // 1. Instancia o Gráfico de Rosca (Status)
    new Chart(statusChartRef.value, {
        type: 'doughnut',
        data: {
            labels: ['Cursando', 'Concluídos', 'Pendentes', 'Cancelados'],
            datasets: [{
                data: [
                    props.stats.status.cursando,
                    props.stats.status.concluido,
                    props.stats.status.pendente,
                    props.stats.status.cancelado
                ],
                backgroundColor: ['#3b82f6', '#22c55e', '#f59e0b', '#ef4444'], // Tailwind Cores
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            },
            cutout: '70%' // Deixa a rosca mais fina e elegante
        }
    });

    // 2. Instancia o Gráfico de Barras (Tendência 6 meses)
    new Chart(trendChartRef.value, {
        type: 'bar',
        data: {
            labels: props.chartTrendData.labels,
            datasets: [{
                label: 'Novas Matrículas',
                data: props.chartTrendData.data,
                backgroundColor: '#4f46e5', // Primary-600
                borderRadius: 6, // Arredonda as pontas das barras
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false } // Esconde a legenda para visual mais limpo
            },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }, // Não mostra números decimais
                x: { grid: { display: false } } // Remove linhas de grade horizontais
            }
        }
    });
});

const getStatusClasses = (status) => {
    const classes = {
        cursando: 'bg-blue-100 text-blue-800',
        pendente: 'bg-amber-100 text-amber-800',
        concluido: 'bg-green-100 text-green-800',
        cancelado: 'bg-red-100 text-red-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Breadcrumbs title="Dashboard" description="Visão geral e métricas do seu ambiente de ensino." />

    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-4">
                <div class="p-3 bg-primary-100 text-primary-600 rounded-lg"><Users class="size-8" /></div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total de Estudantes</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.students }}</h3>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-4">
                <div class="p-3 bg-purple-100 text-purple-600 rounded-lg"><BookOpen class="size-8" /></div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Cursos Ativos</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.courses }}</h3>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-4">
                <div class="p-3 bg-teal-100 text-teal-600 rounded-lg"><GraduationCap class="size-8" /></div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Professores Cadastrados</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.teachers }}</h3>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <Card class="lg:col-span-2">
                <template #header>
                    <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <TrendingUp class="size-5 text-gray-400" />
                        Evolução de Matrículas (Últimos 6 meses)
                    </h3>
                </template>
                <template #content>
                    <div class="h-64 relative w-full">
                        <canvas ref="trendChartRef"></canvas>
                    </div>
                </template>
            </Card>

            <Card class="lg:col-span-1">
                <template #header>
                    <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <Activity class="size-5 text-gray-400" />
                        Distribuição por Status
                    </h3>
                </template>
                <template #content>
                    <div class="h-64 relative w-full flex justify-center">
                        <canvas ref="statusChartRef"></canvas>
                    </div>
                </template>
            </Card>
        </div>

        <Card>
            <template #header>
                <h3 class="font-bold text-gray-900 dark:text-white">Últimos Alunos Matriculados</h3>
            </template>
            <template #content>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 uppercase text-xs font-bold text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3 rounded-tl-lg">Aluno</th>
                                <th class="px-4 py-3">Curso</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 rounded-tr-lg">Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="enrollment in recentEnrollments" :key="enrollment.id" class="border-b dark:border-gray-700 last:border-0">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="size-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center font-bold text-gray-500 shrink-0">
                                            {{ enrollment.student.name.charAt(0) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900 dark:text-white">{{ enrollment.student.name }}</span>
                                            <span class="text-xs text-gray-500">{{ enrollment.student.email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-700 dark:text-gray-300">{{ enrollment.course.name }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-full" :class="getStatusClasses(enrollment.status)">
                                        {{ enrollment.status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs">{{ new Date(enrollment.created_at).toLocaleDateString() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>
        </Card>
    </div>
</template>