<template>
    <div class="min-h-screen bg-gray-400 p-8">
      <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
              <h1 v-if="user" class="flex-1 text-gray-600 text-2xl text-center mb-4 pl-20">
                  Bem vindo, <span class="font-semibold">{{ user.name }}</span>!
              </h1>
              <button 
                  @click="logout"
                  class="bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-5 rounded"
              >
                  Logout
              </button>
          </div>
      </div>
      <div align="center" class="max-w-4xl mx-auto mt-6 bg-white rounded-lg shadow p-6">
          <h2 class="text-gray-700 text-xl mb-4 mt-8">LISTA DE PESSOAS</h2>
          <p class="text-gray-600"> Pessoas cadastradas: {{ listaPessoas ? listaPessoas.length : 0 }}</p>
          <div class="mt-5 bg-gray-100 p-3 rounded">
            <ul class="mt-3 text-gray-700">
                <li v-for="pessoa in listaPessoas" :key="pessoa.id" class="border-b py-2 flex justify-between items-center">
                    <span>{{ pessoa.nome }} - {{ pessoa.email }}</span>
                    <div class="flex gap-2">
                        <button class="bg-yellow-100 hover:bg-yellow-400 text-black font-semibold py-1 px-3 rounded">
                            Visualizar Dados
                        </button>
                        <button class="bg-blue-200 hover:bg-blue-600 text-black font-semibold py-1 px-3 rounded">
                            Editar
                        </button>
                        <button class="bg-red-200 hover:bg-red-800 text-black font-semibold py-1 px-3 rounded" @click="mostrarModal = true; pessoaSelecionada = pessoa.nome">  
                            Excluir
                        </button>
                    </div>
                    <div v-if="mostrarModal" class="fixed inset-0 backdrop-blur-sm flex items-center justify-center">
                        <div class="bg-white rounded-lg p-6 w-96">
                            <h2 class="text-xl font-bold mb-4">Deseja Excluir a pessoa {{ pessoaSelecionada }} do cadastro?</h2>
                            <div class="flex justify-center gap-4">
                              <button @click="excluir(pessoa.id); mostrarModal = false" class="mt-4 bg-green-500 text-white py-1 px-4 rounded">
                                  Sim
                              </button>
                              <button @click="mostrarModal = false" class="mt-4 bg-red-500 text-white py-1 px-4 rounded">
                                  Não
                              </button>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
          </div>
      </div>
    </div>
</template>

<script>
  import api from '../services/api'

  export default {
    data() {
      return {
        user: null,
        listaPessoas:null,
        mostrarModal: false,
        pessoaSelecionada: null,
      }
    },
    async mounted(){
      const token = localStorage.getItem('token')

      // Buscar dados do usuário logado
      const response = await api.get('/user', {
        headers: {
          Authorization: `Bearer ${token}`
        }
      })
      this.user = response.data
      
      // Buscar lista de pessoas
      const response2 = await api.get('/pessoas', {
        headers: {
          Authorization: `Bearer ${token}`
        }
      })
      this.listaPessoas = response2.data
      console.log(this.listaPessoas)
    },
    methods: {
      // Logout do usuário
      async logout() {
        const token = localStorage.getItem('token')

        await api.post('/logout', {}, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        })

        localStorage.removeItem('token')
        this.$router.push('/login')
      },

      // Excluir Pessoa da Lista
      async excluir(id) {
        const token = localStorage.getItem('token')

        await api.delete(`/pessoas/${id}`, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        })

        // Remover a pessoa da lista
        this.listaPessoas = this.listaPessoas.filter(p => p.id !== id)
      }
    }
  }
</script>