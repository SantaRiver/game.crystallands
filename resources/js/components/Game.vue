<template>
    <div id="game" class="container-fluid p-0 d-flex justify-content-between align-items-center flex-column bd-highlight">
        <div class="p-2">
            <game-panel></game-panel>
        </div>
        <div class="p-2">
            <game-modal></game-modal>
        </div>
        <div class="p-2">
            <game-menu></game-menu>
        </div>
    </div>
</template>

<script>
import GameMenu from './elements/game/GameMenu.vue'
import GameModal from './elements/game/GameModal.vue'
import GamePanel from './elements/game/GamePanel.vue'
export default {
  components: { GamePanel, GameMenu, GameModal },
    name: "Game"
}
</script>

<style scoped>

</style>
