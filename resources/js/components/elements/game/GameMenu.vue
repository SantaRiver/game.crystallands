<template>
    <div>
        <div class="menu container-fluid container-xl d-flex justify-content-center p-3">
            <button type="button" class="menu__item button" @click="showUserInterface = true">User</button>
            <button type="button" class="menu__item button" @click="showInventoryInterface = true">Inventory</button>
        </div>

        <user-interface v-if="showUserInterface" @closeModal="showUserInterface = false"></user-interface>
        <inventory-interface v-if="showInventoryInterface" @closeModal="showInventoryInterface = false"></inventory-interface>
    </div>
</template>

<script>
export default {
    name: "Menu",
    data: function () {
        return {
            showUserInterface: false,
            showInventoryInterface: false,
        };
    },
};
</script>

<style scoped>

</style>
