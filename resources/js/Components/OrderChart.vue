<script setup lang="ts">
import { defineProps, onMounted, ref, watch } from 'vue';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const props = defineProps<{ orders: { amount: number, date: string }[] }>();

const canvas = ref<HTMLCanvasElement | null>(null);
let chart: Chart | null = null;

const renderChart = () => {
  if (!canvas.value) return;

  const ctx = canvas.value.getContext('2d');
  if (!ctx) return;

  if (chart) chart.destroy();
  if (!props.orders.length) return;
  const grouped = props.orders.reduce((acc, order) => {
    const date = new Date(order.date).toLocaleDateString();
    acc[date] = (acc[date] || 0) + order.amount;
    return acc;
  }, {} as Record<string, number>);

  chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: Object.keys(grouped),
      datasets: [{
        label: 'Vendas por Dia',
        data: Object.values(grouped),
        borderColor: '#4ade80',
        backgroundColor: 'rgba(74, 222, 128, 0.1)',
        fill: true,
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { labels: { color: '#fff' } } },
      scales: {
        x: { ticks: { color: '#fff' } },
        y: { ticks: { color: '#fff' } },
      }
    }
  });
};

watch(() => props.orders, renderChart);
onMounted(renderChart);
</script>

<template>
  <div class="bg-gray-800 p-4 rounded-xl shadow">
    <canvas ref="canvas"></canvas>
  </div>
</template>