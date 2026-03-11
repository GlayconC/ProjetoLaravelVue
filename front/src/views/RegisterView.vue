<template>
  <div>
    <h1>Registro</h1>
    <form>
      <div>
        <label>Nome</label>
        <input v-model="name" type="text" placeholder="seu nome" />
      </div>
      <div>
        <label>Email</label>
        <input v-model="email" type="email" placeholder="seu@email.com" />
      </div>
      <div>
        <label>Senha</label>
        <input v-model="password" type="password" placeholder="sua senha" />
      </div>
      <div>
        <label>Confirmar Senha</label>
        <input v-model="password_confirmation" type="password" placeholder="confirme sua senha" />
      </div>
      <button @click.prevent="register">Registrar</button>
    </form>
    <p v-if="error">{{ error }}</p>
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
            error: ''
            }
        },
        methods: {
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
                this.error = 'Erro ao registrar, verifique os dados'
            }
            }
        }
    }
</script>