<template>
  <div
    v-if="show"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
  >
    <div class="bg-gray-800 text-white p-6 rounded-lg shadow-md w-full max-w-md">
      <h2 class="text-xl font-bold mb-4">
        {{ order?.id ? 'Editar Venda' : 'Nova Venda' }}
      </h2>

      <form @submit.prevent="save">
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Valor</label>
          <input
            type="number"
            v-model.number="form.amount"
            required
            class="w-full p-2 rounded bg-gray-700 border border-gray-600"
          />
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Data</label>
          <input
            type="date"
            v-model="form.date"
            required
            class="w-full p-2 rounded bg-gray-700 border border-gray-600"
          />
        </div>

        <div class="flex justify-end space-x-2">
          <button
            type="button"
            @click="emit('close')"
            class="px-4 py-2 bg-gray-600 rounded hover:bg-gray-500"
          >
            Cancelar
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-blue-600 rounded hover:bg-blue-500"
          >
            Salvar
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue';
import type { Order } from '@/types';

interface Props {
  show: boolean;
  order: Partial<Order> | Order | null;
}

const props = defineProps<Props>();
const emit = defineEmits<{
  (e: 'save', order: Order | Partial<Order>): void;
  (e: 'close'): void;
}>();

const form = ref<Partial<Order>>({
  amount: 0,
  date: '',
  seller_id: '',
});

watch(
  () => props.order,
  (newOrder) => {
    if (newOrder) {
      let amount = 0;
      let date = '';
      if(newOrder.amount){
        amount = Number(newOrder.amount/100) ;
      }
      if(newOrder.date){
        date = new Date(newOrder.date).toISOString().split('T', 1)[0];
      }

      form.value = { ...newOrder, amount: amount, date };
    } else {
      form.value = { amount: 0, date: '', seller_id: '', id: '' };
    }
  },
  { immediate: true }
);

function save() {

  let amount = 0;
  if(form.value.amount){
    amount = Number(form.value.amount) * 100;
  }
  emit('save', {...form.value, amount: amount});
}
</script>

<style scoped>
</style>