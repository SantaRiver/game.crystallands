<template>
    <div>
        <div class="menu container-fluid container-xl d-flex justify-content-center p-3">
            <button type="button" class="menu__item button" @click="showModal = true">User</button>
        </div>
        <user-interface v-if="showModal" @closeModal="showModal = false"></user-interface>
    </div>
</template>

<script>
export default {
    name: "Menu",
    data: function () {
        return {
            showModal: false,
        };
    },
};
</script>

<style scoped>

</style>
