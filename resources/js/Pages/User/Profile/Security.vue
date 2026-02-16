<script setup>
import TwoFactor from "./Security/TwoFactor.vue";
import UpdatePassword from "./Security/UpdatePassword.vue";
</script>
<template>
    <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
        <UpdatePassword />
        <TwoFactor />
    </div>
</template>
