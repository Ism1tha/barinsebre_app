<template>
    <div class="flex flex-col md:flex-row justify-between">
        <div>
            <div class="shadow rounded px-4 py-2 mb-4 text-gray-600 bg-white block md:inline">
                <i class="pi pi-calendar text-blue-500"></i>
                {{ this.$moment(date).format('dddd, D MMMM') }}
            </div>
            <div class="shadow rounded px-4 py-2 mb-4 text-gray-600 bg-white md:mx-2 block md:inline">
                <i class="pi pi-clock text-blue-500"></i>
                Torn seleccionat: {{ turn == 1 ? 'Mati' : 'Tarda' }}
            </div>
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
                <template #empty> No hi ha cap comanda per al dia seleccionat. </template>
                <template #expansion="slotProps">
                    <div class="bg-gray-100 text-gray-700 px-4 py-2 rounded my-2"
                        v-for="item in JSON.parse(slotProps.data.products)">
                        {{ item.name }} ({{ item.total }} unitats)
                    </div>
                </template>
            </DataTable>
        </div>
        <div class="col col-span-1">
            <div class="bg-green-500 hover:bg-green-800 rounded text-white px-4 py-2 mb-2 cursor-pointer"
                @click="fetchBookings"><i class="pi pi-refresh"></i> Actualitzar</div>
            <!-- Switch turn -->
            <div class="bg-green-500 hover:bg-green-800 rounded text-white px-4 py-2 mb-2 cursor-pointer"
                @click="switchTurn"><i class="pi pi-clock"></i> Canviar torn</div>
            <div class="bg-white py-2 mt-2 rounded text-center text-gray-600">
                <i class="pi pi-users"></i>
                Total persones: {{ orders.length }}
            </div>
            <div class="bg-white py-2 mt-2 rounded text-center text-gray-600" v-for="item in products">
                {{ item.name }}: {{ item.total }}
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
            turn: 1,
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
            console.log(this.turn);
            axios.get(route('admin.comandes.index', { date: this.date, turn: this.turn })).then(response => {
                this.orders = response.data;
                this.products = this.getProducts();
            });
        },
        getProducts() {
            let products = [];
            //Loop over all orders, check if product exists in array, if not, add it, increment by the total amount
            this.orders.forEach(order => {
                let order_products = JSON.parse(order.products);
                order_products.forEach(product => {
                    let product_index = products.findIndex(item => item.name == product.name);
                    if (product_index == -1) {
                        products.push({
                            name: product.name,
                            total: product.total,
                        });
                    } else {
                        products[product_index].total += product.total;
                    }
                });
            });
            return products;
        },
        switchTurn() {
            this.turn = this.turn == 1 ? 2 : 1;
            this.fetchBookings();
        }
    }
}
</script>