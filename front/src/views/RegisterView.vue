<template>
  <div class="min-h-screen bg-gray-400 p-4 md:p-8"> 
        <div v-if="carregando" class="fixed inset-0 backdrop-blur-sm flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 flex flex-col items-center gap-4">
                <img src="https://i.gifer.com/ZZ5H.gif" alt="Carregando..." class="w-16 h-16">
                <p class="text-gray-700 font-bold">Carregando...</p>
            </div>
        </div>
      <div class="w-full max-w-2xl mx-auto bg-white rounded-lg shadow p-4 md:p-6"> 
          <h1 class="text-black text-xl md:text-2xl text-center font-bold mb-6 md:mb-10">
              Cadastro de Usuários!
          </h1>
          <form> 
              <div class="flex flex-col md:flex-row md:pr-20 gap-1 md:gap-4 md:items-start mb-4">
                  <label class="w-full md:w-36 text-gray-700 font-bold text-xl shrink-0">Nome</label>
                  <div class="flex flex-col w-full">
                      <input v-model="name" type="text" placeholder="seu nome" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                      <p class="text-red-500 text-sm mt-1 min-h-[20px]">{{ erros.name }}</p>
                  </div>
              </div>
              <div class="flex flex-col md:flex-row md:pr-20 gap-1 md:gap-4 md:items-start mb-4">
                  <label class="w-full md:w-36 text-gray-700 font-bold text-xl shrink-0">Email</label>
                  <div class="flex flex-col w-full">
                      <input v-model="email" @blur="validarEmail(email)" type="email" placeholder="seu@email.com" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                      <p class="text-red-500 text-sm mt-1 min-h-[20px]">{{ erros.email }}</p>
                  </div>
              </div>
              <div class="flex flex-col md:flex-row md:pr-20 gap-1 md:gap-4 md:items-start mb-4">
                  <label class="w-full md:w-36 text-gray-700 font-bold text-xl shrink-0">Senha</label>
                  <div class="flex flex-col w-full">
                      <input v-model="password" type="password" placeholder="sua senha" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                      <p class="text-red-500 text-sm mt-1 min-h-[20px]">{{ erros.password }}</p>
                  </div>
              </div>
              <div class="flex flex-col md:flex-row md:pr-20 gap-1 md:gap-4 md:items-start mb-4">
                  <label class="w-full md:w-36 text-gray-700 font-bold text-xl shrink-0">Confirmar</label>
                  <div class="flex flex-col w-full">
                      <input v-model="password_confirmation" type="password" placeholder="confirme sua senha" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                      <p class="text-red-500 text-sm mt-1 min-h-[20px]">{{ erros.password_confirmation }}</p>
                  </div>
              </div>
              <div class="flex justify-end md:pr-20 pt-6 md:pt-10">
                  <button @click.prevent="validaCampos" class="w-full md:w-auto bg-green-700 hover:bg-green-900 text-white font-bold py-2 px-4 rounded text-xl">
                      Registrar
                  </button>
              </div>
          </form>
          <p v-if="error">{{ error }}</p>
      </div>
  </div>
</template>

<script>

import api from '../services/api'

    export default {
        data() {
            return {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            error: '',
            carregando: false,
            erros: {
              name: '',
              email: '',
              password: '',
              password_confirmation: ''
            }
            }
        },

        methods: {
            // Verifica se os Campos estão preenchidos e se as senhas coincidem
            validaCampos() {
                this.erros.name = this.name ? '' : 'O campo nome é obrigatório.'
                this.erros.email = this.email ? '' : 'O campo email é obrigatório.'
                this.erros.password = this.password ? '' : 'O campo senha é obrigatório.'
                this.erros.password_confirmation = this.password_confirmation ? '' : 'O campo confirmação de senha é obrigatório.'
                if (this.password && this.password_confirmation && this.password !== this.password_confirmation) {
                    this.erros.password_confirmation = 'As senhas não coincidem.'
                }
                if (!this.erros.name && !this.erros.email && !this.erros.password && !this.erros.password_confirmation) {
                    this.register()
                }
            },
            validarEmail(email) {
                const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
                if (!email) {
                    this.erros.email = 'Email é obrigatório.'
                } else if (!regex.test(email)) {
                    this.erros.email = 'Email inválido.'
                } else {
                    this.erros.email = null
                }
            },
            async register() {
                try {
                    this.carregando = true
                    const response = await api.post('/register', {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation
                    })

                    localStorage.setItem('token', response.data.token)
                    this.$router.push('/dashboard')
                } catch (error) {
                    const errosApi = error.response.data.errors
                    if (errosApi.email) this.erros.email = 'Email já cadastrado!'
                    if (errosApi.password) this.erros.password = 'A senha deve conter no mínimo 6 caracteres.'
                    console.log(errosApi)
                } finally {
                    this.carregando = false
                }
            }
        }
    }
</script>