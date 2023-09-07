<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Book from '@/Components/Menjador/Book.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
</script>

<template>
    <AppLayout title="Menjador">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-600 leading-tight">
                Menjador
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <div class="flex justify-end">
                    <Link :href="route('dashboard')" class="bg-gray-700 hover:bg-gray-800 rounded text-white px-4 py-2 mb-2"><i class="pi pi-chevron-left"></i> Tornar a la pàgina principal</Link>
                </div>
                <div class="p-6 lg:p-8 bg-white overflow-hidden sm:rounded-lg border-t-4 border-blue-500">
                    <Book />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
