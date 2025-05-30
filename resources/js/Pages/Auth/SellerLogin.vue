<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white shadow-md rounded-2xl p-8 w-full max-w-md">
      <h1 class="text-2xl font-bold text-center mb-6">Bem-vindo novamente, vendedor!</h1>
      <form @submit.prevent="handleLogin">
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1" for="email">Email</label>
          <input
            type="email"
            id="email"
            v-model="form.email"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-400"
            required
          />
        </div>

        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-1" for="password">Senha</label>
          <input
            type="password"
            id="password"
            v-model="form.password"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-400"
            required
          />
        </div>

        <button
          type="submit"
          class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors"
        >
          Entrar
        </button>
      </form>
    </div>
  </div>
</template>

<script lang="ts" setup>
import ToastService from '@/utils/toast';
import { reactive } from 'vue';
import axios from 'axios';

interface LoginForm {
  email: string;
  password: string;
}

const form = reactive<LoginForm>({
  email: '',
  password: ''
});

const handleLogin = async () => {
    if(!form.email || !form.password) {
        ToastService.warning('Por favor, preencha todos os camps');
        return;
    }

    try {
        const { data:content } = await axios.post('/login', form);
        const { success } = content;
        if (success) {
            window.location.href = '/dashboard';
        }
    }
    catch(e) {
        console.log(e);
        ToastService.error('Email ou senha incorretos');
    }
}
</script>

<style scoped>
body {
  font-family: 'Inter', sans-serif;
}
</style>
