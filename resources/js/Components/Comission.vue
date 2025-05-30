<script setup lang="ts">
import { defineProps, computed } from 'vue';

const props = defineProps<{ 
  orders: { id: string; amount: number; date: string }[] 
}>();

const comission = computed(() => {
    if(props.orders.length > 0){
      const amount =  props.orders.reduce((acc, order) => acc + order.amount, 0);
      return (amount/100) * 0.085;
    }
    return 0;
})

</script>

<template>
  <div class="bg-gray-800 rounded-xl p-4 shadow">
    <h2 class="text-white text-lg font-bold mb-4">Comissão do mês</h2>
    <strong>R$ {{ comission.toFixed(2).replace('.', ',') }}</strong>
  </div>
</template>
