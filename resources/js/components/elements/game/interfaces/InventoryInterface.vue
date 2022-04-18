<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Inventory {{ user.name }}</h5>
                        </div>
                        <div class="modal-body container">
                            <div class="row text-center px-4">
                                <div class="col-5 p-2 inventory-game-column border"
                                     style="background-color: rgb(116 255 133 / 20%);">
                                    <p class="fw-bold h5 mb-4">In game</p>
                                    <p v-for="item in userInventory['game']" class="h5">
                                        <span v-if="item.quantity > 1">{{ item.quantity }}x </span>
                                        {{ item.name }}
                                    </p>
                                </div>
                                <div class="col-2 d-flex align-items-center justify-content-center">
                                    <button class="btn btn-primary" v-bind:disabled="true">←</button>
                                </div>
                                <div class="col-5 p-2 inventory-atomic-column border"
                                     style="background-color: rgba(255, 219, 110, 0.20);">
                                    <p class="fw-bold h5 mb-4">In AtomicHub</p>
                                    <p v-for="item in userInventory['atomic']" class="h5">
                                        <span v-if="item.quantity > 1">{{ item.quantity }}x </span>
                                        {{ item.name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "GameInterfaceModal",
    props: ['showModal'],
    computed: mapGetters(["user", "userInventory"]),
    mounted() {
        console.log(this.userInventory);
    },
    methods: {
        closeModal() {
            this.$emit('closeModal');
        }
    }
}
</script>

