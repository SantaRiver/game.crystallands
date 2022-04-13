<template>
    <div id="game"
         class="container-fluid p-0 d-flex justify-content-between align-items-center flex-column bd-highlight">
        <game-panel></game-panel>
        <game-login-modal :showModal="showModal"></game-login-modal>
        <game-menu></game-menu>
    </div>
</template>

<script>
import GameMenu from './elements/game/GameMenu.vue'
import GamePanel from './elements/game/GamePanel.vue'
import GameLoginModal from './elements/game/GameLoginModal.vue'
import {mapGetters, mapActions} from "vuex";

export default {
    name: "Game",
    data: function (){
        return {
            showModal: false,
        }
    },
    components: {GamePanel, GameMenu, GameLoginModal},
    computed: mapGetters(["user", "isLoggedIn"]),
    methods: mapActions(["getUser"]),
    mounted() {
        console.log('isLoggedIn', this.isLoggedIn);
        this.showModal = !this.user.id;
    },
}
</script>

<style scoped>

</style>
