export default {
    actions: {
        getUser({commit, getters, dispatch}) {
            axios.get(`/api/users/${getters.user.id}`).then(res => {
                commit('setUser', res.data);
            });
            dispatch('getUserStats');
            dispatch('getUserWallet');
            dispatch('getUserResources');
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
        setUserWallet(state, UserWallet) {
            state.userWallet = UserWallet;
        },
        setUserResources(state, UserResources) {
            state.userResources = UserResources;
        }
    },
    state: {
        user: {},
        userStats: {},
        userWallet: {},
        userResources: {},
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
        isLoggedIn(state) {
            return !!state.user.id;
        },
    }
}
