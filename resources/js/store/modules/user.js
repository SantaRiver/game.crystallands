export default {
    actions: {
        getUser({commit, getters, dispatch}, user = 1) {
            axios.get(`/api/users/${user}`).then(res => {
                commit('setUser', res.data);
                console.log(getters.user.id)
            });
        },
        setUser({commit, getters, dispatch}, user) {
            commit('setUser', user);
        },
        getUserProps({commit, getters, dispatch}, user = 1) {
            axios.get(`/api/users/${user}/props`).then(res => {
                commit('setUserProps', res.data);
            });
        },
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
        },
        setUserProps(state, userProps) {
            state.userProps = userProps;
        }
    },
    state: {
        user: {},
    },
    getters: {
        user(state) {
            return state.user
        },
        userProps(state) {
            return state.userProps
        },
        isLoggedIn(state) {
            return !!state.user.id;
        },
    }
}
