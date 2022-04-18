export default {
    actions: {
        getUser({commit, getters, dispatch}) {
            axios.get(`/api/users/${getters.user.id}`).then(res => {
                commit('setUser', res.data);
            });
            dispatch('getUserStats');
            dispatch('getUserWallet');
            dispatch('getUserResources');
            dispatch('getUserInventory');
        },
        getUserStats({commit, getters}) {
            axios.get(`/api/users/${getters.user.id}/stats`).then(res => {
                commit('setUserStats', res.data);
            });
        },
        getUserWallet({commit, getters}) {
            axios.get(`/api/users/${getters.user.id}/wallet`).then(res => {
                commit('setUserWallet', res.data);
            });
        },
        getUserResources({commit, getters}) {
            axios.get(`/api/users/${getters.user.id}/resources`).then(res => {
                commit('setUserResources', res.data);
            });
        },
        getUserInventory({commit, getters}) {
            axios.get(`/api/users/${getters.user.id}/inventory`).then(res => {
                commit('setUserInventory', res.data);
            });
        },
        loginUser({dispatch}, loginRequest) {
            axios.post('/login', loginRequest).then(res => {
                dispatch('setUser', res.data.user);
            });
        },
        setUser({commit, getters, dispatch}, user) {
            if (user.id){
                commit('setUser', user);
                dispatch('getUser');
            }
        },
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
        },
        setUserStats(state, userStats) {
            state.userStats = userStats;
        },
        setUserWallet(state, userWallet) {
            state.userWallet = userWallet;
        },
        setUserResources(state, userResources) {
            state.userResources = userResources;
        },
        setUserInventory(state, userInventory) {
            state.userInventory = userInventory;
        }
    },
    state: {
        user: {},
        userStats: {},
        userWallet: {},
        userResources: {},
        userInventory: {},
    },
    getters: {
        user(state) {
            return state.user
        },
        userStats(state) {
            return state.userStats
        },
        userWallet(state) {
            return state.userWallet
        },
        userResources(state) {
            return state.userResources
        },
        userInventory(state) {
            return state.userInventory
        },
        isLoggedIn(state) {
            return !!state.user.id;
        },
    }
}
