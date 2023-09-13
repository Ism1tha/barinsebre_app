<template>
    <!-- Confirmation modal -->
    <Dialog v-model:visible="confirmation_modal_visible" modal header="Confirmar comanda"
        :style="{ width: '500px', maxWidth: '98%' }">
        <p>Confirmo que he revisat la comanda i que vull realitzar-la per al torn de
            <b>{{ time_selected == 1 ? 'matí (11:00)' : 'tarda (18:00)' }}</b>.
        </p>
        <ul class="my-4 list-disc list-inside">
            <template v-for="(item, index) in product_types">
                <li v-if="item.total > 0">
                    {{ item.name }} ({{ item.total }} unitats)
                </li>
            </template>
        </ul>
        <div class="mt-8">
            <button class="bg-green-500 px-4 py-2 rounded text-white mr-2"
                @click="confirmation_modal_visible = false; createOrder();">Confirmar</button>
            <button class="bg-red-500 px-4 py-2 rounded text-white"
                @click="confirmation_modal_visible = false">Cancelar</button>
        </div>
    </Dialog>
    <!-- END Confirmation modal-->
    <div class="flex flex-col justify-between md:flex-row">
        <div class="shadow rounded px-4 py-2 inline mb-4 text-gray-600">
            <i class="pi pi-calendar text-blue-500"></i>
            {{ today_date }}
        </div>
        <div class="shadow rounded px-4 py-2 inline mb-4 text-gray-600">
            <i class="pi pi-user text-blue-500"></i>
            {{ this.$page.props.auth.user.name }}
        </div>
    </div>
    <template v-if="can_order">
        <div class="border-t border-gray my-2 py-2">
            <span class="text-gray-700 font-bold">Selecciona el torn per al que vols realitzar la comanda
                d'entrepans:</span>
            <div class="grid md:grid-cols-4 gap-4">
                <div class="rounded px-4 py-2 inline md:mb-4 text-gray-600 cursor-pointer border-2"
                    :class="time_selected == 1 ? 'border-blue-500' : ''" @click="time_selected = 1">
                    <i class="pi pi-clock text-blue-500"></i>
                    Mati (11:00)
                </div>
                <div class="rounded px-4 py-2 inline mb-4 text-gray-600 cursor-pointer border-2"
                    :class="time_selected == 2 ? 'border-blue-500' : ''" @click="time_selected = 2">
                    <i class="pi pi-clock text-blue-500"></i>
                    Tarda (18:00)
                </div>
            </div>
        </div>
        <div class="border-t border-gray my-2 py-2">
            <span class="text-gray-700 font-bold">Selecciona els entrepans que vols encomanar:</span>
            <div class="grid gap-4" v-for="(item, index) in product_types">
                <div
                    class="rounded border-2 px-4 py-2 inline md:mb-4 text-gray-600 flex justify-between items-center col-span-8">
                    <div>
                        <i class="pi pi-list text-gray mr-2"></i>
                        <b class="text-primary-800">{{item.total}}</b> {{ item.name }} <span class="text-sm">({{ item.price }}€)</span>
                    </div>
                    <div class="flex items-center">
                        <button class="bg-green-500 hover:bg-green-800 rounded text-white px-4 py-1 mr-1"
                            @click="increaseTotal(index)"><i class="pi pi-plus" style="font-size: 0.5em;"></i></button>
                        <button class="bg-red-500 hover:bg-red-600 rounded text-white px-4 py-1"
                            @click="decreaseTotal(index)"><i class="pi pi-minus" style="font-size: 0.5em;"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end">
            <button class="bg-green-500 hover:bg-green-400 rounded text-white px-4 py-2 mb-2" @click="validateForm">
                <i class="pi pi-check"></i>
                Realitzar comanda
            </button>
        </div>
    </template>
    <template v-else>
        <div class="bg-green-500 rounded text-white px-4 py-2 my-4">
            <i class="pi pi-check"></i>
            La teva comanda per al torn de {{ this.$page.props.order_turn == 1 ? 'matí' : 'tarda' }} s'ha realitzat correctament.
        </div>
        <div class="text-gray-600">
            <ul>
                <li v-for="item in $page.props.order_items">{{ item.name }} x{{ item.total }}</li>
            </ul>
        </div>
    </template>
</template>

<script>
import Dialog from 'primevue/dialog';
import { Link } from '@inertiajs/vue3';
export default {
    components: {
        Dialog,
        Link,
    },
    data() {
        return {
            can_order: false,
            today_date: '',
            time_selected: 0,
            product_types: {},
            confirmation_modal_visible: false,
        };
    },
    methods: {
        increaseTotal(index) {
            this.product_types[index].total++;
        },
        decreaseTotal(index) {
            if (this.product_types[index].total > 0)
                this.product_types[index].total--;
        },
        validateForm() {
            if (this.time_selected == 0) {
                this.$toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Selecciona un torn per a la comanda (matí o tarda).',
                    life: 3000
                });
                return false;
            }
            if (this.countTotalItems() == 0) {
                this.$toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Selecciona almenys un entrepà per a la comanda.',
                    life: 3000
                });
                return false;
            }
            if (this.time_selected == 1)
                if (this.$moment().format('HH:mm') > '11:00') {
                    this.$toast.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'No es pot fer comanda de matí després de les 11:00.',
                        life: 3000
                    });
                    return false;
                }
            if (this.time_selected == 2)
                if (this.$moment().format('HH:mm') > '18:00') {
                    this.$toast.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'No es pot fer comanda de tarda després de les 18:00.',
                        life: 3000
                    });
                    return false;
                }
            this.confirmation_modal_visible = true;
        },
        createOrder() {
            var selected_products = this.parseSelectedItems();
            axios.post(route('encomanar.add'), {
                date: this.$moment().format('YYYY-MM-DD'),
                turn: this.time_selected,
                products: selected_products
            }).then(response => {
                if (response.data.id) {
                    this.$inertia.visit(route('encomanar'));
                }
            });
        },
        countTotalItems() {
            let total = 0;
            this.product_types.forEach(item => {
                total += item.total;
            });
            return total;
        },
        parseSelectedItems() {
            let selected_items = [];
            this.product_types.forEach(item => {
                if (item.total > 0)
                    selected_items.push({
                        name: item.name,
                        total: item.total
                    });
            });
            return selected_items;
        }
    },
    mounted() {
        this.can_order = this.$page.props.can_order;
        this.today_date = this.$moment().format('dddd D MMMM');
        this.today_date = this.today_date.charAt(0).toUpperCase() + this.today_date.slice(1);
        this.product_types = this.$page.props.product_types;
        this.product_types.forEach(item => {
            item.total = 0;
        });
    },
}
</script>