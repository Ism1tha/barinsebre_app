<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
</script>

<template>
    <AppLayout title="Home">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-600 leading-tight">
                Home
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <div class="bg-blue-500 rounded text-white px-4 py-2 mb-4">
                    <i class="pi pi-info-circle"></i>
                    Recordem que el mal ús d'aquesta aplicació pot comportar sancions disciplinàries.
                </div>
                <div class="bg-white overflow-hidden sm:rounded-lg">
                    <Welcome />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
