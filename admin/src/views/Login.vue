<template>
    <div>
        <h1>login</h1>
        <form @submit.prevent="login">
            <input type="email" name="email" v-model="email">
            <input type="password" name="password" v-model="password">
            <button type="submit">Se connecter</button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            email: '',
            password: '',
        }
    },
    methods: {
        async login() {
            try {
                const { data } = await this.axios.post('/login', {
                    email: this.email,
                    password: this.password
                })
                const user = await data.user
                this.$store.commit('setToken', data.access_token)
                this.$store.commit('setUser', user)
                window.localStorage.setItem('token', data.access_token)
                this.$router.push('/');
            } catch (error) {
                console.log(error);
            }
        }
    }
}
</script>