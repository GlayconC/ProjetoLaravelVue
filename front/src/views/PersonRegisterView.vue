<template>
    <div class="min-h-screen bg-gray-400 p-4 md:p-10">
      <div class="w-full max-w-4xl mx-auto mt-4 md:mt-6 bg-white rounded-lg shadow p-4 md:p-6">
        <div class="flex items-center mt-4 md:mt-8 mb-4">
            <div class="flex-1"></div>
            <h2 class="text-gray-700 text-lg md:text-xl font-bold text-center">
                {{ $route.params.id ? 'EDIÇÃO DE PESSOAS' : 'CADASTRO DE PESSOAS' }}
            </h2>
            <div class="flex-1 flex justify-end">
                <button @click="$router.push('/dashboard')" class="hover:bg-blue-600 bg-blue-400 text-white py-1 px-3 md:px-4 rounded text-sm md:text-base">
                    Voltar
                </button>
            </div>
        </div>
        <div v-if="carregando" class="fixed inset-0 backdrop-blur-sm flex items-center justify-center">
          <div class="bg-white rounded-lg p-6 flex flex-col items-center gap-4">
              <img src="https://i.gifer.com/ZZ5H.gif" alt="Carregando..." class="w-16 h-16">
              <p class="text-gray-700 font-bold">Carregando...</p>
          </div>
        </div>
        <form v-if="!carregando" @submit.prevent="cadastrarPessoa">
          <div class="mb-4"> 
            <div class="flex flex-col md:flex-row pt-6 md:pt-10 gap-1 md:gap-4 md:items-center">
              <label class="w-full md:w-32 text-gray-700 text-xl font-bold" for="name">Nome:</label>
                <div class="flex flex-col w-full">
                  <input v-model="dados.nome" @input="erros.nome = null" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-0" id="name" type="text" placeholder="Digite o nome">
                  <p class="text-red-500 text-sm pt-1 min-h-[20px]">{{ erros.nome }}</p>
                </div>
              </div> 
            <div class="flex flex-col md:flex-row pt-3 md:pt-5 gap-1 md:gap-4 md:items-start">
                <label class="w-full md:w-32 text-gray-700 text-xl font-bold md:pt-2" for="email">Email:</label>
                <div class="flex flex-col w-full">
                    <input 
                      v-model="dados.email" 
                      @input="onEmailInput" 
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                      id="email" 
                      placeholder="Digite o email"
                    >
                    <p class="text-red-500 text-sm pt-1 min-h-[20px]">{{ erros.email }}</p>
                </div>
            </div>
            <div class="flex flex-col md:flex-row pt-3 md:pt-5 gap-1 md:gap-4 md:items-start">
                <label class="w-full md:w-45 text-gray-700 text-xl font-bold md:pt-2" for="documento">CPF/CNPJ:</label>
                <div class="flex flex-col w-full">
                    <input 
                        :value="dados.documento"
                        @input="onDocumentoInput"
                        @keypress="apenasNumeros"
                        maxlength="18"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                        id="documento" 
                        type="text" 
                        placeholder="CPF ou CNPJ"
                    >
                    <p class="text-red-500 text-sm pt-1 min-h-[20px]">{{ erros.documento }}</p>
                </div>
              <div class="flex gap-2 md:gap-4 md:items-start items-center">
                <label class="w-10 text-gray-700 text-xl font-bold" for="tipo">Tipo:</label>
                <input v-model="dados.tipo" readonly class="shadow appearance-none border rounded w-35 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-300" id="tipo" type="text" placeholder="PF/PJ">
              </div>
            </div>
            <div class="flex flex-col md:flex-row pt-3 md:pt-5 gap-1 md:gap-4 md:items-start">
              <label class="w-full md:w-32 text-gray-700 text-xl font-bold md:pt-2" for="telefone">Telefone:</label>
              <div class="flex flex-col w-full">
                <input 
                    :value="dados.telefone" 
                    maxlength="15" 
                    @input="onTelefoneInput"
                    @keypress="apenasNumeros"  
                    class="shadow appearance-none border rounded w-full md:w-38 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                    id="telefone" 
                    type="text" 
                    placeholder="Digite o telefone"
                >
                <p class="text-red-500 text-sm pt-1 min-h-[20px]">{{ erros.telefone }}</p>
              </div>
            </div>
          </div>                      
          <div class="flex justify-end pt-4">
            <button type="submit" class="w-full md:w-auto bg-green-600 hover:bg-green-800 text-white font-bold py-3 px-5 rounded">
              {{ $route.params.id ? 'Salvar' : 'Cadastrar' }}
            </button>
          </div>
        </form>
      </div>
      <div v-if="modalSucesso" class="fixed inset-0 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-sm">
          <div class="text-left">
            <h2 class="text-xl mb-4 text-center">
            {{ $route.params.id ? 'Pessoa atualizada com sucesso!' : 'Pessoa cadastrada com sucesso!' }}
            </h2>
          </div>
          <div class="flex justify-center gap-4">
            <button v-if="!$route.params.id" @click="modalSucesso = false" class="mt-4 bg-green-200 hover:bg-green-800 text-white py-1 px-4 rounded">
              Novo Cadastro
            </button>  
            <button @click="$router.push('/dashboard')" class="mt-4 hover:bg-blue-500 bg-blue-200 text-white py-1 px-4 rounded">
              Ir para Dashboard
            </button>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
  import api from '../services/api'

  export default {
    data() {
      return {
        dados: {
            nome: '',
            email: '',
            documento: '',
            tipo: '',
            telefone: ''
        },
        erros: {
            documento: null,
            telefone: null,
            email: null
        },
        modalSucesso: false,
        carregando: false
      }
    },
    async mounted() {
        //Busca o ID da rota para verificar se é edição ou cadastro
        const id = this.$route.params.id

        if (id) {
            // modo edição - busca os dados da pessoa
            this.carregando = true
            const token = localStorage.getItem('token')
            const response = await api.get(`/pessoas/${id}`, {
                headers: { Authorization: `Bearer ${token}` }
            })
            this.dados = response.data
            this.carregando = false
        }
    },
    methods: {
      async cadastrarPessoa() {
        console.log(this.$route.params.id) 
        const token = localStorage.getItem('token')

        this.erros.email = this.dados.email ? '' : 'O campo email é obrigatório.'
        this.erros.nome = this.dados.nome ? '' : 'O campo nome é obrigatório.'
        this.validaDocumento(this.dados.documento)
        this.validaTelefone(this.dados.telefone)

        if (!this.erros.documento && 
            !this.erros.telefone && 
            !this.erros.email &&
            !this.erros.nome) {
          try {
            if(this.$route.params.id){
              await api.put(`/pessoas/${this.$route.params.id}`, this.dados, {
                headers: {
                  Authorization: `Bearer ${token}`
                }
              })
            } else {
              await api.post('/pessoas', this.dados,{
                headers: {
                  Authorization: `Bearer ${token}`
                }
              })
            }
            this.modalSucesso = true
            this.dados = {
              nome: '',
              email: '',
              documento: '',
              tipo: '',
              telefone: ''
            }
          } catch (error) {
            if (error.response && error.response.status === 422) {
                const errosApi = error.response.data.errors

                if (errosApi.documento) this.erros.documento = 'Documento já cadastrado!'
                if (errosApi.email) this.erros.email = 'Email já cadastrado!'
            }
          }
        } 
      },
       validaTelefone(telefone) {
        const apenasNumeros = telefone.replace(/\D/g, '')
        if (apenasNumeros.length < 10 || apenasNumeros.length > 11) {
            this.erros.telefone = 'Telefone inválido. Deve conter 10 ou 11 dígitos.'
        } else {
            this.erros.telefone = null
          }
      },
      onTelefoneInput(evento) {
          this.formatarTelefone(evento)
          this.erros.telefone = null
      },
      onEmailInput() {
          this.erros.email = null
      },
      formatarDocumento(documento) {
          let valor = documento.target.value.replace(/\D/g, '') // remove tudo que não é número

          if (valor.length <= 11) {
              // formata CPF: 000.000.000-00
              valor = valor.replace(/(\d{3})(\d)/, '$1.$2')
              valor = valor.replace(/(\d{3})(\d)/, '$1.$2')
              valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2')
              this.dados.tipo = 'Pessoa Física'
          } else {
              // formata CNPJ: 00.000.000/0000-00
              valor = valor.replace(/(\d{2})(\d)/, '$1.$2')
              valor = valor.replace(/(\d{3})(\d)/, '$1.$2')
              valor = valor.replace(/(\d{3})(\d)/, '$1/$2')
              valor = valor.replace(/(\d{4})(\d{1,2})$/, '$1-$2')
              this.dados.tipo = 'Pessoa Jurídica'
          }

          this.dados.documento = valor
      },
      onDocumentoInput(evento) {
          this.formatarDocumento(evento)
          this.erros.documento = null
      },
      formatarTelefone(telefone) {
          let valor = telefone.target.value.replace(/\D/g, '') // remove tudo que não é número

          if (valor.length <= 10) {
              // formata telefone: (00) 0000-0000
              valor = valor.replace(/(\d{2})(\d)/, '($1) $2')
              valor = valor.replace(/(\d{4})(\d)/, '$1-$2')
          } else {
              // formata celular: (00) 00000-0000
              valor = valor.replace(/(\d{2})(\d)/, '($1) $2')
              valor = valor.replace(/(\d{5})(\d)/, '$1-$2')
          }

          this.dados.telefone = valor
      },
      validarCpf(cpf) {
          //Valida CPF: 000.000.000-00
          cpf = cpf.replace(/\D/g, '')
          if (cpf.length !== 11 || /(\d)\1{10}/.test(cpf)) return false

          for (let t = 9; t < 11; t++) {
              let sum = 0
              for (let i = 0; i < t; i++) sum += parseInt(cpf[i]) * (t + 1 - i)
              let remainder = (10 * sum) % 11 % 10
              if (parseInt(cpf[t]) !== remainder) return false
          }
          return true
      },
      validarCnpj(cnpj) {
          //Valida CNPJ: 00.000.000/0000-00
          cnpj = cnpj.replace(/\D/g, '')
          if (cnpj.length !== 14 || /(\d)\1{13}/.test(cnpj)) return false

          const calc = (len) => {
              let sum = 0
              let pos = len - 7
              for (let i = len; i >= 1; i--) {
                  sum += parseInt(cnpj[len - i]) * pos--
                  if (pos < 2) pos = 9
              }
              return sum % 11 < 2 ? 0 : 11 - (sum % 11)
          }

          return calc(12) === parseInt(cnpj[12]) && calc(13) === parseInt(cnpj[13])
      },
      validaDocumento(documento) {
          const cpfRegex = /^\d{3}\.\d{3}\.\d{3}-\d{2}$/
          const cnpjRegex = /^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/
          const token = localStorage.getItem('token')


          if (cpfRegex.test(documento)) {
              if (!this.validarCpf(documento)) {
                  this.erros.documento = 'CPF inválido.'
              } else {
                  this.erros.documento = null
              }
          } else if (cnpjRegex.test(documento)) {
              if (!this.validarCnpj(documento)) {
                  this.erros.documento = 'CNPJ inválido.'
              }  else {
                  this.erros.documento = null
              }
          } else {
              this.erros.documento = 'Documento inválido.'
          }
      },
      apenasNumeros(evento) {
          if (!/\d/.test(evento.key)) evento.preventDefault()
      } 
    }
  }
</script>