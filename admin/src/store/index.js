import { createStore } from "vuex";
import axios from "../utils";

const modalSchema = { 
    show: false,
    title: '',
    question: '',
    action: {
        callable: null,
        text : '',
    },
};

const store = createStore({
    state: {
        user: {
            data: {},
            token: null,
        },
        pairs : {},
        modal: {
            ...modalSchema,
        },
        currencies : [],
    },
    mutations: {
        setUser(state, user) {
            state.user.data = { ...user };
        },
        setToken(state, token) {
            state.user.token = token;
        },
        setPairs(state, pairs) {
            state.pairs = { ...pairs };
        },
        resetModal (state) {
            state.modal = { ...modalSchema };
        },
        setModal (state, modal) {
            state.modal = { ...modal };
        },
        setCurrencies (state, currencies) {
            state.currencies = currencies;
        }
    },
    actions: {
        setUser({ commit }, user) {
            commit('setUser', user);
        },
        setToken({ commit }, token) {
            commit('setToken', token);
        },
        closeModal({ commit }, confirm) {
            if(confirm) this.state.modal.action.callable();
            commit('resetModal');
        },
        openModal({ commit }, modal) {
            commit('resetModal');
            commit('setModal', modal);
        },
        async fetchPairs({ commit }) {
            try {
                const { data : pairs } = await axios.get('/pairs')
                commit('setPairs', pairs)
              }
              catch (error) {
                console.log(error);
              }
        },
        async fetchCurrencies({ commit }) {
            try {
                const { data : currencies } = await axios.get('/currencies')
                commit('setCurrencies', currencies)
              }
              catch (error) {
                console.log(error);
              }
        }
    },
    getters: {
        user: state => state.user.data,
        token: state => state.user.token,
        pairs: state => state.pairs,
        currencies: state => state.currencies,
    },
});


export default store;