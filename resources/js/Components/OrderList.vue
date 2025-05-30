<script setup lang="ts">
import { defineEmits, defineProps } from 'vue';

const props = defineProps<{ 
  orders: { id: string; amount: number; date: string }[] 
}>();

const emit = defineEmits<{ (e: 'edit', id: string): void }>();
</script>

<template>
  <div class="bg-gray-800 rounded-xl p-4 shadow">
    <h2 class="text-white text-lg font-bold mb-4">Minhas Vendas</h2>
    <ul class="divide-y divide-gray-700">
      <li 
        v-for="order in props.orders" 
        :key="order.id" 
        class="py-2 flex justify-between items-center text-white"
      >
        <span>{{ new Date(order.date).toLocaleDateString() }} - R$ {{ order.amount/100 }}</span>
        <button @click="emit('edit', order.id)" class="text-green-400 hover:underline">Editar</button>
      </li>
    </ul>
  </div>
</template>
