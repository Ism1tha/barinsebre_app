<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Main from '@/Components/Admin/Reserves/Main.vue';
</script>

<template>
    <AppLayout title="Reserves">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-600 leading-tight">
                Reserves
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <Main />
            </div>
        </div>
    </AppLayout>
</template>
