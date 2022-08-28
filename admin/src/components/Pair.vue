<script >

// Fetch api data 

export default {
    name: 'HomeView',
    props: {
        pair: {
            type: Object,
            required: true
        }
    },
    data: () => ({
        edit: false,
        currency_from: {},
        currency_to: {},
        rate : 0,
    }),
    computed: {
        currencies() {
            return this.$store.state.currencies;
        },
        currencies_from() {
            return this.currencies.filter(currency => currency.id !== this.currency_to.id);
        },
        currencies_to() {
            return this.currencies.filter(currency => currency.id !== this.currency_from.id);
        }
    },
    created() {
        this.currency_from = this.currencies.find(currency => currency.id === this.pair.currency_id_from);
        this.currency_to = this.currencies.find(currency => currency.id === this.pair.currency_id_to);
        this.rate = this.pair.rate;
    },
    methods: {
        editPair: async function() {
            this.edit = true;
        },
        abort: function() {
            this.currencies_from = this.currencies.find(currency => currency.id === this.pair.currency_id_from);
            this.currencies_to = this.currencies.find(currency => currency.id === this.pair.currency_id_to);
            this.rate = this.currencies.find(currency => currency.id === this.pair.rate);
            this.edit = false;
        },
        updatePair: async function(id, pair, rate) {
            try {
              const res = await  this.axios.put(`/pairs/${id}`, {
                    ...pair,
                    name : `${this.currency_from.code}_${this.currency_to.code}`,
                    currency_id_from: this.currency_from.id,
                    currency_id_to: this.currency_to.id,
                    rate : rate
                })
                console.log(res);
                this.edit = false;
            } catch (error) {
                console.log(error);
            }
        },
        deletePair: async function (id) {
            console.log('delete');
            try {
                const { data: pairs } = await this.axios.delete(`/pairs/${id}`)
                this.pairs = await this.$store.dispatch('fetchPairs')
            }
            catch (error) {
                console.error(error);
            }
        },
        confirmUpdate: function() {
            this.$store.dispatch('openModal', {
                show: true,
                title: 'Confirmation',
                question: 'Voulez-vous vraiment mettre à jour cette paire ?',
                action: {
                    text: 'Mettre à jour',
                    callable: () => this.updatePair(this.pair.id, this.pair, this.rate)
                }
            })
        },
        confirmDelete: function () {
            this.$store.dispatch('openModal', {
                show: true,
                title: 'Supprimer',
                question: 'Voulez-vous vraiment supprimer cette paire ?',
                action: {
                    text: 'Supprimer',
                    callable: () => this.deletePair(this.pair.id)
                }
            })
        }
    }
}

</script>

<template>
    <li>
        <div class="pair-name" v-if="!edit">
            <div class="item">
                <span>Nom </span>
                <span>{{ pair.currency_from.code }} > {{ pair.currency_to.code }}</span>
            </div>
            <div class="item">
                <span>Taux :  </span>
                <span>{{ pair.rate }}</span>
            </div>
        </div>
        <div v-else>
            <select name="currency_from" id="from" v-model="currency_from">
                <option v-for="currency in currencies_from" :value="currency" :key="currency.id">{{ currency.code }}</option>
            </select>
            <select name="currency_to" id="to" v-model="currency_to">
                <option v-for="currency in currencies_to" :value="currency" :key="currency.id">{{ currency.code }}</option>
            </select>
            <input type="number" min="0"  name="rate" v-model="rate" >
            <button @click="confirmUpdate">Mettre à jour</button>
            <button @click="edit = false">Annuler</button>
        </div>
        <div class="pair-actions" v-if="!edit">
            <button @click="editPair" class="update">Editer</button>
            <button @click="confirmDelete" class="delete">Supprimer</button>
        </div>
    </li>
</template>


<style lang="scss" scoped>
li {
    display: flex;
    align-items: center;
    padding: 10px;
    justify-content: space-between;
    margin: 10px;
    max-width: 500px;
    border: 1px solid #ccc;
    height: 60px;
    background-color: rgb(244, 239, 239);
    border-radius: 5px ;

}
.pair-name {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 00.5rem;

    input, select {
        width: 100%;
        border: 1px solid #ccc;
        height: 1rem;
    }

}
.pair-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 00.5rem;
    button {
        border: 1px solid #ccc;
        height: 1rem;
        background-color: transparent;
        color: #000;
        border-radius: 0;
        font-size: 1rem;
        margin-left: 1rem;
        height: 2rem;
        padding:0 0.5rem;
        cursor: pointer;
        &.update 
        {
            background-color: #c0900b;
            color: #fff;
        }
        &.delete {
            background-color: #f44336;
            color: #fff;
        }

    }
}
</style>