<template>
    <div class="min-h-screen bg-gray-400 p-4 md:p-8">
        <div v-if="carregando" class="fixed inset-0 backdrop-blur-sm flex items-center justify-center">
          <div class="bg-white rounded-lg p-6 flex flex-col items-center gap-4">
              <img src="https://i.gifer.com/ZZ5H.gif" alt="Carregando..." class="w-16 h-16">
              <p class="text-gray-700 font-bold">Carregando...</p>
          </div>
        </div>
      <div v-if="!carregando" class="max-w-4xl mx-auto bg-white rounded-lg shadow p-4 md:p-6">
          <div class="flex items-center justify-between">
              <h1 v-if="user" class="flex-1 text-gray-600 text-lg md:text-2xl text-center mb-4 pl-0 md:pl-20">
                  Bem vindo, <span class="font-bold">{{ user.name }}</span>!
              </h1>
              <button 
                  @click="logout"
                  class="bg-red-200 hover:bg-red-600 text-white font-semibold py-2 md:py-3 px-3 md:px-5 rounded text-sm md:text-base"
              >
                  Logout
              </button>
          </div>
      </div>
      <div class="max-w-4xl mx-auto mt-4 md:mt-6 bg-white rounded-lg shadow p-4 md:p-6">
          <h2 class="text-gray-700 text-lg md:text-xl mb-4 mt-4 md:mt-8 font-bold text-center">LISTA DE PESSOAS</h2>
          <p class="text-gray-600 text-center">Pessoas cadastradas: {{ listaPessoas ? listaPessoas.length : 0 }}</p>
          <div class="mt-5 bg-gray-100 rounded">
            <div class="flex  mt-4 px-4 pt-5">
                <input 
                    v-model="filtro"
                    @input="paginaAtual = 1"
                    type="text" 
                    placeholder="Buscar por nome..."
                    class="shadow w-full md:w-96 py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                >
            </div>
            <ul class="mt-3 text-gray-700">
                <li v-for="pessoa in pessoasPaginadas" :key="pessoa.id" class="hover:bg-gray-200 py-3 px-4 flex flex-col md:flex-row justify-between md:items-center gap-2">
                    <span class="text-sm md:text-base">{{ pessoa.nome }} - {{ pessoa.email }}</span>
                    <div class="flex gap-2 flex-wrap">
                        <button class="bg-yellow-300 hover:bg-yellow-400 text-black font-semibold py-1 px-2 md:px-3 rounded text-sm" @click="mostrarModalDados = true; pessoaSelecionada = pessoa">
                            Visualizar
                        </button>
                        <button @click="$router.push(`/people/${pessoa.id}/edit`)" class="bg-blue-400 hover:bg-blue-600 text-black font-semibold py-1 px-2 md:px-3 rounded text-sm">
                            Editar
                        </button>
                        <button class="bg-orange-500 hover:bg-red-500 text-black font-semibold py-1 px-2 md:px-3 rounded text-sm" @click="mostrarModal = true; pessoaSelecionada = pessoa">  
                            Excluir
                        </button>
                    </div>
                </li>
                <div v-if="listaPessoas && listaPessoas.length > 0" class="flex justify-center gap-4 mt-4 pb-4">
                    <button 
                        @click="paginaAtual--" 
                        :disabled="paginaAtual === 1"
                        class="bg-gray-200 hover:bg-gray-400 py-1 px-4 rounded disabled:opacity-50">
                        Anterior
                    </button>
                    <span class="py-1">{{ paginaAtual }} / {{ totalPaginas }}</span>
                    <button 
                        @click="paginaAtual++" 
                        :disabled="paginaAtual === totalPaginas"
                        class="bg-gray-200 hover:bg-gray-400 py-1 px-4 rounded disabled:opacity-50">
                        Próxima
                    </button>
                </div>
            </ul>
            <div v-if="mostrarModal" class="fixed inset-0 backdrop-blur-sm flex items-center justify-center p-4">
                <div class="bg-white rounded-lg p-6 w-full max-w-sm">
                    <h2 class="text-lg md:text-xl font-bold mb-4 text-center">Deseja Excluir a pessoa {{ pessoaSelecionada.nome }} do cadastro?</h2>
                    <div class="flex justify-center gap-4">
                        <button @click="excluir(pessoaSelecionada.id); mostrarModal = false" class="mt-4 bg-green-500 text-white py-1 px-4 rounded">
                            Sim
                        </button>
                        <button @click="mostrarModal = false" class="mt-4 bg-red-500 text-white py-1 px-4 rounded">
                            Não
                        </button>
                    </div>
                </div>
            </div>
            <div v-if="mostrarModalDados" class="fixed inset-0 backdrop-blur-sm flex items-center justify-center p-4">
                <div class="bg-white rounded-lg p-6 md:p-8 w-full max-w-sm md:max-w-md text-left">
                    <div class="text-base md:text-xl mb-4 space-y-2">
                        <div class="flex gap-2">
                            <span class="font-bold w-28 md:w-32 shrink-0">Nome:</span>
                            <span>{{ pessoaSelecionada.nome }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="font-bold w-28 md:w-32 shrink-0">Email:</span>
                            <span class="break-all">{{ pessoaSelecionada.email }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="font-bold w-28 md:w-32 shrink-0">Documento:</span>
                            <span>{{ pessoaSelecionada.documento }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="font-bold w-28 md:w-32 shrink-0">Tipo:</span>
                            <span>{{ pessoaSelecionada.tipo }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="font-bold w-28 md:w-32 shrink-0">Telefone:</span>
                            <span>{{ pessoaSelecionada.telefone }}</span>
                        </div>
                    </div>
                    <div class="flex justify-center gap-4">
                        <button @click="mostrarModalDados = false" class="mt-4 bg-red-500 text-white py-1 px-4 rounded">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
          </div>                    
          <div class="flex justify-end pt-4">
              <button @click="$router.push('/people/register')" class="w-full md:w-auto bg-green-600 hover:bg-green-800 text-white font-bold py-3 px-5 rounded">
                Adicionar Pessoa
              </button>
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
        listaPessoas:[],
        mostrarModal: false,
        mostrarModalDados: false,
        pessoaSelecionada: null,
        paginaAtual: 1,
        itensPorPagina: 5,
        carregando: false,
        filtro:''
      }
    },
    async mounted(){

      this.carregando = true

      // Buscar dados do usuário logado
      const response = await api.get('/user')
      this.user = response.data
      
      // Buscar lista de pessoas
      const response2 = await api.get('/pessoas')
      this.listaPessoas = response2.data
      this.carregando = false
    },
    methods: {
      // Logout do usuário
      async logout() {
        const token = localStorage.getItem('token')

        await api.post('/logout', {})

        localStorage.removeItem('token')
        this.$router.push('/login')
      },

      // Excluir Pessoa da Lista
      async excluir(id) {
        const token = localStorage.getItem('token')
        console.log(id)

        await api.delete(`/pessoas/${id}`)

        // Remover a pessoa da lista
        this.listaPessoas = this.listaPessoas.filter(p => p.id !== id)
      }
    },
    computed: {
        pessoasFiltradas() {
            if (!this.filtro) return this.listaPessoas
            return this.listaPessoas.filter(p =>
                p.nome.toLowerCase().includes(this.filtro.toLowerCase())
            )
        },
        pessoasPaginadas() {
            const inicio = (this.paginaAtual - 1) * this.itensPorPagina
            const fim = inicio + this.itensPorPagina
            return this.pessoasFiltradas.slice(inicio, fim)  // ← trocar aqui
        },
        totalPaginas() {
            return Math.ceil(this.pessoasFiltradas.length / this.itensPorPagina)  // ← e aqui
        }
    }
  }
</script>