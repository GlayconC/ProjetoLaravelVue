<template>
    <div>   
        <h1>Login</h1>
        <form> 
            <div>
                <label>Email:</label>
                <input v-model="email" type="email" placeholder="Digite seu e-mail"/>
            </div>
            <div>
                <label>Senha:</label>
                <input v-model="password" type="password" placeholder="Digite sua senha"/>
            </div>
            <p v-if="error" style="color: red;">{{ error }}</p>
            <button @click.prevent="login">Entrar</button>
        </form>
    </div>
</template>

<script>
    import api from '../services/api'

    export default {
        data() {
            return {
            email: '',
            password: '',
            error: ''
            }
        },
        methods: {
            async login() {
            try {
                const response = await api.post('/login', {
                email: this.email,
                password: this.password
                })

                localStorage.setItem('token', response.data.token)
                this.$router.push('/dashboard')
            } catch (err) {
                this.error = 'Email ou senha inválidos'
            }
            }
        }
    }
</script>