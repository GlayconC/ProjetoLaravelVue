<template>
  <div class="min-h-screen bg-gray-400 p-8"> 
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6"> 
          <h1 class="text-black text-2xl text-center font-bold mb-10">
              Cadastro de Usuários!
          </h1>
          <form> 
              <div class="flex pr-20 gap-4 items-start mb-4">
                  <label class="w-36 text-gray-700 font-bold text-xl shrink-0">Nome</label>
                  <div class="flex flex-col w-full">
                      <input v-model="name" type="text" placeholder="seu nome" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                      <p class="text-red-500 text-sm mt-1 min-h-[20px]">{{ erros.name }}</p>
                  </div>
              </div>
              <div class="flex pr-20 gap-4 items-start mb-4">
                  <label class="w-36 text-gray-700 font-bold text-xl shrink-0">Email</label>
                  <div class="flex flex-col w-full">
                      <input v-model="email" type="email" placeholder="seu@email.com" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                      <p class="text-red-500 text-sm mt-1 min-h-[20px]">{{ erros.email }}</p>
                  </div>
              </div>
              <div class="flex pr-20 gap-4 items-start mb-4">
                  <label class="w-36 text-gray-700 font-bold text-xl shrink-0">Senha</label>
                  <div class="flex flex-col w-full">
                      <input v-model="password" type="password" placeholder="sua senha" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                      <p class="text-red-500 text-sm mt-1 min-h-[20px]">{{ erros.password }}</p>
                  </div>
              </div>
              <div class="flex pr-20 gap-4 items-start mb-4">
                  <label class="w-36 text-gray-700 font-bold text-xl shrink-0">Confirmar</label>
                  <div class="flex flex-col w-full">
                      <input v-model="password_confirmation" type="password" placeholder="confirme sua senha" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                      <p class="text-red-500 text-sm mt-1 min-h-[20px]">{{ erros.password_confirmation }}</p>
                  </div>
              </div>
              <div class="flex justify-end pr-20 pt-10">
                  <button @click.prevent="validaCampos" class="bg-green-700 hover:bg-green-900 text-white font-bold py-2 px-4 rounded text-xl">
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
            erros: {
              name: '',
              email: '',
              password: '',
              password_confirmation: ''
            }
            }
        },
        methods: {
            async validaCampos() {
              this.erros.name = this.name ? '' : 'O campo nome é obrigatório.'
              this.erros.email = this.email ? '' : 'O campo email é obrigatório.'
              this.erros.password = this.password ? '' : 'O campo senha é obrigatório.'
              this.erros.password_confirmation = this.password_confirmation ? '' : 'O campo confirmação de senha é obrigatório.'
              if (this.password && this.password_confirmation && this.password !== this.password_confirmation) {
                this.erros.password_confirmation = 'As senhas não coincidem.'
              }
            },
            async register() {
            try {
                const response = await api.post('/register', {
                name: this.name,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation
                })

                localStorage.setItem('token', response.data.token)
                this.$router.push('/dashboard')
            } catch (err) {
                console.log(response)
                this.error = 'Erro ao registrar, verifique os dados'
            }
            }
        }
    }
</script>