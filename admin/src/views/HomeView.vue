<script >
import Pair from '../components/Pair.vue';

export default {
  name: 'HomeView',
  computed: {
    pairs() {
      return this.$store.state.pairs;
    },
    currencies() {
      return this.$store.state.currencies;
    },
    show() {
      return this.$store.state.modal.show;
    }
  },
  components: {
    Pair: Pair
  }
}

</script>

<template>
  <main>
    <div class="wrapper">
      <div class="pairs">
        <ul>
          <Pair v-for="pair in pairs" :key="pair.id" :pair="pair" />
        </ul>
      </div>
    </div>
  </main>
</template>

<style scoped lang="scss">
  .wrapper {
    display: flex;
    justify-content: center;
    flex-direction: column;
    padding: 2rem;
    ul {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      flex-direction: column;
      gap : 1rem;
    }
  }
</style>
