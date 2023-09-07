<template>
    <TabView class="tabview-custom">
        <TabPanel>
            <template #header>
                <i class="pi pi-calendar mr-2"></i>
                <span>Calendari</span>
            </template>
            <!-- Calendar tab here-->
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <Calendar v-model="selected_date" inline showWeek class="w-full md:px-2" />
                </div>
                <div class="p-1">
                    <div class="shadow rounded px-4 py-3 text-gray-600 border-2">
                        <i class="pi pi-calendar text-blue-500 mr-2"></i>
                        <template v-if="selected_date == null">
                            No has seleccionat cap data.
                        </template>
                        <template v-else>
                            {{ this.$moment(selected_date).format('dddd, D MMMM').charAt(0).toUpperCase() +
                                this.$moment(selected_date).format('dddd, D MMMM').slice(1) }}
                        </template>
                    </div>
                    <template v-if="selected_date != null">
                        <Dropdown v-model="selected_date_menu_template" :options="menu_templates" optionLabel="name"
                            placeholder="Seleccionar menú" class="w-full md:w-14rem mt-2 text-sm" />
                        <div class="bg-green-500 hover:bg-gray-800 inline-block rounded text-white px-4 py-2 mb-2 mt-4 cursor-pointer"
                            @click="saveSettings"><i class="pi pi-check"></i> Assignar menú</div>
                    </template>
                </div>
            </div>
        </TabPanel>
        <TabPanel>
            <template #header>
                <i class="pi pi-file-edit mr-2"></i>
                <span>Plantilles</span>
            </template>
            <!-- Templates tab here-->
            <div class="flex justify-end">
                <div class="bg-green-500 hover:bg-gray-800 rounded text-white px-4 py-2 mb-2 mt-4 mr-2 cursor-pointer"
                    @click="addMenuTemplate">
                    <i class="pi pi-plus"></i> Afegir nova plantilla
                </div>
            </div>
            <DataTable :value="menu_templates" tableStyle="min-width: 50rem"
                v-model:expandedRows="expanded_menu_templates_rows" dataKey="id">
                <Column expander style="width: 5rem" />
                <Column field="id" header="Codi"></Column>
                <Column field="name" header="Nom de la plantilla de menú">
                    <template #body="slotProps">
                        <input type="text"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            v-model="this.menu_templates[slotProps.index].name">
                    </template>
                </Column>
                <Column>
                    <template #body="slotProps">
                        <div class="flex justify-end">
                            <button class="rounded text-white px-4 py-1 mr-1 cursor-pointer"
                                @click="deleteMenuTemplate(slotProps.index)"><i
                                    class="pi pi-trash text-red-500"></i></button>
                        </div>
                    </template>
                </Column>
                <template #empty> No hi ha cap plantilla de menú creada. </template>
                <template #expansion="slotProps">
                    <!-- Primer plat-->
                    <div class="flex justify-between items-center my-2 rounded">
                        <div class="font-bold text-xs uppercase text-gray-700 block">Opcions, primer plat</div>
                        <div class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 text-md rounded cursor-pointer md:w-1/6 text-center"
                            @click="addMenuTemplateItem(slotProps.index, 'primer')">Afegir primer plat</div>
                    </div>
                    <DataTable :value="menu_templates[slotProps.index].products.primer" tableStyle="min-width: 50rem">
                        <Column field="name" header="Nom del producte">
                            <template #body="slotProps2">
                                <input type="text"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    v-model="this.menu_templates[slotProps.index].products.primer[slotProps2.index].name">
                            </template>
                        </Column>
                        <Column>
                            <template #body="slotProps2">
                                <div class="flex justify-end">
                                    <button class="rounded text-white px-4 py-1 mr-1 cursor-pointer"
                                        @click="deleteMenuTemplateItem(slotProps.index, 'primer', slotProps2.index)"><i
                                            class="pi pi-trash text-red-500"></i></button>
                                </div>
                            </template>
                        </Column>
                        <template #empty> No hi ha cap producte disponible per a encomanar. </template>
                    </DataTable>
                    <!-- Segon plat-->
                    <div class="flex justify-between items-center my-2 rounded">
                        <div class="font-bold text-xs uppercase text-gray-700 block">Opcions, segon plat</div>
                        <div class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 text-md rounded cursor-pointer md:w-1/6 text-center"
                            @click="addMenuTemplateItem(slotProps.index, 'segon')">Afegir segon plat</div>
                    </div>
                    <DataTable :value="menu_templates[slotProps.index].products.segon" tableStyle="min-width: 50rem">
                        <Column field="name" header="Nom del producte">
                            <template #body="slotProps2">
                                <input type="text"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    v-model="this.menu_templates[slotProps.index].products.segon[slotProps2.index].name">
                            </template>
                        </Column>
                        <Column>
                            <template #body="slotProps2">
                                <div class="flex justify-end">
                                    <button class="rounded text-white px-4 py-1 mr-1 cursor-pointer"
                                        @click="deleteMenuTemplateItem(slotProps.index, 'segon', slotProps2.index)"><i
                                            class="pi pi-trash text-red-500"></i></button>
                                </div>
                            </template>
                        </Column>
                        <template #empty> No hi ha cap producte disponible per a encomanar. </template>
                    </DataTable>
                    <!-- Tercer plat-->
                    <div class="flex justify-between items-center my-2 rounded">
                        <div class="font-bold text-xs uppercase text-gray-700 block">Opcions, postre</div>
                        <div class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 text-md rounded cursor-pointer md:w-1/6 text-center"
                            @click="addMenuTemplateItem(slotProps.index, 'postre')">Afegir postre</div>
                    </div>
                    <DataTable :value="menu_templates[slotProps.index].products.postre" tableStyle="min-width: 50rem">
                        <Column field="name" header="Nom del producte">
                            <template #body="slotProps2">
                                <input type="text"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    v-model="this.menu_templates[slotProps.index].products.postre[slotProps2.index].name">
                            </template>
                        </Column>
                        <Column>
                            <template #body="slotProps2">
                                <div class="flex justify-end">
                                    <button class="rounded text-white px-4 py-1 mr-1 cursor-pointer"
                                        @click="deleteMenuTemplateItem(slotProps.index, 'postre', slotProps2.index)"><i
                                            class="pi pi-trash text-red-500"></i></button>
                                </div>
                            </template>
                        </Column>
                        <template #empty> No hi ha cap producte disponible per a encomanar. </template>
                    </DataTable>
                </template>
            </DataTable>
        </TabPanel>
    </TabView>
    <div class="flex justify-end">
        <div class="bg-green-500 hover:bg-gray-800 rounded text-white px-4 py-2 mb-2 mt-4 cursor-pointer"
            @click="saveSettings"><i class="pi pi-check"></i> Desar canvis</div>
    </div>
</template>

<script>
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        TabView,
        TabPanel,
        DataTable,
        Column,
        Link,
        Calendar,
        Dropdown
    },
    data() {
        return {
            enabled: false,
            selected_date: null,
            selected_date_menu_template: null,
            menu_templates: {},
            expanded_menu_templates_rows: [],
            loading_assignation: false
        }
    },
    mounted() {
        this.enabled = this.$page.props.settings.menjador.enabled;
        this.fetchMenuTemplates();
    },
    watch: {
        selected_date: function (newVal, oldVal) {
            this.fetchCurrentDateMenuTemplate();
        }
    },
    methods: {
        fetchMenuTemplates() {
            axios.get(route('admin.settings.menjador.menu_templates')).then(response => {
                this.menu_templates = response.data.menu_templates;
            });
        },
        fetchCurrentDateMenuTemplate() {
            this.loading_assignation = true;
            var parsed_date = this.$moment(this.selected_date).format('YYYY-MM-DD');
            axios.get(route('admin.settings.menjador.assignation.get', parsed_date)).then(response => {
                if (response.data.menu_assignation != null) {
                    this.selected_date_menu_template = response.data.menu_assignation.menu_template;
                }
                else {
                    this.selected_date_menu_template = null;
                }
                this.loading_assignation = false;
            });
        },
        addMenuTemplate() {
            axios.get(route('admin.settings.menjador.add')).then(response => {
                this.menu_templates.push({
                    id: response.data.id,
                    name: "Nova plantilla de menú",
                    products: {
                        primer: [],
                        segon: [],
                        postre: []
                    }
                });
            });
            this.$toast.add({ severity: 'success', summary: 'Producte afegit', detail: 'El producte s\'ha afegit correctament.', life: 3000 });
        },
        addMenuTemplateItem(template_id, location) {
            this.menu_templates[template_id].products[location].push({
                name: "Nou plat",
            });
        },
        deleteMenuTemplate(index) {
            axios.get(route('admin.settings.menjador.delete', this.menu_templates[index].id)).then(response => {
                this.menu_templates.splice(index, 1);
                this.$toast.add({ severity: 'success', summary: 'Producte eliminat', detail: 'El producte s\'ha eliminat correctament.', life: 3000 });
            });
        },
        deleteMenuTemplateItem(template_id, location, index) {
            this.menu_templates[template_id].products[location].splice(index, 1);
            this.$toast.add({ severity: 'success', summary: 'Producte eliminat', detail: 'El plat s\'ha eliminat correctament.', life: 3000 });
        },
        saveSettings() {
            axios.post(route('admin.settings.menjador.update'), {
                enabled: this.enabled,
                menu_templates: this.menu_templates,
                selected_date: this.selected_date ? this.$moment(this.selected_date).format('YYYY-MM-DD') : null,
                selected_date_menu_template: this.selected_date_menu_template ? this.selected_date_menu_template.id : null
            }).then(response => {
                this.$toast.add({ severity: 'success', summary: 'Configuració desada', detail: 'La configuració s\'ha desat correctament.', life: 3000 });
            });
            this.selected_date = null;
            this.selected_date_menu_template = null;
        },
    }
}
</script>