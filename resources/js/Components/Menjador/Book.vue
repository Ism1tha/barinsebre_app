<template>
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
    <template v-if="can_book">
        <!-- New booking section-->
        <template v-if="!assignation">
            <div class="bg-blue-500 rounded text-white px-4 py-2">
                <i class="pi pi-info-circle"></i>
                Sembla que no han assignat cap menú per al dia d'avui. Torna a intentar-ho més tard.
            </div>
        </template>
        <template v-else>

            <div class="mt-4 text-gray-500 font-bold">
                <p>Sembla que encara no t'has apuntat al menjador per al dia d'avui. Selecciona el menú que vols i fes clic
                    al
                    botó
                    per a apuntar-te.</p>
            </div>
            <span class="text-gray-500 font-bold block mt-8"><i class="pi pi-clock mr-2"></i>Selecciona l'hora per dinar:</span>
            <Calendar id="calendar-timeonly" class="text-gray-600" v-model="time" timeOnly />
            <div class="border-t border-gray my-2 py-2" v-if="$page.props.menu.primer.length > 0">
                <span class="text-gray-500 font-bold">Selecciona el primer plat del teu menú:</span>
                <div class="grid md:grid-cols-4 gap-4">
                    <div class="rounded px-4 py-2 inline mb-2 border border-gray text-gray-600 cursor-pointer border-2 flex items-center justify-center"
                        v-for="(item, index) in $page.props.menu.primer"
                        :class="food1_selected == index ? 'border-blue-500' : ''" @click="food1_selected = index">
                        {{ item.name }}
                    </div>
                </div>
            </div>
            <div class="border-t border-gray my-2 py-2" v-if="$page.props.menu.segon.length > 0">
                <span class="text-gray-500 font-bold">Selecciona el segon plat del teu menú:</span>
                <div class="grid md:grid-cols-4 gap-4">
                    <div class="rounded px-4 py-2 inline mb-2 border border-gray text-gray-600 cursor-pointer border-2 flex items-center justify-center"
                        v-for="(item, index) in $page.props.menu.segon"
                        :class="food2_selected == index ? 'border-blue-500' : ''" @click="food2_selected = index">
                        {{ item.name }}
                    </div>
                </div>
            </div>
            <div class="border-t border-gray my-2 py-2" v-if="$page.props.menu.postre.length > 0">
                <span class="text-gray-700 font-bold">Selecciona el postre del teu menú:</span>
                <div class="grid md:grid-cols-4 gap-4">
                    <div class="rounded px-4 py-2 inline mb-4 border border-gray text-gray-600 cursor-pointer border-2 flex items-center justify-center"
                        v-for="(item, index) in $page.props.menu.postre"
                        :class="food3_selected == index ? 'border-blue-500' : ''" @click="food3_selected = index">
                        {{ item.name }}
                    </div>
                </div>
            </div>
            <!-- End New booking section -->
            <div class="flex justify-end">
                <button class="bg-green-500 hover:bg-green-400 rounded text-white px-4 py-2 mb-2" @click="validateForm">
                    <i class="pi pi-check"></i>
                    Apuntar-se al menjador
                </button>
            </div>
        </template>
    </template>
    <template v-else>
        <div class="bg-green-500 rounded text-white px-4 py-2 my-4">
            <i class="pi pi-check"></i>
            T'has apuntat al menjador per al dia d'avui. En cas de modificacions o cancel·lacions, contacta amb el personal
            del menjador.
        </div>
    </template>
</template>

<script>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Calendar from 'primevue/calendar';
export default {
    components: {
        Calendar,
        Head,
        Link,
        useForm,
    },
    data() {
        return {
            time: '',
            can_book: false,
            assignation: false,
            today_date: '',
            food1_selected: -1,
            food2_selected: -1,
            food3_selected: -1,
        };
    },
    mounted() {
        this.can_book = this.$page.props.can_book;
        this.today_date = this.$moment().format('dddd D MMMM');
        this.today_date = this.today_date.charAt(0).toUpperCase() + this.today_date.slice(1);
        this.assignation = this.$page.props.assignation;
        this.time = this.$moment().hour(13).minute(0).toDate();
    },
    methods: {
        validateForm() {
            if ((this.food1_selected == -1 && this.$page.props.menu.primer.length > 0) && (this.food2_selected == -1 && this.$page.props.menu.segon.length > 0) && (this.food3_selected == -1 && this.$page.props.menu.postre.length > 0)) {
                this.$toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Has de seleccionar un primer plat, un segon plat i un postre.',
                    life: 3000,
                });
            } else {
                this.executeBooking();
            }
        },
        executeBooking() {
            var food1 = this.food1_selected == -1 ? 'Sense primer plat' : this.$page.props.menu.primer[this.food1_selected].name;
            var food2 = this.food2_selected == -1 ? 'Sense segon plat' : this.$page.props.menu.segon[this.food2_selected].name;
            var food3 = this.food3_selected == -1 ? 'Sense postre' : this.$page.props.menu.postre[this.food3_selected].name;
            console.log("Time: " + this.$moment(this.time).format('HH:mm'));
            axios.post(route('menjador.add'), {
                date: this.$moment().format('YYYY-MM-DD'),
                time: this.$moment(this.time).format('HH:mm'),
                products: {
                    food1: food1,
                    food2: food2,
                    food3: food3
                }
            }).then((response) => {
                if (response.data.id) {
                    this.$inertia.visit('menjador');
                } else {
                    this.$toast.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'Hi ha hagut un error al realitzar la comanda.',
                        life: 3000,
                    });
                }
            });
        },
        increaseHour() {
            if (this.hour < 23) {
                this.hour++;
            }
        },
        decreaseHour() {
            if (this.hour > 0) {
                this.hour--;
            }
        },
        increaseMinute() {
            if (this.minute < 59) {
                this.minute++;
            }
        },
        decreaseMinute() {
            if (this.minute > 0) {
                this.minute--;
            }
        },
    }
}
</script>