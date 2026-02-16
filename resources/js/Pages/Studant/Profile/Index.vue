<script setup>
import StundantLayout from "@/Layouts/StundantLayout.vue";

// IMPORTANTE: Ajuste estes caminhos caso os seus componentes estejam em uma pasta diferente (ex: '@/Components/Profile/...')
import Avatar from "@/Pages/User/Profile/Avatar.vue";
import Information from "@/Pages/User/Profile/Information.vue";
import Security from "@/Pages/User/Profile/Security.vue";

defineOptions({ layout: StundantLayout });
</script>

<template>
    <div class="max-w-7xl mx-auto space-y-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Meu Perfil</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Gerencie suas informações pessoais, atualize sua foto e configure a segurança da sua conta escolar.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                <Avatar />
            </div>

            <div class="lg:col-span-2 space-y-6 lg:space-y-8">
                
                <Information />
                
                <Security />
                
            </div>
        </div>
    </div>
</template>