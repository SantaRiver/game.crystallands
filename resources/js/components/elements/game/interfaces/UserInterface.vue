<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">User {{ user.name }}</h5>
                        </div>
                        <div class="modal-body container">
                            <div class="row">
                                <div class="col-6">
                                    <p class="fw-bold h5">Stats</p>
                                    <p>Health - {{ userStats.health }} / 100</p>
                                    <p>Hunger - {{ userStats.hunger }} / 300</p>
                                    <p>Energy - {{ userStats.energy }} / 100</p>
                                    <p>Balance - {{ userWallet.balance }} CRY</p>
                                </div>
                                <div class="col-6">
                                    <p class="fw-bold h5">Resources</p>
                                    <p>Stone - {{ userResources.stone }}</p>
                                    <p>Wood - {{ userResources.wood }}</p>
                                    <p>Iron - {{ userResources.iron }}</p>
                                    <p>Iron Ingot - {{ userResources.iron_ingot }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Close</button>
<!--                            <button type="button" class="btn btn-primary">Save changes</button>-->
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
    computed: mapGetters(["user", "userStats", "userWallet", "userResources"]),
    mounted() {
        console.log('GameInterfaceModal mounted');
    },
    methods: {
        closeModal() {
            this.$emit('closeModal');
        }
    }
}
</script>

