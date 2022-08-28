<script >

export default {
    name: 'AddPairView',
    data: () => ({
        currency_from: null,
        currency_to: null,
        rate: 0,
    }),
    created() {
    },
    async created() {
        this.currency_from = await this.currencies;
        this.currency_to = await this.currencies;
    },
    computed: {
        currencies() {
            return this.$store.state.currencies;
        },
        currencies_from() {
            return this.currencies.filter(currency => currency.id !== this.currency_to?.id);

        },
        currencies_to() {
            return this.currencies.filter(currency => currency.id !== this.currency_from?.id);
        }
    },
    methods: {
        createPair: async function (currency_from, currency_to, rate) {
            try {
                const res = await this.axios.post('/pairs', {
                    currency_id_from: currency_from.id,
                    currency_id_to: currency_to.id,
                    rate: rate
                })
                console.log(res);
            } catch (error) {
                console.log(error);
            }
        },
        confirmCreationPair: function () {
            this.$store.dispatch('openModal', {
                show: true,
                title: 'Confirmation',
                question: 'Voulez-vous vraiment mettre à jour cette paire ?',
                action: {
                    text: 'Créer',
                    callable: () => this.createPair(this.currency_from, this.currency_to, this.rate)
                }
            })
        }
    }
}

</script>

<template>
    <main>
        <h1>Ajouter une paire</h1>
        <form @submit.prevent="confirmCreationPair">
            <div class="form-group">
                <label for="currency_id_from">Devise de départ</label>
                <select name="currency_id_from" id="currency_id_from" v-model="currency_from">
                    <option v-for="currency in currencies_from" :value="currency">{{ currency.code }}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="currency_id_to">Devise d'arrivée</label>
                <select name="currency_id_to" id="currency_id_to" v-model="currency_to">
                    <option v-for="currency in currencies_to" :value="currency">{{ currency.code }}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="rate">Taux de conversion</label>
                <input type="number" name="rate" id="rate" v-model="rate" />
            </div>
            <div class="form-group">
                <button type="submit">Ajouter</button>
            </div>
        </form>
    </main>
</template>

<style scoped lang="scss">
</style>
