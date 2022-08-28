<script  >
import { RouterLink, RouterView } from 'vue-router'
// set token in store if exist in localstorage
export default {
  name: 'HomeView',
  async beforeCreate() {
    this.$store.dispatch("fetchCurrencies");
    this.$store.dispatch("fetchPairs");
    if (window.localStorage.getItem('token') !== null && window.localStorage.getItem('token') !== undefined) {
      this.$store.commit('setToken', window.localStorage.getItem('token'));
    }
  },
  methods: {
     async logout(){ 
       console.log(this.$store.state.user)
      try {
        const res = await this.axios.post('/logout', {
          user : this.$store.state.user.data
        })
        console.log(res)

      } catch (error) {
        console.log(error)
      }
        window.localStorage.removeItem('token')
        this.$store.state.user.token = null
        this.$router.push('/login')
    }
  },
  computed: {
    show() {
      return this.$store.state.modal.show;
    }
  },
}

</script>

<template>
  <header>
    <div class="wrapper" >
      <nav>
        <div class="">
          <RouterLink to="/">Dashboard</RouterLink>
          <RouterLink to="/addpair">Ajouter une paire</RouterLink>
        </div>
        <button @click.prevent="logout"><a href="#">Se déconnecter</a></button>
        <!-- <a @click.prevent="logout" >Se Déconnecter</a> -->
      </nav>
    </div>
  </header>

  <RouterView />
  <div class="confirm-modal">
    <div class="modal-content" v-show="show">
      <h1>{{ $store.state.modal.title }}</h1>
      <p>-{{ $store.state.modal.question }}</p>
      <button @click="$store.dispatch('closeModal', true)">{{ $store.state.modal.action.text }}</button>
      <button @click="$store.dispatch('closeModal', false)">Annuler</button>
    </div>
  </div>
</template>

<style scoped lang="scss">
nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: solid 1px #ccc;
  padding: 1rem 0;

  button {
    border: none;
    background: none;
    cursor: pointer;
  }

  a {
    color: #000;
    text-decoration: none;
    font-size: 1.5rem;
    font-weight: 700;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    transition: all 0.3s ease;

    &:hover {}
  }
}

.modal-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1;
}
</style>
