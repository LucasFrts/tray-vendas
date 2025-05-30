<template>
  <Head title="Register your sales" />
  <div class="w-100 py-3 flex justify-end pr-3">
    <a href="/admin/login" class="text-sm text-indigo-800 hover:text-indigo-500">Administração</a>
  </div>
  <div class="min-h-screen bg-gray-50 flex flex-col items-center py-10 px-4">
    <section class="text-center max-w-xl mb-12">
      <h1 class="text-3xl md:text-5xl font-bold text-gray-900">
        Registre-se e gerencie suas vendas
      </h1>
      <p class="mt-4 text-gray-600 text-lg">
        Entre como vendedor e tenha controle de suas vendas
      </p>
    </section>

    <div class="w-full max-w-6xl grid gap-8 md:grid-cols-2">
      <RegisterForm @register="register" />

      
      <div class="bg-white rounded-2xl shadow p-6 md:p-8">
        <div class="flex items-center justify-between mb-6">
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-100 rounded-full"></div>
            <div>
              <p class="text-gray-800 font-semibold">Fulano</p>
              <p class="text-gray-600 text-sm">R$250.00</p>
            </div>
          </div>
        </div>

        <div class="mb-6">
          <h3 class="text-lg font-semibold text-gray-700 mb-2">Vendas</h3>
          <div class="bg-blue-100 h-24 w-full rounded"></div>
          <div class="mt-4 space-y-2 text-sm">
            <div class="flex justify-between"><span>Data</span><span>Valor</span></div>
            <div class="flex justify-between"><span>Hoje</span><span>R$100.00</span></div>
            <div class="flex justify-between"><span>Ontem</span><span>R$50.00</span></div>
          </div>
        </div>

        <div class="bg-blue-200 w-full h-24 rounded"></div>
      </div>
    </div>

    <section class="mt-16 flex flex-col items-center text-center">
      <div class="flex items-center space-x-4">
        <div class="w-12 h-12 bg-blue-100 rounded flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a2 2 0 00-2-2H5a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2zm10 0v-2a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2zM9 7V5a2 2 0 00-2-2H5a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2zm10 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2z" />
          </svg>
        </div>
        <div>
          <p class="text-xl font-semibold">Geração de relatórios</p>
          <p class="text-md text-gray-600">Gere relatórios detalhados de suas vendas</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script lang="ts" setup>
import RegisterForm from '@/Components/Forms/RegisterForm.vue'
import LoadingService from '@/utils/loading'
import axios from 'axios'
import { Head } from '@inertiajs/vue3';


type RegisterFormType = {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
}

const register = async (form: RegisterFormType) => {
    LoadingService.wait();
    try{
        const response = await axios.post('/sellers', form);
        window.location.reload();
    }
    catch(e){
        console.log(e)
    }
    finally{
        LoadingService.stop();
    }
    
}
</script>

<style scoped>
.input {
  @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500;
}

.btn-primary {
  @apply bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700;
}
</style>