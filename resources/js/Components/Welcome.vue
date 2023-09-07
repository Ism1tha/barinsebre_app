<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="border-t-4 border-blue-500">
        <div class="p-6 lg:p-8 text-gray-600 bg-white">
            <p>Amb la nostra aplicació, fer comandes d'entrepans i apuntar-se al menjador és molt
                senzill. Professors i alumnes, planifiqueu i gaudiu del dinar amb comoditat i eficiència:</p>
            <ul class="list-disc list-inside my-4">
                <li>Reserveu un lloc al menjador per a l'hora de dinar.</li>
                <li>Trieu els vostres entrepans preferits i feu la comanda aquí.</li>
            </ul>
            <p>Simplifiquem la vostra experiència de dinar a l'institut. Bon profit!</p>
            <div class="mt-6">
                <Link :href="route('menjador')"
                    class="text-white bg-blue-500 hover:bg-blue-400 py-2 px-4 rounded mr-2 block w-full mt-2 md:inline">
                <i class="pi pi-calendar-plus"></i>
                Apuntar-se al menjador
                </Link>
                <Link :href="route('encomanar')"
                    class="text-white bg-blue-500 hover:bg-blue-400 py-2 px-4 rounded block w-full mt-2 md:inline">
                <i class="pi pi-book"></i>
                Fer una comanda d'entrepans
                </Link>
            </div>
        </div>
    </div>
</template>
