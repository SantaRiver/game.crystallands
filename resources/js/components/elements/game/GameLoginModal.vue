<template>
    <transition name="modal">
        <div v-if="!isLoggedIn">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-dialog" role="document">
                        <div class="modal-login-content">
                            <div class="modal-body">
                                <div class="container d-flex flex-column justify-content-center align-items-center">
                                    <wax-login-button/>
                                    <anchor-login-button/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
import AnchorLoginButton from "../../api/AnchorLoginButton.vue";
import WaxLoginButton from "../../api/WaxLoginButton.vue";
import {mapGetters} from "vuex";

export default {
    name: "GameLoginModal",
    computed: mapGetters(["isLoggedIn"]),
    components: {AnchorLoginButton, WaxLoginButton},
};
</script>

<style scoped>
</style>
