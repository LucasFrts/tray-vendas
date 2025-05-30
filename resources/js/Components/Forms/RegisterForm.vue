<script setup lang="ts">
import { reactive } from 'vue';
import ToastService from '@/utils/toast';

type RegisterForm = {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
}

const form = reactive<RegisterForm>({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const emit = defineEmits(['register']);

function register() {

    if (!form.name || !form.email || !form.password || !form.password_confirmation) {
        ToastService.warning('Please fill in all the fields');
        return;
    }

    if (form.password !== form.password_confirmation) {
        ToastService.warning('Passwords do not match');
        return;
      }


    emit('register', form);
}

</script>

<template>
     <form class="bg-white rounded-2xl shadow p-6 md:p-8 space-y-5">
        <h2 class="text-xl font-semibold text-gray-800">Cadastro</h2>
        <input v-model="form.name" type="text" placeholder="Nome" class="input" />
        <input v-model="form.email" type="email" placeholder="Email" class="input" />
        <input v-model="form.password" type="password" placeholder="Senha" class="input" />
        <input v-model="form.password_confirmation" type="password" placeholder="Confirme a senha" class="input" />
        <button type="button" @click="register" class="btn-primary w-full">
          Cadastrar
        </button>
        <p class="text-md font-semibold pb-3">Já possui uma conta?</p>
        <a href="/login" class="btn-primary w-full">Entrar</a>
      </form>
</template>

<style scoped>
.input {
  @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500;
}

.btn-primary {
  @apply bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700;
}
</style>