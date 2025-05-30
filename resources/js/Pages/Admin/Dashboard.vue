<template>
    <Head title="Admin Dashboard" />
    <div class="min-h-screen bg-gray-900 text-white">
        <header
            class="bg-gray-800 p-4 shadow-md flex justify-between items-center"
        >
            <div class="flex gap-3 items-center">
                <a href="/logout" class="text-white hover:underline">Sair</a>
                |
                <h1 class="text-2xl font-bold">Dashboard do Administrador</h1>
            </div>
        </header>

        <main class="p-6 space-y-6">
            <div class="flex items-center justify-between gap-3">
                <OrderChart :orders="orders" class="max-height-900px" />
                <section class="flex flex-col md:flex-row gap-6">
                    <div class="card-size-double bg-gray-800 p-4 rounded-xl shadow text-center">
                        <h2 class="text-lg font-semibold mb-2">
                            Total de Vendas
                        </h2>
                        <p class="text-2xl text-green-400 font-bold">
                            R$ {{ totalAmount / 100 }}
                        </p>
                    </div>
                    <div class="flex gap-6 flex-col">
                        <div
                            class="bg-gray-800 card-size p-4 rounded-xl shadow text-center"
                        >
                            <h2 class="text-lg font-semibold mb-2">
                                Número de Vendas
                            </h2>
                            <p class="text-2xl text-blue-400 font-bold">
                                {{ orders.length }}
                            </p>
                        </div>
                        <div
                            class="bg-gray-800 p-4 card-size rounded-xl shadow text-center"
                        >
                            <h2 class="text-lg font-semibold mb-2">
                                Vendedores
                            </h2>
                            <p class="text-2xl text-yellow-400 font-bold">
                                {{ sellers.length }}
                            </p>
                        </div>
                    </div>
                </section>
            </div>

            <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-800 rounded-xl p-4 shadow">
                    <h2 class="text-white text-lg font-bold mb-4">
                        Lista de Vendas
                    </h2>
                    <ul class="divide-y divide-gray-700">
                        <li
                            v-for="order in orders"
                            :key="order.id"
                            class="py-2 flex justify-between items-center text-white"
                        >
                            <span>
                                {{ new Date(order.date).toLocaleDateString() }} - R$ {{ order.amount / 100 }}
                            </span>
                            <span class="text-sm text-gray-400">
                                {{ getSellerName(order.seller_id) }}
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="bg-gray-800 rounded-xl p-4 shadow">
                    <h2 class="text-white text-lg font-bold mb-4">
                        Lista de Vendedores
                    </h2>
                    <ul class="divide-y divide-gray-700">
                        <li
                            v-for="seller in sellers"
                            :key="seller.id"
                            class="py-2 flex justify-between items-center text-white"
                        >
                            <span>{{ seller.name }}</span>
                            <button
                                class="bg-indigo-600 hover:bg-indigo-700 px-3 py-1 rounded text-white text-sm"
                                @click="sendDailyReport(seller.id)"
                            >
                                Enviar Relatório Diário
                            </button>
                        </li>
                    </ul>
                </div>
            </section>
        </main>
    </div>
</template>

<script lang="ts" setup>
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import axios from "axios";
import OrderChart from "@/Components/OrderChart.vue";
import type { Order, Seller } from "@/types";
import ToastService from "@/utils/toast";

const orders = ref<Order[]>([]);
const sellers = ref<Seller[]>([]);
const totalAmount = ref(0);

const fetchOrders = async () => {
    try {
        const { data: response } = await axios.get("/orders");
        const { data: content, success } = response;
        const { data } = content;

        if (success) {
            orders.value = data;
            calculateTotalAmount();
        }
    } catch (e) {
        console.error(e);
    }
};

const fetchSellers = async () => {
    try {
        const { data: response } = await axios.get("/sellers");
        const { data: content, success } = response;
        const { data } = content;
        if (success) {
            sellers.value = data;
        }
    } catch (e) {
        console.error(e);
    }
};

const calculateTotalAmount = () => {
    totalAmount.value = orders.value.reduce(
        (sum, order) => sum + order.amount,
        0
    );
};

const getSellerName = (sellerId: string) => {
    const seller = sellers.value.find((s) => s.id === sellerId);
    return seller ? seller.name : "Desconhecido";
};

const sendDailyReport = async (sellerId: string) => {
    try {
        await axios.post(`/sellers/${sellerId}/daily-report`);
        ToastService.success("Relatório enviado com sucesso!");
    } catch (error) {
        console.error(error);
        ToastService.error("Erro ao enviar o relatório!");
    }
};

onMounted(() => {
    fetchOrders();
    fetchSellers();
});
</script>

<style scoped>
body {
    @apply bg-gray-900;
}
.max-height-900px {
    max-height: 580px;
    width: 100%;
}

.card-size, .card-size-double{
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

@media (min-width: 768px) {
    .card-size{
        width: 14vw;
        height: 14vw;
    }
    .card-size-double{
      width: 29vw;
        height: 29vw;
    }
}
</style>
