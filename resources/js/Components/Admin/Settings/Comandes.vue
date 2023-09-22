<template>
    <div class="inline-block bg-green-500 hover:bg-gray-800 rounded text-white px-4 py-2 mb-4 mt-4 cursor-pointer" @click="addProduct"><i class="pi pi-plus"></i> Afegir nou producte</div>
    <DataTable :value="products" tableStyle="min-width: 50rem">
        <Column field="id" header="Codi"></Column>
        <Column field="name" header="Nom del producte">
            <template #body="slotProps">
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="this.products[slotProps.index].name">
            </template>
        </Column>
        <Column field="price" header="Preu">
            <template #body="slotProps">
                <input type="number" class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="this.products[slotProps.index].price">
            </template>
        </Column>
        <Column>
            <template #body="slotProps">
                <div class="flex justify-end">
                    <button class="rounded text-white px-4 py-1 mr-1 cursor-pointer" @click="deleteProduct(slotProps.index)"><i class="pi pi-trash text-red-500"></i></button>
                </div>
            </template>
        </Column>
        <template #empty> No hi ha cap producte disponible per a encomanar. </template>
    </DataTable>
    <div class="flex justify-end">
        <div class="bg-green-500 hover:bg-gray-800 rounded text-white px-4 py-2 mb-2 mt-4 cursor-pointer" @click="saveSettings"><i class="pi pi-check"></i> Desar canvis</div>
    </div>
</template>

<script>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { Link } from '@inertiajs/vue3';

export default{
    components:{
        DataTable,
        Column,
        Link
    },
    data(){
        return{
            enabled: false,
            products: []
        }
    },
    mounted(){
        this.enabled = this.$page.props.settings.comandes.enabled;
        console.log(this.$page.props.settings.comandes);
        this.products = this.$page.props.settings.comandes.product_types;
    },
    methods:{
        addProduct(){
            axios.get(route('admin.settings.comandes.add')).then(response => {
                this.products.push({
                    id: response.data.id,
                    name: "Nou producte",
                    price: 0
                });
            });
            this.$toast.add({severity:'success', summary: 'Producte afegit', detail: 'El producte s\'ha afegit correctament.', life: 3000});
        },
        deleteProduct(index){
            axios.post(route('admin.settings.comandes.delete', this.products[index].id)).then(response => {
                this.products.splice(index, 1);
                this.$toast.add({severity:'success', summary: 'Producte eliminat', detail: 'El producte s\'ha eliminat correctament.', life: 3000});
            });
        },
        saveSettings(){
            axios.post(route('admin.settings.comandes.update'), {
                enabled: this.enabled,
                product_types: this.products
            }).then(response => {
                console.log("Configuració desada");
                this.$toast.add({severity:'success', summary: 'Configuració desada', detail: 'La configuració s\'ha desat correctament.', life: 3000});
            });
        }
    }
}
</script>