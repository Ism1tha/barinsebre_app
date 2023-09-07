<script setup>
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Menjador from '@/Components/Admin/Settings/Menjador.vue';
import Comandes from '@/Components/Admin/Settings/Comandes.vue';
</script>
<template>
    <TabView class="tabview-custom">
        <TabPanel>
            <template #header>
                <i class="pi pi-calendar mr-2"></i>
                <span>Menjador</span>
            </template>
            <Menjador />
        </TabPanel>
        <TabPanel>
            <template #header>
                <i class="pi pi-shopping-cart mr-2"></i>
                <span>Comandes</span>
            </template>
            <Comandes />
        </TabPanel>
    </TabView>
</template>