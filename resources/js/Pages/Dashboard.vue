<template>
  <Head title="Dashboard" />
  <div class="min-h-screen bg-gray-900 text-white">
    <header class="bg-gray-800 p-4 shadow-md flex justify-between items-center">
      <div class="flex gap-3 items-center">
        <a href="/logout" class="text-white hover:underline">Sair</a>
        |
        <h1 class="text-2xl font-bold">Minha Dashboard</h1>
      </div>
      
      <button class="bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded text-white" @click="openNewOrderModal">
        Nova Venda
      </button>
    </header>

    <main class="p-6">
      <Comission :orders="orders" class="mb-6"/>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <OrderChart :orders="orders" />
        <OrderList :orders="orders" @edit="openEditOrderModal" />
      </div>
    </main>

    <OrderModal v-if="showModal" :show="showModal" :order="selectedOrder" @close="closeModal" @save="createOrder" />
  </div>
</template>

<script lang="ts" setup>
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import OrderChart from '@/Components/OrderChart.vue';
import OrderList from '@/Components/OrderList.vue';
import OrderModal from '@/Components/OrderModal.vue';
import type { Order, Seller } from '@/types';
import ToastService from '@/utils/toast';
import Comission from '@/Components/Comission.vue';


const { seller } = defineProps<{ orders: Order[] | [], seller: Seller }>();

const orders = ref<Order[]>([]);
const showModal = ref(false);
const selectedOrder = ref<Partial<Order> | null>(null);

const fetchOrders = async () => {
  try {
    const { data: content } = await axios.get('/orders');
    const { data, success } = content;
    if (success) {
      orders.value = data.orders;
    }

  }
  catch (e) {
    console.log(e)
  }
};

const openNewOrderModal = () => {
  selectedOrder.value = null;
  showModal.value = true;
};

const openEditOrderModal = (id: string) => {
  const order = orders.value.find((order: Order) => order.id === id);
  if (order) {
    selectedOrder.value = order
    showModal.value = true;
  };
};

const closeModal = () => {
  showModal.value = false;
};

const createOrder = async (order: Partial<Order>) => {
  if (order.amount != null && order.amount <= 0) {
    ToastService.error('O amount deve ser maior que 0');
    return
  }

  console.log('cria uma order', order);

  order.seller_id = seller.id;

  try {
    await axios.post('/orders', order);
    refreshOrders();
  }
  catch (e) {
    console.log(e)
  }

}


const refreshOrders = async () => {
  await fetchOrders();
  closeModal();
};

onMounted(fetchOrders);
</script>

<style scoped>
body {
  @apply bg-gray-900;
}
</style>
