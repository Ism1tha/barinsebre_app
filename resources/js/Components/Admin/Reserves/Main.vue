<template>
    <div class="flex flex-col md:flex-row justify-between">
        <div class="shadow rounded px-4 py-2 inline mb-4 text-gray-600 bg-white">
            <i class="pi pi-calendar text-blue-500"></i>
            {{ this.$moment(date).format('dddd, D MMMM') }}
        </div>
        <div>
            <!-- Dia anterior, dia posterior -->
            <button class="bg-blue-500 hover:bg-blue-600 rounded text-white px-4 py-2 mb-2 mr-2" @click="previousDay">
                <i class="pi pi-chevron-left"></i>
                Dia següent
            </button>
            <button class="bg-blue-500 hover:bg-blue-600 rounded text-white px-4 py-2 mb-2" @click="nextDay">
                Dia posterior
                <i class="pi pi-chevron-right"></i>
            </button>
        </div>
    </div>
    <div class="grid md:grid-cols-4 gap-4 mt-4">
        <div class="col md:col-span-3 overflow-x-scroll md:overflow-x-auto">
            <DataTable :value="orders" v-model:expandedRows="expandedRows">
                <Column expander style="width: 5rem" />
                <Column field="user.name" header="Nom"></Column>
                <Column field="user.email" header="Correu"></Column>
                <Column header="Hora">
                    <template #body="slotProps">
                        {{ this.$moment(slotProps.data.created_at).format('HH:ss') }}
                    </template>
                </Column>
                <template #empty> No hi ha cap reserva per al dia seleccionat. </template>
                <template #expansion="slotProps">
                    <div class="bg-gray-100 text-gray-700 px-4 py-2 rounded my-2">
                        Plat 1 seleccionat: {{ JSON.parse(slotProps.data.products).food1 }}
                    </div>
                    <div class="bg-gray-100 text-gray-700 px-4 py-2 rounded my-2">
                        Plat 2 seleccionat: {{ JSON.parse(slotProps.data.products).food1 }}
                    </div>
                    <div class="bg-gray-100 text-gray-700 px-4 py-2 rounded my-2">
                        Postre seleccionat: {{ JSON.parse(slotProps.data.products).food1 }}
                    </div>
                </template>
            </DataTable>
        </div>
        <div class="col col-span-1">
            <div class="bg-green-500 hover:bg-green-800 rounded text-white px-4 py-2 mb-2 cursor-pointer"
                @click="fetchBookings"><i class="pi pi-refresh"></i> Actualitzar</div>
            <div class="bg-white py-2 mt-2 rounded text-center text-gray-600">
                <i class="pi pi-users"></i>
                Total persones: {{ orders.length }}
            </div>
            <div class="bg-white py-2 mt-2 rounded text-center text-gray-600" v-for="item in products">
                Total {{ item.name }}: {{ item.total }}
            </div>
        </div>
    </div>
</template>

<script>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
export default {
    components: {
        DataTable,
        Column,
    },
    data() {
        return {
            orders: [],
            products: [],
            expandedRows: [],
        }
    },
    mounted() {
        this.date = this.$moment().format('YYYY-MM-DD');
        this.fetchBookings();
    },
    methods: {
        previousDay() {
            this.date = this.$moment(this.date).subtract(1, 'days').format('YYYY-MM-DD');
            this.fetchBookings();
        },
        nextDay() {
            this.date = this.$moment(this.date).add(1, 'days').format('YYYY-MM-DD');
            this.fetchBookings();
        },
        fetchBookings() {
            axios.get(route('admin.reserves.index', this.date)).then(response => {
                this.orders = response.data;
                this.products = this.getProducts();
            });
        },
        getProducts() {
            let products = [];
            this.orders.forEach(order => {
                let order_products = JSON.parse(order.products);
                if (products.length == 0) {
                    products.push({
                        name: order_products.food1,
                        total: 1,
                    });
                    products.push({
                        name: order_products.food2,
                        total: 1,
                    });
                    products.push({
                        name: order_products.food3,
                        total: 1,
                    });
                } else {
                    let food1_exists = false;
                    let food2_exists = false;
                    let food3_exists = false;
                    products.forEach(product => {
                        if (product.name == order_products.food1) {
                            product.total++;
                            food1_exists = true;
                        }
                        if (product.name == order_products.food2) {
                            product.total++;
                            food2_exists = true;
                        }
                        if (product.name == order_products.food3) {
                            product.total++;
                            food3_exists = true;
                        }
                    });
                    if (!food1_exists) {
                        products.push({
                            name: order_products.food1,
                            total: 1,
                        });
                    }
                    if (!food2_exists) {
                        products.push({
                            name: order_products.food2,
                            total: 1,
                        });
                    }
                    if (!food3_exists) {
                        products.push({
                            name: order_products.food3,
                            total: 1,
                        });
                    }
                }
            });
            return products;
        },
    }
}
</script>