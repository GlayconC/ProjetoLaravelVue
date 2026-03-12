<template>
    <div class="min-h-screen bg-gray-400 p-10">
      <div class="max-w-4xl mx-auto mt-6 bg-white rounded-lg shadow p-6">
        <div class="flex items-center mt-8 mb-4">
            <div class="flex-1"></div>
            <h2 class="text-gray-700 text-xl font-bold">CADASTRO DE PESSOAS</h2>
            <div class="flex-1 flex justify-end">
                <button @click="$router.push('/dashboard')" class="hover:bg-blue-500 bg-blue-200 text-white py-1 px-4 rounded">
                    Voltar
                </button>
            </div>
        </div>
            <form @submit.prevent="cadastrarPessoa">
              <div class="mb-4"> 
                <div class="flex pt-10 gap-4 items-center">
                  <label class="w-32 text-gray-700 text-xl font-bold" for="name">Nome:</label>
                  <input v-model="dados.nome" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" placeholder="Digite o nome">
                </div> 
                <div class="flex pt-10 gap-4 items-start">
                    <label class="w-32 text-gray-700 text-xl font-bold pt-2" for="email">Email:</label>
                    <div class="flex flex-col w-full">
                        <input v-model="dados.email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" placeholder="Digite o email">
                        <p class="text-red-500 text-sm pt-1 min-h-[20px]">{{ erros.email }}</p>
                    </div>
                </div>
                <div class="flex pt-5 gap-4 items-start">
                    <label class="w-32 text-gray-700 text-xl font-bold pt-2" for="documento">CPF/CNPJ:</label>
                    <div class="flex flex-col w-full">
                        <input 
                            :value="dados.documento"
                            @input="formatarDocumento"
                            maxlength="18"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                            id="documento" 
                            type="text" 
                            placeholder="CPF ou CNPJ"
                            required
                        >
                        <p class="text-red-500 text-sm pt-1 min-h-[20px]">{{ erros.documento }}</p>
                    </div>
                  <label class="w-10 text-gray-700 text-xl font-bold" for="tipo">Tipo:</label>
                  <input v-model="dados.tipo" readonly class="shadow appearance-none border rounded w-35 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-300" id="tipo" type="text" placeholder="PF/PJ">
                </div>
                <div class="flex pt-5 gap-4 items-start">
                  <label class="w-32 text-gray-700 text-xl font-bold pt-2" for="telefone">Telefone:</label>
                  <div class="flex flex-col w-full">
                    <input v-model="dados.telefone" maxlength="15" @input="formatarTelefone" required class="shadow appearance-none border rounded w-38 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="telefone" type="text" placeholder="Digite o telefone">
                    <p class="text-red-500 text-sm pt-1 min-h-[20px]">{{ erros.telefone }}</p>
                  </div>
                </div>
              </div>                      
              <div class="flex justify-end pt-4">
                <button type="submit" class="bg-green-200 hover:bg-green-800 text-white font-bold p py-3 px-5 rounded">
                  Cadastrar
                </button>
              </div>
            </form>
      </div>
      <div v-if="modalSucesso" class="fixed inset-0 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-96">
          <div class="text-left">
            <h2 class="text-xl mb-4 text-center">
              Pessoa cadastrada com sucesso!
            </h2>
          </div>
          <div class="flex justify-center gap-4">
            <button @click="modalSucesso = false" class="mt-4 bg-green-200 hover:bg-green-800 text-white py-1 px-4 rounded ">
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
        modalSucesso: false
      }
    },
    methods: {
      async cadastrarPessoa() {
        const token = localStorage.getItem('token')

        this.validaDocumento(this.dados.documento)
        this.validaTelefone(this.dados.telefone)
        console.log(this.dados)

        if (!this.erros.documento && !this.erros.telefone) {
          try {
            await api.post('/pessoas', this.dados,{
              headers: {
                Authorization: `Bearer ${token}`
              }
            })
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
                  this.dados.tipo = 'PF'
                  this.erros.documento = null
              }
          } else if (cnpjRegex.test(documento)) {
              if (!this.validarCnpj(documento)) {
                  this.erros.documento = 'CNPJ inválido.'
              }  else {
                  this.dados.tipo = 'PJ'
                  this.erros.documento = null
              }
          } else {
              this.erros.documento = 'Documento inválido.'
          }
      } 
    }
  }
</script>