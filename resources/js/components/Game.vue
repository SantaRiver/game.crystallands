<template>
    <div id="game" class="container-fluid p-0 d-flex justify-content-between align-items-center flex-column bd-highlight">
            <game-panel></game-panel>
            <game-login-modal auth="true"></game-login-modal>
            <game-menu></game-menu>
    </div>
</template>

<script>
import GameMenu from './elements/game/GameMenu.vue'
import GameModal from './elements/game/GameLoginModal.vue'
import GamePanel from './elements/game/GamePanel.vue'

export default {
  components: { GamePanel, GameMenu, GameModal },
    name: "Game",
    props: ['user'],
    created: function () {
        //console.log('game', this.user);
    },
}
</script>

<style scoped>

</style>
