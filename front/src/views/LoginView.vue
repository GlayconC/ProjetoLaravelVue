<template>
    <div class="min-h-screen bg-gray-400 p-4 md:p-8"> 
        <div class="w-full max-w-2xl mx-auto bg-white rounded-lg shadow p-4 md:p-6"> 
            <div v-if="carregando" class="fixed inset-0 backdrop-blur-sm flex items-center justify-center">
                <div class="bg-white rounded-lg p-6 flex flex-col items-center gap-4">
                    <img src="https://i.gifer.com/ZZ5H.gif" alt="Carregando..." class="w-16 h-16">
                    <p class="text-gray-700 font-bold">Carregando...</p>
                </div>
            </div>
            <div>
                <h1 class="text-black text-xl md:text-2xl text-center font-bold mb-4">
                    Bem Vindo ao Sistema de Cadastro de Pessoas!
                </h1>
            </div>
            <div class="mt-6 pt-6 md:pt-20 px-4 md:pl-10">
                <form> 
                    <div class="flex flex-col md:flex-row md:pr-20 gap-2 md:gap-4 md:items-center mb-4">
                        <label class="w-full md:w-20 text-gray-700 font-bold text-xl">Email:</label>
                        <input v-model="email" type="email" placeholder="Digite seu e-mail" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                    </div>
                    <div class="flex flex-col md:flex-row md:pr-20 gap-2 md:gap-4 md:items-center mb-4">
                        <label class="w-full md:w-20 text-gray-700 font-bold text-xl">Senha:</label>
                        <input v-model="password" type="password" placeholder="Digite sua senha" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>                       
                    </div>  
                    <div class="flex justify-start md:pl-20">
                        <p v-if="error" style="color: red;">{{ error }}</p>                 
                    </div>
                    <div class="flex pt-8 md:pt-20 md:pr-10 justify-between">
                        <button @click="$router.push('/register')" class="bg-green-700 hover:bg-green-900 text-white font-bold py-2 px-4 rounded text-lg md:text-xl">
                            Cadastrar
                        </button>
                        <button @click.prevent="login" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-lg md:text-xl">
                            Entrar
                        </button>
                    </div>
                </form>
            </div>
        </div> 
    </div>
</template>

<script>

import api from '../services/api'

    export default {
        data() {
            return {
            email: '',
            password: '',
            error: '',
            carregando: false
            }
        },
        methods: {
            async login() {
                try {
                    this.carregando = true
                    const response = await api.post('/login', {
                    email: this.email,
                    password: this.password
                    })
                    localStorage.setItem('token', response.data.token)
                    this.$router.push('/dashboard')
                } catch (err) {
                    this.error = 'Email ou senha inválidos'
                } finally {
                    this.carregando = false
                }
            }
        }
    }
</script>